@php
    $faq = $faq ?? $faq_data ?? new \App\Models\Faq();
    $isEdit = $faq->exists;
@endphp

<section class="ab-section">
    <div class="ab-section__head">
        <h2>Question</h2>
        <p>The title visitors see, plus the answer and the broker it belongs to.</p>
    </div>
    <div class="ab-section__body">
        <div class="form-group">
            <label for="faq_title">FAQ title <span class="text-danger">*</span></label>
            <input type="text" name="faq_title" id="faq_title" class="form-control @error('faq_title') is-invalid @enderror" required
                   value="{{ old('faq_title', $faq->faq_title) }}" placeholder="e.g. What is the minimum deposit?">
            @error('faq_title')<small class="ab-error">{{ $message }}</small>@enderror
        </div>
        <div class="form-group">
            <label for="faq_detail">Answer <span class="text-danger">*</span></label>
            <textarea name="faq_detail" id="faq_detail" class="form-control snote @error('faq_detail') is-invalid @enderror" rows="8">{{ old('faq_detail', $faq->faq_detail) }}</textarea>
            @error('faq_detail')<small class="ab-error">{{ $message }}</small>@enderror
        </div>
        <div class="form-group mb-0">
            <label for="broker_id">Broker <span class="text-danger">*</span></label>
            <select name="broker_id" id="broker_id" class="form-control" required>
                <option value="">Choose a broker</option>
                @foreach($brokers as $broker)
                    <option value="{{ $broker->id }}" @selected((string) old('broker_id', $faq->broker_id) === (string) $broker->id)>{{ $broker->name }}</option>
                @endforeach
            </select>
            @error('broker_id')<small class="ab-error">{{ $message }}</small>@enderror
        </div>
        @include('admin.partials.language_id_field', ['language_id' => $faq->language_id])
    </div>
</section>
