<?php
/** Dev-only smoke test: walk every admin GET route as a signed-in super admin. */

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

app()->singleton(\Illuminate\Contracts\Debug\ExceptionHandler::class, fn () => new class implements \Illuminate\Contracts\Debug\ExceptionHandler {
    public function report(\Throwable $e): void {}
    public function shouldReport(\Throwable $e): bool { return false; }
    public function render($request, \Throwable $e) { throw $e; }
    public function renderForConsole($output, \Throwable $e): void {}
});

$kernel = app(\Illuminate\Contracts\Http\Kernel::class);
$user = \App\Models\User::first();
auth()->login($user);

$project = \App\Models\Project::first();
$plan = \App\Models\GrowthPlan::first();
$page = \App\Models\Page::first();

$routes = [
    '/admin', '/admin/preview', '/admin/marketing',
    '/admin/projects', '/admin/projects/create', "/admin/projects/{$project->id}/edit",
    '/admin/services', '/admin/services/create',
    '/admin/marketing-services', '/admin/marketing-services/create',
    '/admin/growth-plans', '/admin/growth-plans/create', "/admin/growth-plans/{$plan->id}/edit",
    '/admin/campaigns', '/admin/campaigns/create',
    '/admin/channels', '/admin/channels/create',
    '/admin/process', '/admin/process/create',
    '/admin/team', '/admin/team/create',
    '/admin/socials', '/admin/socials/create',
    '/admin/testimonials', '/admin/testimonials/create',
    '/admin/navigation', '/admin/navigation/create',
    '/admin/contact-options', '/admin/contact-options/create',
    '/admin/pages', "/admin/pages/{$page->id}",
    '/admin/media', '/admin/media/browse',
    '/admin/enquiries',
    '/admin/settings', '/admin/settings/contact', '/admin/settings/features', '/admin/settings/seo', '/admin/settings/footer',
    '/admin/users', '/admin/users/create',
    '/admin/activity',
];

$fails = 0;

foreach ($routes as $route) {
    try {
        $request = \Illuminate\Http\Request::create($route, 'GET');
        $request->setLaravelSession(app('session.store'));
        $code = $kernel->handle($request)->getStatusCode();
        echo str_pad($route, 42).$code.($code >= 400 ? '   <-- FAIL' : '')."\n";
        $code >= 400 && $fails++;
    } catch (\Throwable $e) {
        $fails++;
        echo str_pad($route, 42).'EX  '.get_class($e).': '.$e->getMessage().' @ '.basename($e->getFile()).':'.$e->getLine()."\n";
    }
}

echo "\n".($fails === 0 ? 'All admin routes OK' : $fails.' route(s) failing')."\n";
