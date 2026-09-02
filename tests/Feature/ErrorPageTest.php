<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\DataProvider;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

final class ErrorPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.debug' => false]);
        $this->app->detectEnvironment(fn (): string => 'production');
    }

    public function test_missing_routes_and_projects_render_the_branded_not_found_page(): void
    {
        $this->get('/this-route-does-not-exist')
            ->assertNotFound()
            ->assertSee('data-pcl-error-shell', false)
            ->assertSeeText('This page has moved—or never existed.');

        $this->get('/work/missing-project')
            ->assertNotFound()
            ->assertSee('data-pcl-error-shell', false)
            ->assertSeeText('This page has moved—or never existed.');
    }

    public function test_forbidden_admin_request_renders_the_admin_recovery_actions(): void
    {
        $editor = User::factory()->editor()->create();

        $this->actingAs($editor)
            ->get(route('admin.users.index'))
            ->assertForbidden()
            ->assertSee('data-pcl-error-shell', false)
            ->assertSeeText('That area is not available to you.')
            ->assertSeeText('Back to dashboard')
            ->assertSeeText('View website')
            ->assertDontSeeText('Contact the studio');
    }

    public function test_expired_session_exception_renders_the_419_page(): void
    {
        Route::get('/_test/expired-session', function (): never {
            throw new TokenMismatchException('private token mismatch detail');
        });

        $this->get('/_test/expired-session')
            ->assertStatus(419)
            ->assertSee('data-pcl-error-shell', false)
            ->assertSeeText('Your session has expired.')
            ->assertDontSeeText('private token mismatch detail');
    }

    public function test_throttled_request_renders_the_429_page(): void
    {
        Route::middleware('throttle:1,1')->get('/_test/rate-limited', fn (): string => 'ok');

        $this->get('/_test/rate-limited')->assertOk();

        $this->get('/_test/rate-limited')
            ->assertStatus(429)
            ->assertSee('data-pcl-error-shell', false)
            ->assertSeeText('A little too much, too quickly.');
    }

    public function test_unexpected_exception_renders_a_safe_500_page_in_production(): void
    {
        Route::get('/_test/server-error', function (): never {
            throw new RuntimeException(
                'SQLSTATE[HY000] password=super-secret /Users/private/app.php api_token=abc123',
            );
        });

        $this->get('/_test/server-error')
            ->assertServerError()
            ->assertSee('data-pcl-error-shell', false)
            ->assertSeeText('Something went off-grid.')
            ->assertDontSeeText('SQLSTATE')
            ->assertDontSeeText('super-secret')
            ->assertDontSeeText('/Users/private/app.php')
            ->assertDontSeeText('api_token');
    }

    public function test_maintenance_mode_renders_the_custom_503_page_and_is_always_disabled_afterward(): void
    {
        try {
            Artisan::call('down', ['--retry' => 60]);

            $this->get('/')
                ->assertStatus(503)
                ->assertSee('data-pcl-error-shell', false)
                ->assertSeeText('We are making a careful adjustment.');
        } finally {
            Artisan::call('up');
        }

        $this->assertFalse($this->app->maintenanceMode()->active());
    }

    #[DataProvider('specificStatusPages')]
    public function test_specific_status_pages_render_the_expected_safe_copy(
        int $status,
        string $copy,
    ): void {
        Route::get('/_test/status-'.$status, function () use ($status): never {
            throw new HttpException($status, 'private exception detail');
        });

        $this->get('/_test/status-'.$status)
            ->assertStatus($status)
            ->assertSee('data-pcl-error-shell', false)
            ->assertSeeText($copy)
            ->assertDontSeeText('private exception detail');
    }

    /** @return iterable<string, array{int, string}> */
    public static function specificStatusPages(): iterable
    {
        yield 'bad request' => [400, 'That request did not land cleanly.'];
        yield 'unauthorized' => [401, 'Sign in required.'];
        yield 'method not allowed' => [405, 'That action is not available here.'];
        yield 'request timeout' => [408, 'The request took too long.'];
        yield 'unprocessable content' => [422, 'We could not process that request.'];
        yield 'bad gateway' => [502, 'The upstream connection did not respond.'];
        yield 'gateway timeout' => [504, 'The upstream request timed out.'];
    }

    #[DataProvider('fallbackStatusPages')]
    public function test_status_family_fallback_pages_preserve_the_original_status(
        int $status,
        string $copy,
    ): void {
        Route::get('/_test/fallback-'.$status, function () use ($status): never {
            throw new HttpException($status, 'private fallback detail');
        });

        $this->get('/_test/fallback-'.$status)
            ->assertStatus($status)
            ->assertSee('data-pcl-error-shell', false)
            ->assertSeeText('Error '.$status)
            ->assertSeeText($copy)
            ->assertDontSeeText('private fallback detail');
    }

    /** @return iterable<string, array{int, string}> */
    public static function fallbackStatusPages(): iterable
    {
        yield 'unknown client error' => [418, 'That request could not be completed.'];
        yield 'unknown server error' => [507, 'The studio hit an unexpected issue.'];
    }

    public function test_api_and_ajax_errors_remain_json_without_html_error_markup(): void
    {
        Route::get('/api/_test/server-error', function (): never {
            throw new RuntimeException('private API failure');
        });
        Route::get('/_test/ajax-error', function (): never {
            throw new RuntimeException('private AJAX failure');
        });

        $this->get('/api/_test/server-error', ['Accept' => 'text/html'])
            ->assertServerError()
            ->assertHeader('content-type', 'application/json')
            ->assertJsonPath('message', 'Server Error')
            ->assertDontSee('data-pcl-error-shell', false)
            ->assertDontSeeText('private API failure');

        $this->getJson('/_test/ajax-error', ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertServerError()
            ->assertHeader('content-type', 'application/json')
            ->assertJsonPath('message', 'Server Error')
            ->assertDontSee('data-pcl-error-shell', false)
            ->assertDontSeeText('private AJAX failure');
    }

    public function test_validation_errors_remain_json_for_json_requests(): void
    {
        Route::post('/api/_test/validation-error', function (): never {
            throw ValidationException::withMessages(['email' => ['A safe validation message.']]);
        });

        $this->postJson('/api/_test/validation-error')
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('email')
            ->assertDontSee('data-pcl-error-shell', false);
    }

    public function test_error_page_still_renders_when_the_database_is_unavailable(): void
    {
        $defaultConnection = config('database.default');

        config([
            'database.connections.unavailable' => [
                'driver' => 'sqlite',
                'database' => '/missing/pixelcraftslab/database.sqlite',
                'prefix' => '',
                'foreign_key_constraints' => true,
            ],
        ]);

        Route::get('/_test/database-unavailable', function (): void {
            DB::connection('unavailable')->select('select 1');
        });

        try {
            $response = $this->get('/_test/database-unavailable');
        } finally {
            DB::purge('unavailable');
            config(['database.default' => $defaultConnection]);
        }

        $response
            ->assertServerError()
            ->assertSee('data-pcl-error-shell', false)
            ->assertSeeText('Something went off-grid.')
            ->assertDontSeeText('database.sqlite')
            ->assertDontSeeText('/missing/pixelcraftslab');
    }
}
