@php
    $resolvedLanguageId = old('language_id', $language_id ?? app(\App\Services\GlobalViewDataService::class)->currentLanguageId());
@endphp
<input type="hidden" name="language_id" value="{{ $resolvedLanguageId }}">
