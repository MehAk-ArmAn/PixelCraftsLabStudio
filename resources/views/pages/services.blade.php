@extends('layouts.app')

{{-- Page Title --}}
@section('title', 'Services | PixelCraftsLabStudio')

{{-- Page Specific CSS & JS --}}
@section('styles')
<link rel="stylesheet" href="{{ asset('css/pages/services.css') }}">
<script src="{{ asset('js/services.js') }}" defer></script>
@endsection


@section('content')

    {{-- Navbar --}}
    @include('partials.navbar')

<div class="services-container">

<div class="services-header">
<h1>Our Services</h1>
<p>Explore what PixelCraftsLabStudio can build for you.</p>
</div>


<div class="services-grid">

<!-- CARD -->
<div class="service-card" onclick="openService('brandkits')">
<h2>Brand Kits</h2>
</div>

<div class="service-card" onclick="openService('web')">
<h2>Web Development</h2>
</div>

<div class="service-card" onclick="openService('apps')">
<h2>App Development</h2>
</div>

<div class="service-card" onclick="openService('software')">
<h2>Custom Software</h2>
</div>

<div class="service-card" onclick="openService('support')">
<h2>App Support</h2>
</div>

<div class="service-card" onclick="openService('consult')">
<h2>Digital Consultation</h2>
</div>

<div class="service-card" onclick="openService('api')">
<h2>API Development</h2>
</div>

</div>

</div>

</section>


<!-- MODAL -->

<div id="service-modal" class="service-modal">

<div class="modal-content">

<button class="close-btn" onclick="closeService()">✕</button>

<h2 id="modal-title"></h2>

<p id="modal-text"></p>

</div>

</div>

@endsection