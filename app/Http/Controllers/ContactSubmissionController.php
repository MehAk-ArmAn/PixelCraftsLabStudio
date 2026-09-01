<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactSubmissionRequest;
use App\Services\ContactSubmissionService;
use App\Services\SettingsRepository;
use Illuminate\Http\JsonResponse;

final class ContactSubmissionController extends Controller
{
    public function __construct(
        private readonly ContactSubmissionService $submissions,
        private readonly SettingsRepository $settings,
    ) {}

    public function store(ContactSubmissionRequest $request): JsonResponse
    {
        if (! $this->settings->bool('contact_form_enabled', true)) {
            return response()->json([
                'ok' => false,
                'message' => $this->settings->string(
                    'contact_disabled_message',
                    'The enquiry form is currently closed. Please email us instead.',
                ),
            ], 503);
        }

        $submission = $this->submissions->record($request->validated(), $request);

        return response()->json([
            'ok' => true,
            'id' => $submission->id,
            'message' => $this->settings->string(
                'contact_success_message',
                'Brief received. We will be in touch shortly.',
            ),
        ], 201);
    }
}
