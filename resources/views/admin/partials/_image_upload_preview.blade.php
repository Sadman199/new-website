@php
    $inputId = $inputId ?? 'feature_image';
    $previewId = $previewId ?? 'image_preview';
    $label = $label ?? 'Feature Image';
    $currentUrl = $currentUrl ?? null;
    $required = $required ?? false;
@endphp

<div class="form-group">
    <label for="{{ $inputId }}" class="font-weight-bold">{{ $label }} @if($required)<span class="text-danger">*</span>@endif</label>
    <input type="file" name="{{ $inputId }}" id="{{ $inputId }}" class="form-control-file js-image-upload-preview" accept="image/*" data-preview-target="{{ $previewId }}" @if($required) required @endif>
    <small class="text-muted d-block mt-1">JPG, PNG, WEBP, AVIF — max 2MB. Leave empty on edit to keep the current image.</small>
    <div class="mt-3">
        <label class="d-block text-muted small mb-1">Preview</label>
        <img id="{{ $previewId }}"
             src="{{ $currentUrl ?: '#' }}"
             alt="Image preview"
             class="img-thumbnail"
             style="max-width: 220px; max-height: 160px; object-fit: contain; {{ $currentUrl ? '' : 'display:none;' }}">
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
