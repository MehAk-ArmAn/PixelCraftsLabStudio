@include('errors.layout', [
    'status' => 500,
    'title' => 'Something went off-grid.',
    'message' => 'The studio hit an unexpected issue. Please try again shortly.',
])
