<?php

namespace App\Http\Controllers\Admin;

use App\Models\ContactOption;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContactOptionController extends AdminResourceController
{
    protected function model(): string
    {
        return ContactOption::class;
    }

    protected function routeBase(): string
    {
        return 'contact-options';
    }

    protected function title(): string
    {
        return 'Contact Options';
    }

    protected function singular(): string
    {
        return 'Contact option';
    }

    protected function intro(): ?string
    {
        return 'These are the choices the public enquiry form offers. Set the group to "growth" on marketing '
            .'options so marketing enquiries are flagged automatically.';
    }

    protected function searchable(): array
    {
        return ['label', 'value', 'type'];
    }

    protected function indexQuery(Request $request): Builder
    {
        $query = parent::indexQuery($request);

        if ($type = $request->query('type')) {
            $query->where('type', $type);
        }

        return $query->orderBy('type');
    }

    protected function listColumns(): array
    {
        return [
            ['key' => 'type', 'label' => 'Step'],
            ['key' => 'label', 'label' => 'Label'],
            ['key' => 'group', 'label' => 'Group'],
            ['key' => 'is_enabled', 'label' => 'Enabled', 'type' => 'bool'],
        ];
    }

    protected function schema(): array
    {
        return [
            ['name' => 'type', 'label' => 'Step', 'type' => 'select', 'options' => [
                'build' => 'What are you building',
                'scope' => 'How much of it',
                'timeline' => 'When do you need it',
                'service' => 'Service',
                'budget' => 'Budget',
            ]],
            ['name' => 'label', 'label' => 'Label', 'type' => 'text', 'required' => true],
            ['name' => 'value', 'label' => 'Stored value', 'type' => 'text', 'help' => 'Leave blank to reuse the label.'],
            ['name' => 'group', 'label' => 'Group', 'type' => 'text', 'datalist' => ['build', 'growth'], 'help' => 'Use "growth" to mark this as a marketing option.'],
            ['name' => 'is_enabled', 'label' => 'Enabled', 'type' => 'checkbox'],
            ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number'],
        ];
    }

    protected function rules(?Model $record = null): array
    {
        return [
            'type' => ['required', Rule::in(ContactOption::TYPES)],
            'label' => ['required', 'string', 'max:190'],
            'value' => ['nullable', 'string', 'max:190'],
            'group' => ['nullable', 'string', 'max:64'],
            'is_enabled' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
        ];
    }

    protected function transform(array $data, Model $record): array
    {
        if (blank($data['value'] ?? null)) {
            $data['value'] = $data['label'];
        }

        return $data;
    }

    protected function recordLabel(Model $record): string
    {
        return $record->label;
    }
}
