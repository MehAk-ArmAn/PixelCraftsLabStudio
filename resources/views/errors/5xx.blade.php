@php
    $status = isset($exception) && method_exists($exception, 'getStatusCode')
        ? (int) $exception->getStatusCode()
        : 500;
@endphp

@include('errors.layout', [
    'status' => $status,
    'title' => 'The studio hit an unexpected issue.',
    'message' => 'We could not complete the request just now. Please try again shortly.',
])
