@php
    // Laravel hands the family fallback the exception, not a $code — take the
    // real status from it so the page never misreports what happened.
    $code = $code
        ?? (isset($exception) && method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 400);
@endphp
@extends('errors.layout', ['code' => $code, 'title' => $title ?? 'Request not completed', 'accent' => '#5B2394'])
@section('eyebrow', 'Error ' . $code)
@section('headline')That request could not be <em>completed</em>.@endsection
@section('body')Something about it was not right, so we stopped before going further. Heading back to a known page is usually the quickest fix.
@include('errors.partials.home-action')

@endsection
