<?php

namespace App\Services;

use App\Mail\ContactSubmissionReceived;
use App\Models\ContactOption;
use App\Models\ContactSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ContactSubmissionService
{
    public function __construct(private readonly SettingsRepository $settings) {}

    /**
     * The enquiry is persisted first and always. A mail failure is logged, not
     * surfaced — losing the lead would be worse than a missed notification.
     */
    public function record(array $data, Request $request): ContactSubmission
    {
        $submission = ContactSubmission::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'build_type' => $data['build_type'] ?? null,
            'scope' => $data['scope'] ?? null,
            'timeline' => $data['timeline'] ?? null,
            'service' => $data['service'] ?? null,
            'budget' => $data['budget'] ?? null,
            'message' => $data['message'] ?? null,
            'business_name' => $data['business_name'] ?? null,
            'website_url' => $data['website_url'] ?? null,
            'social_platforms' => $data['social_platforms'] ?? null,
            'primary_goal' => $data['primary_goal'] ?? null,
            'target_audience' => $data['target_audience'] ?? null,
            'current_marketing' => $data['current_marketing'] ?? null,
            'preferred_channels' => $data['preferred_channels'] ?? null,
            'is_marketing_enquiry' => $this->looksLikeMarketing($data),
            'status' => ContactSubmission::STATUS_NEW,
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 250, ''),
        ]);

        $this->notify($submission);

        return $submission;
    }

    private function notify(ContactSubmission $submission): void
    {
        if (! $this->settings->bool('contact_notifications_enabled', true)) {
            return;
        }

        $recipient = $this->settings->string('contact_recipient_email', '')
            ?: $this->settings->string('studio_email', '');

        if ($recipient === '' || ! filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            Log::warning('PCL contact notification skipped: no valid recipient configured.', [
                'submission_id' => $submission->id,
            ]);

            return;
        }

        try {
            Mail::to($recipient)->send(new ContactSubmissionReceived(
                $submission,
                $this->settings->string('contact_subject_prefix', '[PixelCraftsLab]'),
            ));
        } catch (\Throwable $e) {
            Log::error('PCL contact notification failed to send.', [
                'submission_id' => $submission->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /** Marketing enquiries unlock the extra growth questions in the admin view. */
    private function looksLikeMarketing(array $data): bool
    {
        $marketingValues = ContactOption::query()
            ->whereIn('type', ['build', 'service'])
            ->where('group', 'growth')
            ->pluck('value')
            ->map(fn ($v) => Str::lower($v))
            ->all();

        foreach (['build_type', 'service'] as $field) {
            $value = Str::lower((string) ($data[$field] ?? ''));

            if ($value !== '' && in_array($value, $marketingValues, true)) {
                return true;
            }
        }

        return collect(['business_name', 'social_platforms', 'primary_goal', 'preferred_channels', 'current_marketing'])
            ->contains(fn ($f) => filled($data[$f] ?? null));
    }
}
