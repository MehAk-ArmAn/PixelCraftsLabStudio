@include('errors.layout', [
    'status' => 504,
    'title' => 'The upstream request timed out.',
    'message' => 'A service took too long to respond. Please wait a moment and try again.',
])
