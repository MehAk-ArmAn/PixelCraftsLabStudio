@extends('errors.layout', ['code' => 400, 'title' => 'Bad request', 'accent' => '#5B2394'])
@section('eyebrow', 'Error 400 · Bad request')
@section('headline')That request did not <em>land cleanly</em>.@endsection
@section('body')Something in the address or the data attached to it could not be read. Try again from a link on the site rather than a typed or pasted address.@endsection
