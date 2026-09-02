@php
    $code = $code
        ?? (isset($exception) && method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500);
@endphp
@extends('errors.layout', ['code' => $code, 'title' => $title ?? 'Unexpected issue', 'accent' => '#FF5F1F', 'accent2' => '#8B45FF', 'motif' => 'broken'])
@section('eyebrow', 'Error ' . $code)
@section('headline')The studio hit an <em>unexpected issue</em>.@endsection
@section('body')Not your fault. It has been logged and we will take a look — trying again in a moment often works.@endsection
