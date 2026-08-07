@php
    $fieldId = $id ?? 'color_' . uniqid();
    $value = old($name, $value ?? '');
    $normalized = \App\Support\SiteTheme::normalizeHex($value, $default ?? '#007AAD');
@endphp

<div class="form-group">
    <label class="font-weight-bold d-block" for="{{ $fieldId }}">{{ $label }}</label>
    @if(!empty($help))
        <p class="text-muted small mb-2">{{ $help }}</p>
    @endif
    <div class="d-flex align-items-center flex-wrap" style="gap:0.75rem;">
        <input type="color"
               id="{{ $fieldId }}_picker"
               value="{{ $normalized }}"
               class="bc-color-picker"
               aria-label="{{ $label }} picker">
        <input type="text"
               name="{{ $name }}"
               id="{{ $fieldId }}"
               class="form-control jscolor bc-color-input"
               value="{{ $normalized }}"
               maxlength="7"
               placeholder="{{ $default ?? '#007AAD' }}"
               style="max-width:140px;">
        <span class="bc-color-preview badge badge-light border px-3 py-2" data-preview-for="{{ $fieldId }}" style="background:{{ $normalized }};color:#fff;min-width:110px;">
            Preview
        </span>
    </div>
</div>
