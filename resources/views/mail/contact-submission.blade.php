<x-mail::message>
# New project enquiry

**Name:** {{ $submission->name }}
**Email:** {{ $submission->email }}
@if ($submission->build_type)
**Building:** {{ $submission->build_type }}
@endif
@if ($submission->service)
**Service:** {{ $submission->service }}
@endif
@if ($submission->scope)
**Scope:** {{ $submission->scope }}
@endif
@if ($submission->timeline)
**Timing:** {{ $submission->timeline }}
@endif
@if ($submission->budget)
**Budget:** {{ $submission->budget }}
@endif

@if ($submission->message)
## Brief

{{ $submission->message }}
@endif

@if ($submission->is_marketing_enquiry)
## Marketing detail

@if ($submission->business_name)
**Business:** {{ $submission->business_name }}
@endif
@if ($submission->website_url)
**Website:** {{ $submission->website_url }}
@endif
@if ($submission->social_platforms)
**Social platforms:** {{ $submission->social_platforms }}
@endif
@if ($submission->primary_goal)
**Primary goal:** {{ $submission->primary_goal }}
@endif
@if ($submission->target_audience)
**Audience:** {{ $submission->target_audience }}
@endif
@if ($submission->preferred_channels)
**Preferred channels:** {{ $submission->preferred_channels }}
@endif
@if ($submission->current_marketing)
**Current activity:** {{ $submission->current_marketing }}
@endif
@endif

<x-mail::button :url="$url">
Open in admin
</x-mail::button>

Received {{ $submission->created_at->format('j M Y, H:i') }}
</x-mail::message>
