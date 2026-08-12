@php $d = $data ?? []; @endphp
<section class="cms-section cms-contact">
    <div class="cms-wrap cms-wrap--narrow">
        @if(!empty($d['heading']))
            <h2 class="cms-section__title">{{ $d['heading'] }}</h2>
        @endif
        @if(!empty($d['subheading']))
            <p class="cms-section__lead">{{ $d['subheading'] }}</p>
        @endif

        @if(session('success'))
            <div class="cms-alert cms-alert--success" role="status">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="cms-alert cms-alert--error" role="alert">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('contact_form_submit') }}" method="POST" class="cms-contact__form">
            @csrf
            <div class="cms-contact__grid">
                <label>
                    <span>Name</span>
                    <input type="text" name="name" value="{{ old('name') }}" required>
                </label>
                <label>
                    <span>Email</span>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </label>
            </div>
            <label>
                <span>Subject</span>
                <input type="text" name="subject" value="{{ old('subject') }}" required>
            </label>
            <label>
                <span>Message</span>
                <textarea name="message" rows="6" required>{{ old('message') }}</textarea>
            </label>
            <label class="cms-contact__terms">
                <input type="checkbox" name="terms" value="1" required @checked(old('terms'))>
                <span>I agree to the terms and privacy policy.</span>
            </label>
            <button type="submit" class="cms-btn cms-btn--primary">Send message</button>
        </form>
    </div>
</section>
