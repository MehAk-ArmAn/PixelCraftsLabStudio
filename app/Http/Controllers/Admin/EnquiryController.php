<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EnquiryController extends Controller
{
    public function __construct(private readonly ActivityLogger $logger) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', \App\Models\Project::class);

        $query = ContactSubmission::query()->latest();

        if ($term = trim((string) $request->query('q', ''))) {
            $query->where(fn ($q) => $q
                ->where('name', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")
                ->orWhere('message', 'like', "%{$term}%")
                ->orWhere('business_name', 'like', "%{$term}%"));
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($request->boolean('unread')) {
            $query->whereNull('read_at');
        }

        if ($request->boolean('marketing')) {
            $query->where('is_marketing_enquiry', true);
        }

        return view('admin.enquiries.index', [
            'enquiries' => $query->paginate(25)->withQueryString(),
            'q' => $request->query('q', ''),
            'status' => $status,
            'counts' => [
                'all' => ContactSubmission::count(),
                'unread' => ContactSubmission::unread()->count(),
                'marketing' => ContactSubmission::where('is_marketing_enquiry', true)->count(),
            ],
        ]);
    }

    public function show(ContactSubmission $enquiry): View
    {
        $this->authorize('view', \App\Models\Project::class);

        if (! $enquiry->read_at) {
            $enquiry->forceFill([
                'read_at' => now(),
                'status' => $enquiry->status === ContactSubmission::STATUS_NEW
                    ? ContactSubmission::STATUS_READ
                    : $enquiry->status,
            ])->save();
        }

        return view('admin.enquiries.show', ['enquiry' => $enquiry]);
    }

    public function update(Request $request, ContactSubmission $enquiry): RedirectResponse
    {
        $this->authorize('update', \App\Models\Project::class);

        $data = $request->validate([
            'status' => ['required', Rule::in(ContactSubmission::STATUSES)],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $enquiry->fill($data);

        if ($data['status'] === ContactSubmission::STATUS_REPLIED && ! $enquiry->replied_at) {
            $enquiry->replied_at = now();
        }

        if ($data['status'] === ContactSubmission::STATUS_ARCHIVED && ! $enquiry->archived_at) {
            $enquiry->archived_at = now();
        }

        $enquiry->save();

        $this->logger->logSaved('updated', $enquiry, 'Enquiry from '.$enquiry->name.' updated.');

        return back()->with('status', 'Enquiry updated.');
    }

    public function toggleRead(ContactSubmission $enquiry): RedirectResponse
    {
        $this->authorize('update', \App\Models\Project::class);

        $enquiry->forceFill([
            'read_at' => $enquiry->read_at ? null : now(),
            'status' => $enquiry->read_at ? ContactSubmission::STATUS_NEW : ContactSubmission::STATUS_READ,
        ])->save();

        return back()->with('status', 'Enquiry marked as '.($enquiry->read_at ? 'read' : 'unread').'.');
    }

    public function destroy(ContactSubmission $enquiry): RedirectResponse
    {
        $this->authorize('delete', \App\Models\Project::class);

        $name = $enquiry->name;
        $enquiry->delete();

        $this->logger->log('deleted', null, 'Enquiry from '.$name.' deleted.');

        return redirect()->route('admin.enquiries.index')->with('status', 'Enquiry deleted.');
    }
}
