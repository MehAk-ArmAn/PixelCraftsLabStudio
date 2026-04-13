<section class="testimonials-preview">

    <h2 class="section-title">What Clients Say</h2>

    <div class="testimonials-grid">
        @forelse($testimonials as $testimonial)
            <div class="testimonial">
                <p>{{ $testimonial->quote }}</p>
                <span>
                    — {{ $testimonial->name }}
                    @if($testimonial->designation)
                        , {{ $testimonial->designation }}
                    @endif
                </span>
            </div>
        @empty
            <div class="testimonial">
                <p>Be the first to leave a review :)</p>
                <span>— Your Name</span>
            </div>
        @endforelse
    </div>

</section>
