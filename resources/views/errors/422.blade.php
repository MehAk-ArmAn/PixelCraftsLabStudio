@extends('errors.layout', ['code' => 422, 'title' => 'Could not be processed', 'accent' => '#8B45FF', 'accent2' => '#B94FC0'])
@section('eyebrow', 'Error 422 · Unprocessable')
@section('headline')We could not <em>process</em> that request.@endsection
@section('body')It arrived intact, but something in it did not pass our checks. Go back, review what you entered, and send it again.
@include('errors.partials.home-action')

@endsection
