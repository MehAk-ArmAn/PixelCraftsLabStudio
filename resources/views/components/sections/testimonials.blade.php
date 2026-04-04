@props(['testimonials'])
@if(session('success'))
    <p style="color: lightgreen;">{{ session('success') }}</p>
@endif

    @forelse($testimonials as $t)
        <div class="testimonial">
            <p>{{ $t->message }}</p>
            <span>— {{ $t->name }}</span>
        </div>
    @empty
        <div class="testimonial">
            <p>Be the first to leave a review :)</p>
            <span>— Your Name</span>
        </div>
    @endforelse

</div>
