<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;

class MarketingServiceController extends ServiceController
{
    protected function track(): string
    {
        return Service::TRACK_GROWTH;
    }

    protected function routeBase(): string
    {
        return 'marketing-services';
    }

    protected function title(): string
    {
        return 'Marketing Services';
    }

    protected function singular(): string
    {
        return 'Marketing service';
    }

    protected function intro(): ?string
    {
        return 'Marketing and growth capabilities. Top-level entries appear as capability groups; '
            .'set a parent to nest a sub-service beneath one.';
    }
}
