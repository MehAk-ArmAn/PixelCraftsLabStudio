<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use App\Models\ContactSubmission;
use App\Models\GrowthPlan;
use App\Models\MarketingCampaign;
use App\Models\MarketingChannel;
use App\Models\Package;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarketingOverviewController extends Controller
{
    public function __invoke(Request $request): View
    {
        $canManageAdministration = $request->user()?->canManageAdministration() ?? false;
        $marketingTypes = [Service::class, GrowthPlan::class, Package::class, MarketingCampaign::class, MarketingChannel::class];

        $cards = [
            ['label' => 'Published marketing services', 'value' => Service::where('track', Service::TRACK_GROWTH)->where('is_published', true)->count(), 'href' => route('admin.marketing-services.index')],
            ['label' => 'Hidden marketing services', 'value' => Service::where('track', Service::TRACK_GROWTH)->where('is_published', false)->count(), 'href' => route('admin.marketing-services.index')],
            ['label' => 'Active growth plans', 'value' => GrowthPlan::where('is_published', true)->count(), 'href' => route('admin.growth-plans.index')],
            ['label' => 'Published packages', 'value' => Package::where('is_published', true)->count(), 'href' => route('admin.packages.index')],
            ['label' => 'Marketing case studies', 'value' => Project::where('is_marketing_case_study', true)->count(), 'href' => route('admin.projects.index')],
            ['label' => 'Campaigns', 'value' => MarketingCampaign::count(), 'href' => route('admin.campaigns.index')],
            ['label' => 'Channels enabled', 'value' => MarketingChannel::where('is_enabled', true)->count(), 'href' => route('admin.channels.index')],
        ];

        if ($canManageAdministration) {
            $cards[] = ['label' => 'Marketing enquiries', 'value' => ContactSubmission::where('is_marketing_enquiry', true)->count(), 'href' => route('admin.enquiries.index', ['marketing' => 1])];
        }

        return view('admin.marketing.overview', [
            'cards' => $cards,
            'services' => Service::query()->where('track', Service::TRACK_GROWTH)->whereNull('parent_id')->with('children')->ordered()->get(),
            'plans' => GrowthPlan::query()->with('items')->ordered()->get(),
            'caseStudies' => Project::query()->where('is_marketing_case_study', true)->ordered()->get(),
            'campaigns' => MarketingCampaign::query()->ordered()->limit(8)->get(),
            'enquiries' => $canManageAdministration ? ContactSubmission::query()->where('is_marketing_enquiry', true)->latest()->limit(6)->get() : collect(),
            'canManageAdministration' => $canManageAdministration,
            'recent' => AdminActivityLog::query()->whereIn('resource_type', $marketingTypes)->latest()->limit(8)->get(),
        ]);
    }
}
