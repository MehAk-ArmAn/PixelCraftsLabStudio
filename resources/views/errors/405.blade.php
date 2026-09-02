@include('errors.layout', [
    'status' => 405,
    'title' => 'That action is not available here.',
    'message' => 'This address does not support the way the request was sent.',
])
