<?php

namespace App\Http\Requests;

use App\Models\ContactOption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'message' => ['nullable', 'string', 'max:5000'],
            'build_type' => ['nullable', 'string', 'max:120', $this->optionRule('build')],
            'scope' => ['nullable', 'string', 'max:120', $this->optionRule('scope')],
            'timeline' => ['nullable', 'string', 'max:120', $this->optionRule('timeline')],
            'service' => ['nullable', 'string', 'max:120', $this->optionRule('service')],
            'budget' => ['nullable', 'string', 'max:120', $this->optionRule('budget')],
            'business_name' => ['nullable', 'string', 'max:190'],
            'website_url' => ['nullable', 'string', 'max:255'],
            'social_platforms' => ['nullable', 'string', 'max:255'],
            'primary_goal' => ['nullable', 'string', 'max:255'],
            'target_audience' => ['nullable', 'string', 'max:255'],
            'current_marketing' => ['nullable', 'string', 'max:2000'],
            'preferred_channels' => ['nullable', 'string', 'max:255'],
            // Honeypot: must stay empty. Hidden off-screen in the public form.
            'company_website' => ['nullable', 'size:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'company_website.size' => 'This submission was rejected.',
        ];
    }

    /** Only accept values the CMS actually offers — or anything, if none are configured. */
    private function optionRule(string $type): mixed
    {
        $values = ContactOption::query()
            ->where('type', $type)
            ->where('is_enabled', true)
            ->pluck('value')
            ->all();

        return $values === [] ? 'string' : Rule::in($values);
    }
}
