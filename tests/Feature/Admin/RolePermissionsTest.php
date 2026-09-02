<?php

namespace Tests\Feature\Admin;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class RolePermissionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_editors_can_manage_content_but_not_settings_or_enquiries(): void
    {
        $editor = User::factory()->editor()->create();
        Project::factory()->create();

        $this->actingAs($editor)->get(route('admin.projects.index'))->assertOk();
        $this->actingAs($editor)->get(route('admin.media.index'))->assertOk();
        $this->actingAs($editor)->get(route('admin.settings.edit'))->assertForbidden();
        $this->actingAs($editor)->get(route('admin.enquiries.index'))->assertForbidden();
    }

    public function test_admins_can_manage_settings_and_enquiries_but_only_super_admins_manage_users(): void
    {
        $admin = User::factory()->create();
        $super = User::factory()->superAdmin()->create();

        $this->actingAs($admin)->get(route('admin.settings.edit'))->assertOk();
        $this->actingAs($admin)->get(route('admin.enquiries.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.users.index'))->assertForbidden();
        $this->actingAs($super)->get(route('admin.users.index'))->assertOk();
    }
}
