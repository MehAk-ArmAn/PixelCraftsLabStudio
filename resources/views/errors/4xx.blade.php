@php
    $status = isset($exception) && method_exists($exception, 'getStatusCode')
        ? (int) $exception->getStatusCode()
        : 400;
@endphp

@include('errors.layout', [
    'status' => $status,
    'title' => 'That request could not be completed.',
    'message' => 'The page or action is not available in the way it was requested.',
])
