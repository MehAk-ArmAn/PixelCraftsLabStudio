@extends('errors.layout', ['code' => 502, 'title' => 'Bad gateway', 'accent' => '#FF5F1F', 'accent2' => '#8B45FF', 'motif' => 'broken'])
@section('eyebrow', 'Error 502 · Bad gateway')
@section('headline')The upstream connection did <em>not respond</em>.@endsection
@section('body')A service the site depends on returned something unusable. It is usually brief — give it a moment and reload.@endsection
