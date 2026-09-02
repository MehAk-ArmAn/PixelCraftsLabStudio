<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use App\Models\ContactSubmission;
use App\Models\GrowthPlan;
use App\Models\Media;
use App\Models\Package;
use App\Models\Project;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $canManageAdministration = $request->user()?->canManageAdministration() ?? false;
        $canManageSecurity = $request->user()?->canManageSecurity() ?? false;
        $projects = Project::query()->selectRaw(
            'count(*) as total,'
            .' sum(case when is_published = 1 and is_archived = 0 then 1 else 0 end) as published,'
            .' sum(case when is_published = 0 and is_archived = 0 then 1 else 0 end) as drafts,'
            .' sum(case when is_marketing_case_study = 1 then 1 else 0 end) as case_studies'
        )->first();

        $cards = [
            ['label' => 'Total projects', 'value' => (int) $projects->total, 'href' => route('admin.projects.index')],
            ['label' => 'Published projects', 'value' => (int) $projects->published, 'href' => route('admin.projects.index')],
            ['label' => 'Draft projects', 'value' => (int) $projects->drafts, 'href' => route('admin.projects.index'), 'tone' => (int) $projects->drafts > 0 ? 'warn' : null],
            ['label' => 'Services', 'value' => Service::where('track', Service::TRACK_BUILD)->count(), 'href' => route('admin.services.index')],
            ['label' => 'Marketing services', 'value' => Service::where('track', Service::TRACK_GROWTH)->count(), 'href' => route('admin.marketing-services.index')],
            ['label' => 'Growth plans', 'value' => GrowthPlan::count(), 'href' => route('admin.growth-plans.index')],
            ['label' => 'Pricing packages', 'value' => Package::count(), 'href' => route('admin.packages.index')],
            ['label' => 'Team members', 'value' => TeamMember::count(), 'href' => route('admin.team.index')],
            ['label' => 'Testimonials', 'value' => Testimonial::count(), 'href' => route('admin.testimonials.index')],
            ['label' => 'Media files', 'value' => Media::count(), 'href' => route('admin.media.index')],
        ];

        if ($canManageAdministration) {
            $unread = ContactSubmission::unread()->count();
            $cards[] = ['label' => 'Unread enquiries', 'value' => $unread, 'href' => route('admin.enquiries.index'), 'tone' => $unread > 0 ? 'alert' : null];
            $cards[] = ['label' => 'Total enquiries', 'value' => ContactSubmission::count(), 'href' => route('admin.enquiries.index')];
        }

        return view('admin.dashboard', [
            'cards' => $cards,
            'recentEnquiries' => $canManageAdministration ? ContactSubmission::query()->latest()->limit(6)->get() : collect(),
            'recentActivity' => $canManageSecurity ? AdminActivityLog::query()->with('user')->latest()->limit(8)->get() : collect(),
            'recentlyUpdated' => Project::query()->latest('updated_at')->limit(5)->get(),
            'needsAttention' => $this->needsAttention($canManageAdministration),
            'canManageAdministration' => $canManageAdministration,
            'canManageSecurity' => $canManageSecurity,
        ]);
    }

    /** @return list<array{label: string, href: string}> */
    private function needsAttention(bool $canManageAdministration): array
    {
        $items = [];

        $draftProjects = Project::query()->where('is_published', false)->where('is_archived', false)->count();
        if ($draftProjects > 0) {
            $items[] = ['label' => $draftProjects.' project'.($draftProjects === 1 ? '' : 's').' still in draft', 'href' => route('admin.projects.index')];
        }

        $noImage = Project::query()->live()->whereNull('primary_image')->count();
        if ($noImage > 0) {
            $items[] = ['label' => $noImage.' published project'.($noImage === 1 ? '' : 's').' without an image', 'href' => route('admin.projects.index')];
        }

        $unpublishedTestimonials = Testimonial::query()->where('is_published', false)->count();
        if ($unpublishedTestimonials > 0) {
            $items[] = ['label' => $unpublishedTestimonials.' testimonial'.($unpublishedTestimonials === 1 ? '' : 's').' awaiting publication', 'href' => route('admin.testimonials.index')];
        }

        $unreadEnquiries = $canManageAdministration ? ContactSubmission::unread()->count() : 0;
        if ($unreadEnquiries > 0) {
            $items[] = ['label' => $unreadEnquiries.' unread enquir'.($unreadEnquiries === 1 ? 'y' : 'ies'), 'href' => route('admin.enquiries.index')];
        }

        $hiddenPlans = GrowthPlan::query()->where('is_published', false)->count();
        if ($hiddenPlans > 0) {
            $items[] = ['label' => $hiddenPlans.' growth plan'.($hiddenPlans === 1 ? '' : 's').' unpublished', 'href' => route('admin.growth-plans.index')];
        }

        return $items;
    }
}
