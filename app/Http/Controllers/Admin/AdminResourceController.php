<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Concerns\HasRevisions;
use App\Services\ActivityLogger;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Schema-driven CRUD for the CMS resources. Each subclass declares its model,
 * its field schema and its list columns; everything else (validation wiring,
 * media fields, ordering, audit logging, flash messaging) is shared so the
 * admin behaves identically across every resource.
 */
abstract class AdminResourceController extends Controller
{
    public function __construct(protected readonly ActivityLogger $logger) {}

    /** @return class-string<Model> */
    abstract protected function model(): string;

    /** Route name segment, e.g. "projects" for admin.projects.index. */
    abstract protected function routeBase(): string;

    abstract protected function title(): string;

    /** @return list<array<string, mixed>> */
    abstract protected function schema(): array;

    /** @return array<string, array<int, mixed>|string> */
    abstract protected function rules(?Model $record = null): array;

    /** @return list<array{key: string, label: string, type?: string}> */
    abstract protected function listColumns(): array;

    protected function singular(): string
    {
        return Str::singular($this->title());
    }

    /** Columns matched against the ?q= search box. */
    protected function searchable(): array
    {
        return ['name', 'title'];
    }

    protected function supportsOrdering(): bool
    {
        return in_array('sort_order', $this->fieldNames(), true);
    }

    protected function perPage(): int
    {
        return 25;
    }

    protected function baseQuery(): Builder
    {
        $model = $this->model();

        return $model::query();
    }

    protected function indexQuery(Request $request): Builder
    {
        $query = $this->baseQuery();

        if ($term = trim((string) $request->query('q', ''))) {
            $columns = $this->searchable();

            $query->where(function (Builder $q) use ($columns, $term) {
                foreach ($columns as $column) {
                    $q->orWhere($column, 'like', '%'.$term.'%');
                }
            });
        }

        return $this->supportsOrdering()
            ? $query->orderBy('sort_order')->orderBy('id')
            : $query->orderByDesc('id');
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', $this->model());

        return view('admin.resource.index', [
            'title' => $this->title(),
            'singular' => $this->singular(),
            'routeBase' => $this->routeBase(),
            'columns' => $this->listColumns(),
            'records' => $this->indexQuery($request)->paginate($this->perPage())->withQueryString(),
            'ordering' => $this->supportsOrdering(),
            'q' => $request->query('q', ''),
            'intro' => $this->intro(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', $this->model());

        $model = $this->model();

        return view('admin.resource.form', $this->formData(new $model, 'create'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', $this->model());

        $data = $this->validated($request, null);

        $model = $this->model();
        /** @var Model $record */
        $record = new $model;
        $record->fill($this->transform($data, $record))->save();

        $this->afterSave($record, $request, true);

        $this->logger->logSaved('created', $record, $this->singular().' "'.$this->recordLabel($record).'" created.');

        return redirect()
            ->route('admin.'.$this->routeBase().'.edit', $record)
            ->with('status', $this->singular().' created.');
    }

    public function edit(Model|int|string $record): View
    {
        $record = $this->resolve($record);
        $this->authorize('update', $record);

        return view('admin.resource.form', $this->formData($record, 'edit'));
    }

    public function update(Request $request, Model|int|string $record): RedirectResponse
    {
        $record = $this->resolve($record);
        $this->authorize('update', $record);

        $data = $this->validated($request, $record);

        if (in_array(HasRevisions::class, class_uses_recursive($record), true)) {
            $record->recordRevision(null, 'Before update');
        }

        $record->fill($this->transform($data, $record))->save();

        $this->afterSave($record, $request, false);

        $this->logger->logSaved('updated', $record, $this->singular().' "'.$this->recordLabel($record).'" updated.');

        return redirect()
            ->route('admin.'.$this->routeBase().'.edit', $record)
            ->with('status', $this->singular().' saved.');
    }

    public function destroy(Model|int|string $record): RedirectResponse
    {
        $record = $this->resolve($record);
        $this->authorize('delete', $record);

        $label = $this->recordLabel($record);
        $this->beforeDelete($record);
        $record->delete();

        $this->logger->log('deleted', $record, $this->singular().' "'.$label.'" deleted.');

        return redirect()
            ->route('admin.'.$this->routeBase().'.index')
            ->with('status', $this->singular().' deleted.');
    }

    /** Persists the numeric order boxes from the index screen. */
    public function reorder(Request $request): RedirectResponse
    {
        $this->authorize('update', $this->model());

        $order = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['required', 'integer', 'min:0', 'max:100000'],
        ])['order'];

        $model = $this->model();

        foreach ($order as $id => $position) {
            $model::query()->whereKey($id)->update(['sort_order' => (int) $position]);
        }

        $this->logger->log('reordered', null, $this->title().' reordered.');

        return back()->with('status', 'Order updated.');
    }

    // ---------------------------------------------------------------- helpers

    protected function intro(): ?string
    {
        return null;
    }

    protected function resolve(Model|int|string $record): Model
    {
        if ($record instanceof Model) {
            return $record;
        }

        $model = $this->model();

        return $model::query()->findOrFail($record);
    }

    protected function validated(Request $request, ?Model $record): array
    {
        $data = $request->validate($this->rules($record));

        foreach ($this->schema() as $field) {
            $name = $field['name'];

            if (($field['type'] ?? 'text') === 'checkbox') {
                $data[$name] = $request->boolean($name);
            }

            if (($field['type'] ?? '') === 'list' && array_key_exists($name, $data)) {
                $data[$name] = collect(preg_split('/\r\n|\r|\n/', (string) $data[$name]))
                    ->map(fn ($l) => trim($l))
                    ->filter()
                    ->values()
                    ->all();
            }
        }

        return $data;
    }

    /** Last chance to adjust attributes before they hit the model. */
    protected function transform(array $data, Model $record): array
    {
        return $data;
    }

    protected function afterSave(Model $record, Request $request, bool $created): void {}

    protected function beforeDelete(Model $record): void {}

    protected function recordLabel(Model $record): string
    {
        foreach (['name', 'title', 'label', 'client_name', 'platform', 'key'] as $attr) {
            if (filled($record->{$attr} ?? null)) {
                return (string) $record->{$attr};
            }
        }

        return '#'.$record->getKey();
    }

    /** @return array<string, mixed> */
    protected function formData(Model $record, string $mode): array
    {
        return [
            'title' => $this->title(),
            'singular' => $this->singular(),
            'routeBase' => $this->routeBase(),
            'schema' => $this->schema(),
            'record' => $record,
            'mode' => $mode,
            'extra' => $this->formExtras($record),
        ];
    }

    /** @return array<string, mixed> */
    protected function formExtras(Model $record): array
    {
        return [];
    }

    /** @return list<string> */
    protected function fieldNames(): array
    {
        return array_column($this->schema(), 'name');
    }
}
