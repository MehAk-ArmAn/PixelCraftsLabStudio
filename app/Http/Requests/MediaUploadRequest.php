<?php

namespace App\Http\Requests;

use App\Services\MediaLibraryService;
use Illuminate\Foundation\Http\FormRequest;

class MediaUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canManageContent() ?? false;
    }

    public function rules(): array
    {
        $mimes = implode(',', array_merge(
            MediaLibraryService::IMAGE_MIMES,
            MediaLibraryService::VIDEO_MIMES,
        ));

        return [
            'files' => ['required', 'array', 'max:20'],
            'files.*' => ['file', 'mimes:'.$mimes, 'max:'.MediaLibraryService::MAX_KILOBYTES],
            'folder' => ['nullable', 'string', 'max:64', 'regex:/^[A-Za-z0-9\-_\/]+$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'files.*.mimes' => 'Only images (jpg, png, gif, webp, svg, avif) and video (mp4, webm, ogg) can be uploaded.',
            'files.*.max' => 'Each file must be 20 MB or smaller.',
        ];
    }
}
