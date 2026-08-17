@php
    $review = $review ?? null;
    $idPrefix = $idPrefix ?? 'new';

    $initialScores = [];
    foreach ($dimensionFields as $field => $label) {
        $initialScores[$field] = (int) old($field, $review?->{$field});
    }
    $hasAllScores = count(array_filter($initialScores)) === count($dimensionFields);
    $initialScore10 = $hasAllScores
        ? round(array_sum($initialScores) / count($initialScores)) * 2
        : null;
@endphp

<div class="br-form-score" data-br-score-preview>
    <div class="br-form-score__value-row">
        <span class="br-form-score__value" data-br-score-value>{{ $initialScore10 ?? '—' }}</span>
        <span class="br-form-score__max">/10</span>
    </div>
    <p class="br-form-score__hint">Your score is calculated from the three ratings below.</p>
</div>

<div class="br-form-grid">
    <div class="br-comment-form__field">
        <label for="{{ $idPrefix }}_length_of_use">Length of use</label>
        <select name="length_of_use" id="{{ $idPrefix }}_length_of_use" required>
            <option value="">Select length of use</option>
            @foreach($lengthOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('length_of_use', $review?->length_of_use) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('length_of_use')
            <span class="br-comment-form__error">{{ $message }}</span>
        @enderror
    </div>

    <div class="br-comment-form__field">
        <label for="{{ $idPrefix }}_account_type">Account type</label>
        <select name="account_type" id="{{ $idPrefix }}_account_type" required>
            <option value="">Select account type</option>
            @foreach($reviewAccountTypes as $type)
                <option value="{{ $type }}" @selected(old('account_type', $review?->account_type) === $type)>{{ $type }}</option>
            @endforeach
        </select>
        @error('account_type')
            <span class="br-comment-form__error">{{ $message }}</span>
        @enderror
    </div>

    <div class="br-comment-form__field">
        <label for="{{ $idPrefix }}_country">Country <span class="br-form-optional">optional</span></label>
        <input
            type="text"
            name="country"
            id="{{ $idPrefix }}_country"
            value="{{ old('country', $review?->country ?? auth('web')->user()?->country) }}"
            placeholder="Your country"
        >
        @error('country')
            <span class="br-comment-form__error">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="br-dimension-ratings">
    @foreach($dimensionFields as $field => $label)
        @php $selected = $initialScores[$field]; @endphp
        <div class="br-dimension-rating">
            <span class="br-dimension-rating__label">{{ $label }}</span>
            <div class="br-rating-input">
                <div class="br-rating-input__stars" data-br-star-group="{{ $field }}">
                    @for($i = 1; $i <= 5; $i++)
                        <input
                            type="radio"
                            id="{{ $idPrefix }}_{{ $field }}_{{ $i }}"
                            name="{{ $field }}"
                            value="{{ $i }}"
                            {{ $selected === $i ? 'checked' : '' }}
                            class="br-rating-input__radio"
                            required
                        >
                        <label for="{{ $idPrefix }}_{{ $field }}_{{ $i }}" data-br-require-auth>{{ $i }}</label>
                    @endfor
                </div>
                <span class="br-rating-input__text" data-br-star-text>{{ $selected ? $selected.'/5' : 'Not rated' }}</span>
            </div>
            @error($field)
                <span class="br-comment-form__error">{{ $message }}</span>
            @enderror
        </div>
    @endforeach
</div>

<div class="br-comment-form__field">
    <label for="{{ $idPrefix }}_description">Your review</label>
    <textarea
        name="description"
        id="{{ $idPrefix }}_description"
        rows="5"
        required
        minlength="20"
        maxlength="5000"
        placeholder="Describe spreads, execution, withdrawals, platforms, and support…"
    >{{ old('description', $review?->description) }}</textarea>
    @error('description')
        <span class="br-comment-form__error">{{ $message }}</span>
    @enderror
</div>
