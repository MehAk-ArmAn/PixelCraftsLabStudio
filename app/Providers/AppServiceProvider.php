<?php

namespace App\Providers;

use App\Models\ContactOption;
use App\Models\GrowthPlan;
use App\Models\GrowthPlanItem;
use App\Models\HomepageFeaturedProject;
use App\Models\InteractiveExperience;
use App\Models\MarketingCampaign;
use App\Models\MarketingChannel;
use App\Models\Media;
use App\Models\NavigationItem;
use App\Models\Package;
use App\Models\PackageItem;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\ProcessStage;
use App\Models\Project;
use App\Models\ProjectMetric;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use App\Policies\AdminPolicy;
use App\Policies\UserPolicy;
use App\Services\SettingsRepository;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /** Every CMS resource shares the same content-level policy. */
    private const CONTENT_MODELS = [
        Project::class, ProjectMetric::class, Service::class, ProcessStage::class,
        TeamMember::class, SocialLink::class, Testimonial::class, Page::class,
        PageSection::class, NavigationItem::class, Media::class, SiteSetting::class,
        ContactOption::class, MarketingChannel::class, GrowthPlan::class,
        GrowthPlanItem::class, MarketingCampaign::class, Package::class, PackageItem::class,
        HomepageFeaturedProject::class, InteractiveExperience::class,
    ];

    public function register(): void
    {
        $this->app->singleton(SettingsRepository::class);
    }

    public function boot(): void
    {
        foreach (self::CONTENT_MODELS as $model) {
            Gate::policy($model, AdminPolicy::class);
        }

        Gate::policy(User::class, UserPolicy::class);

        Gate::define('manage-security', fn (User $user) => $user->canManageSecurity());
        Gate::define('manage-content', fn (User $user) => $user->canManageContent());
        Gate::define('manage-administration', fn (User $user) => $user->canManageAdministration());

        RateLimiter::for('pcl-contact', fn (Request $request) => [
            Limit::perMinute(5)->by($request->ip()),
            Limit::perDay(40)->by($request->ip()),
        ]);

        RateLimiter::for('pcl-login', fn (Request $request) => Limit::perMinute(10)->by($request->ip()));
    }
}
