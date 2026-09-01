<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectMetric;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProjectMetricController extends Controller
{
    public function __construct(private readonly ActivityLogger $logger) {}

    public function store(Request $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $project->metrics()->create($this->validated($request) + ['is_published' => true]);

        $this->logger->log('created', $project, 'Metric added to "'.$project->name.'".');

        return back()->with('status', 'Metric added.');
    }

    public function update(Request $request, Project $project, ProjectMetric $metric): RedirectResponse
    {
        $this->authorize('update', $project);
        abort_unless($metric->project_id === $project->id, 404);

        $metric->update($this->validated($request) + ['is_published' => $request->boolean('is_published')]);

        return back()->with('status', 'Metric updated.');
    }

    public function destroy(Project $project, ProjectMetric $metric): RedirectResponse
    {
        $this->authorize('update', $project);
        abort_unless($metric->project_id === $project->id, 404);

        $metric->delete();

        $this->logger->log('deleted', $project, 'Metric removed from "'.$project->name.'".');

        return back()->with('status', 'Metric removed.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'metric_label' => ['required', 'string', 'max:120'],
            'metric_value' => ['required', 'string', 'max:120'],
            'metric_context' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
        ]);
    }
}
