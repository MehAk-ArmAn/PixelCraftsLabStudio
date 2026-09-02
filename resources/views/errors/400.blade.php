@include('errors.layout', [
    'status' => 400,
    'title' => 'That request did not land cleanly.',
    'message' => 'Something in the request was incomplete or malformed. Check the details and try again.',
])
