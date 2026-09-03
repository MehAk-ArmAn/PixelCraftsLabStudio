<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactSubmissionRequest;
use App\Services\ContactSubmissionService;
use App\Services\SettingsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

final class ContactSubmissionController extends Controller
{
    public function __construct(
        private readonly ContactSubmissionService $submissions,
        private readonly SettingsRepository $settings,
    ) {}

    public function store(ContactSubmissionRequest $request): JsonResponse|RedirectResponse
    {
        if (! $this->settings->bool('contact_form_enabled', true)) {
            $message = $this->settings->string(
                'contact_disabled_message',
                'The enquiry form is currently closed. Please email us instead.',
            );

            if ($request->expectsJson()) {
                return response()->json(['ok' => false, 'message' => $message], 503);
            }

            return back()->withInput()->withErrors(['contact' => $message]);
        }

        $submission = $this->submissions->record($request->validated(), $request);

        $message = $this->settings->string(
            'contact_success_message',
            'Brief received. We will be in touch shortly.',
        );

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'id' => $submission->id,
                'message' => $message,
            ], 201);
        }

        return to_route('contact')->with('contact_status', $message);
    }
}
