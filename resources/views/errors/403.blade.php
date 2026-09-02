@include('errors.layout', [
    'status' => 403,
    'title' => 'That area is not available to you.',
    'message' => 'You do not have permission to access this part of the studio.',
])
