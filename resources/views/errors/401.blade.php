@include('errors.layout', [
    'status' => 401,
    'title' => 'Sign in required.',
    'message' => 'This area needs an authenticated account before you can continue.',
])
