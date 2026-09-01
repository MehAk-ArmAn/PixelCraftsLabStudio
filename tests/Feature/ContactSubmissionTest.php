<?php

namespace Tests\Feature;

use App\Mail\ContactSubmissionReceived;
use App\Models\ContactOption;
use App\Models\ContactSubmission;
use App\Models\User;
use App\Services\SettingsRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

final class ContactSubmissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clear('pcl-contact');
        $this->seed(\Database\Seeders\SiteSettingsSeeder::class);
        $this->seed(\Database\Seeders\ContactOptionSeeder::class);
    }

    public function test_a_valid_enquiry_is_stored(): void
    {
        Mail::fake();

        $this->postJson(route('contact.store'), [
            'name' => 'Jane Cooper',
            'email' => 'jane@example.com',
            'message' => 'We need a website and some marketing.',
            'build_type' => 'Website',
            'scope' => 'A first version / MVP',
            'timeline' => 'As soon as possible',
        ])->assertCreated()->assertJson(['ok' => true]);

        $this->assertDatabaseHas('contact_submissions', [
            'name' => 'Jane Cooper',
            'email' => 'jane@example.com',
            'status' => ContactSubmission::STATUS_NEW,
        ]);
    }

    public function test_validation_rejects_incomplete_enquiries(): void
    {
        $this->postJson(route('contact.store'), ['name' => '', 'email' => 'nope'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email']);

        $this->assertSame(0, ContactSubmission::count());
    }

    public function test_values_outside_the_configured_options_are_rejected(): void
    {
        $this->postJson(route('contact.store'), [
            'name' => 'Jane',
            'email' => 'jane@example.com',
            'build_type' => 'Something the CMS does not offer',
        ])->assertStatus(422)->assertJsonValidationErrors('build_type');
    }

    public function test_the_honeypot_blocks_bots(): void
    {
        $this->postJson(route('contact.store'), [
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'company_website' => 'http://spam.example',
        ])->assertStatus(422);

        $this->assertSame(0, ContactSubmission::count());
    }

    public function test_marketing_enquiries_are_flagged(): void
    {
        Mail::fake();

        $this->postJson(route('contact.store'), [
            'name' => 'Growth Lead',
            'email' => 'growth@example.com',
            'build_type' => 'Marketing & Growth',
            'service' => 'Social Media Marketing',
            'business_name' => 'Acme',
            'primary_goal' => 'More leads',
        ])->assertCreated();

        $this->assertTrue(ContactSubmission::first()->is_marketing_enquiry);
    }

    public function test_a_notification_is_sent_to_the_configured_recipient(): void
    {
        Mail::fake();
        app(SettingsRepository::class)->set('contact_recipient_email', 'studio@pcl.test', 'contact');

        $this->postJson(route('contact.store'), [
            'name' => 'Jane',
            'email' => 'jane@example.com',
        ])->assertCreated();

        Mail::assertSent(ContactSubmissionReceived::class, fn ($mail) => $mail->hasTo('studio@pcl.test'));
    }

    public function test_the_enquiry_survives_a_mail_failure(): void
    {
        app(SettingsRepository::class)->set('contact_recipient_email', 'studio@pcl.test', 'contact');
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP down'));

        $this->postJson(route('contact.store'), [
            'name' => 'Jane',
            'email' => 'jane@example.com',
        ])->assertCreated();

        $this->assertSame(1, ContactSubmission::count());
    }

    public function test_the_form_can_be_disabled_from_settings(): void
    {
        app(SettingsRepository::class)->set('contact_form_enabled', false, 'features', 'bool');

        $this->postJson(route('contact.store'), [
            'name' => 'Jane',
            'email' => 'jane@example.com',
        ])->assertStatus(503);

        $this->assertSame(0, ContactSubmission::count());
    }

    public function test_submissions_are_rate_limited(): void
    {
        Mail::fake();

        for ($i = 0; $i < 5; $i++) {
            $this->postJson(route('contact.store'), [
                'name' => 'Jane '.$i,
                'email' => "jane{$i}@example.com",
            ])->assertCreated();
        }

        $this->postJson(route('contact.store'), [
            'name' => 'Jane 6',
            'email' => 'jane6@example.com',
        ])->assertStatus(429);
    }

    public function test_the_admin_inbox_shows_and_manages_enquiries(): void
    {
        Mail::fake();
        $admin = User::factory()->superAdmin()->create();

        $this->postJson(route('contact.store'), ['name' => 'Jane', 'email' => 'jane@example.com'])->assertCreated();
        $enquiry = ContactSubmission::first();

        $this->actingAs($admin)->get(route('admin.enquiries.index'))->assertOk()->assertSee('Jane');

        $this->actingAs($admin)->get(route('admin.enquiries.show', $enquiry))->assertOk();
        $this->assertNotNull($enquiry->fresh()->read_at);

        $this->actingAs($admin)->put(route('admin.enquiries.update', $enquiry), [
            'status' => ContactSubmission::STATUS_REPLIED,
            'admin_notes' => 'Called them back.',
        ])->assertRedirect();

        $this->assertNotNull($enquiry->fresh()->replied_at);
        $this->assertSame('Called them back.', $enquiry->fresh()->admin_notes);

        $this->actingAs($admin)->delete(route('admin.enquiries.destroy', $enquiry))->assertRedirect();
        $this->assertSame(0, ContactSubmission::count());
    }

    public function test_contact_options_come_from_the_cms(): void
    {
        ContactOption::where('type', 'build')->update(['is_enabled' => false]);
        ContactOption::create([
            'type' => 'build', 'label' => 'Only Option', 'value' => 'Only Option',
            'group' => 'build', 'sort_order' => 1, 'is_enabled' => true,
        ]);

        \App\Services\SiteContentService::flush();
        $options = app(\App\Services\SiteContentService::class)->payload()['contactOptions']['build'];

        $this->assertCount(1, $options);
        $this->assertSame('Only Option', $options[0]['label']);
    }
}
