@php
    $inputId = $inputId ?? 'feature_image';
    $previewId = $previewId ?? 'image_preview';
    $label = $label ?? 'Feature Image';
    $currentUrl = $currentUrl ?? null;
    $required = $required ?? false;
@endphp

<div class="tw-flex tw-flex-col tw-gap-2">
    <label for="{{ $inputId }}" class="tw-text-sm tw-font-semibold tw-text-slate-800">
        {{ $label }}
        @if($required)<span class="tw-text-red-600">*</span>@endif
    </label>

    <input
        type="file"
        name="{{ $inputId }}"
        id="{{ $inputId }}"
        class="tw-block tw-w-full tw-text-sm tw-text-slate-700
               file:tw-mr-4 file:tw-px-3 file:tw-py-2
               file:tw-rounded-lg file:tw-border-0
               file:tw-text-sm file:tw-font-semibold
               file:tw-bg-brand file:tw-text-white
               hover:file:tw-bg-brand/90
               js-image-upload-preview"
        accept="image/*"
        data-preview-target="{{ $previewId }}"
        @if($required) required @endif />

    <p class="tw-text-xs tw-text-slate-500">
        JPG, PNG, WEBP, AVIF — max 2MB.
        Leave empty on edit to keep the current image.
    </p>

    <div class="tw-pt-1">
        <p class="tw-text-xs tw-font-semibold tw-text-slate-500 tw-mb-1">Preview</p>
        <img id="{{ $previewId }}"
             src="{{ $currentUrl ?: '#' }}"
             alt="Image preview"
             class="tw-w-full tw-max-w-[220px] tw-h-[160px] tw-object-contain tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white
                    {{ $currentUrl ? '' : 'tw-hidden' }}">
    </div>
</div>

@once
<script>
(function () {
    if (window.__imageUploadPreviewBound) {
        return;
    }
    window.__imageUploadPreviewBound = true;

    document.addEventListener('change', function (event) {
        var input = event.target;
        if (!input.classList || !input.classList.contains('js-image-upload-preview')) {
            return;
        }

        var previewId = input.getAttribute('data-preview-target');
        var preview = previewId ? document.getElementById(previewId) : null;
        if (!preview) {
            return;
        }

        var file = input.files && input.files[0];
        if (!file) {
            return;
        }

        var reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    });
})();
</script>
@endonce
