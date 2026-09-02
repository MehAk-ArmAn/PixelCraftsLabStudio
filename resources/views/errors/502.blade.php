@include('errors.layout', [
    'status' => 502,
    'title' => 'The upstream connection did not respond.',
    'message' => 'A service needed for this request returned an invalid response. Please try again shortly.',
])
