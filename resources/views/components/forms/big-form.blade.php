{{-- 
    BIG MAIN FORM
    This form is centered and designed as the main focus of the landing page.
    All text is placeholder ("...") and can be replaced later.
--}}

<section class="form-section">

    <div class="particles" id="particles"></div>
    
    <div class="form-container">

        <h1>...</h1>

        {{-- Main Project Form --}}
        <form action="{{ route('contact.store') }}" method="POST">

            {{-- CSRF token for security (required in Laravel forms) --}}
            @csrf

            {{-- Name Field --}}
            <input type="text" name="name" placeholder="..." required>

            {{-- Email Field --}}
            <input type="email" name="email" placeholder="..." required>

            {{-- Select Service --}}
            <select name="service">
                <option value="">...</option>
                <option value="option1">...</option>
                <option value="option2">...</option>
            </select>

            {{-- Description Field --}}
            <textarea name="message" placeholder="..."></textarea>

            {{-- Submit Button --}}
            <button type="submit">...</button>

        </form>

    </div>

</section>
