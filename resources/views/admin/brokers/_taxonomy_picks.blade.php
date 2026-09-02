@php
    $inputName = $inputName ?? 'broker_categories[]';
    $descName = $descName ?? 'category_descriptions';
    $idPrefix = $idPrefix ?? 'broker_category';
    $type = $type ?? 'category';
    $selected = $selected ?? [];
    $options = $options ?? [];
    $stored = array_merge(
        $formOptions['taxonomyDescriptions'][$type] ?? [],
        (array) old($descName, [])
    );
@endphp

<div class="ab-tax-block" data-ab-tax-block>
    <div class="row">
        @foreach($options as $value => $label)
            @php
                $isChecked = in_array($value, $selected, true);
                $fieldId = $idPrefix.'_'.$value;
            @endphp
            <div class="col-md-6 col-lg-4">
                <div class="custom-control custom-checkbox mb-2">
                    <input type="checkbox"
                           class="custom-control-input"
                           name="{{ $inputName }}"
                           id="{{ $fieldId }}"
                           value="{{ $value }}"
                           data-ab-tax-toggle
                           data-ab-tax-target="{{ $fieldId }}_description"
                           @checked($isChecked)>
                    <label class="custom-control-label" for="{{ $fieldId }}">{{ $label }}</label>
                </div>
            </div>
        @endforeach
    </div>

    @foreach($options as $value => $label)
        @php
            $isChecked = in_array($value, $selected, true);
            $descId = $idPrefix.'_'.$value.'_description';
            $currentDescription = is_array($stored) ? ($stored[$value] ?? '') : '';
        @endphp
        <div class="ab-tax-editor" data-ab-tax-desc @if(! $isChecked) hidden @endif>
            <label class="ab-tax-editor__label" for="{{ $descId }}">{{ $label }} — listing description</label>
            <textarea name="{{ $descName }}[{{ $value }}]"
                      id="{{ $descId }}"
                      class="form-control @error($descName.'.'.$value) is-invalid @enderror"
                      rows="8"
                      data-ab-snote
                      data-admin-editor="full"
                      data-admin-editor-lazy
                      placeholder="Shown on the public {{ $label }} listing."
                      @disabled(! $isChecked)>{{ $currentDescription }}</textarea>
            @error($descName.'.'.$value)<small class="text-danger d-block">{{ $message }}</small>@enderror
        </div>
    @endforeach
</div>
