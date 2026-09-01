<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_log_in_with_valid_credentials(): void
    {
        $user = User::factory()->superAdmin()->create(['password' => 'correct-horse-1']);

        $this->post(route('admin.login.attempt'), [
            'email' => $user->email,
            'password' => 'correct-horse-1',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($user);
        $this->assertNotNull($user->fresh()->last_login_at);
    }

    public function test_invalid_credentials_are_rejected(): void
    {
        $user = User::factory()->create(['password' => 'correct-horse-1']);

        $this->post(route('admin.login.attempt'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_inactive_admin_cannot_log_in(): void
    {
        $user = User::factory()->inactive()->create(['password' => 'correct-horse-1']);

        $this->post(route('admin.login.attempt'), [
            'email' => $user->email,
            'password' => 'correct-horse-1',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_inactive_admin_is_ejected_from_the_panel(): void
    {
        $user = User::factory()->inactive()->create();

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_guests_cannot_reach_the_admin_panel(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.projects.index'))->assertRedirect(route('admin.login'));
        $this->post(route('admin.projects.store'), [])->assertRedirect(route('admin.login'));
        $this->get(route('admin.preview'))->assertRedirect(route('admin.login'));
    }

    public function test_there_is_no_public_registration(): void
    {
        foreach (['/register', '/admin/register', '/admin/signup'] as $path) {
            $this->get($path)->assertNotFound();
            $this->post($path, [])->assertNotFound();
        }

        $this->assertFalse(app('router')->getRoutes()->hasNamedRoute('register'));
    }

    public function test_logout_invalidates_the_session(): void
    {
        $user = User::factory()->superAdmin()->create();

        $this->actingAs($user)
            ->post(route('admin.logout'))
            ->assertRedirect(route('admin.login'));

        $this->assertGuest();
    }

    public function test_editor_cannot_manage_admin_users(): void
    {
        $editor = User::factory()->editor()->create();

        $this->actingAs($editor)->get(route('admin.users.index'))->assertForbidden();
        $this->actingAs($editor)->get(route('admin.activity.index'))->assertForbidden();
    }

    public function test_editor_can_reach_content_screens(): void
    {
        $editor = User::factory()->editor()->create();

        $this->actingAs($editor)->get(route('admin.projects.index'))->assertOk();
        $this->actingAs($editor)->get(route('admin.media.index'))->assertOk();
    }
}
