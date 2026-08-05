@php
    $roleFields = [
        'can_write' => ['label' => 'Written', 'help' => 'Can be credited as the writer on posts.'],
        'can_edit' => ['label' => 'Edited', 'help' => 'Can be credited as the editor on posts.'],
        'can_fact_check' => ['label' => 'Fact-Checked', 'help' => 'Can be credited as the fact-checker on posts.'],
    ];
@endphp

<div class="card border mb-4">
    <div class="card-header bg-light">
        <strong>Editorial Roles</strong>
    </div>
    <div class="card-body">
        <p class="text-muted small mb-3">Select which editorial credits this author can receive on published content.</p>
        <div class="row">
            @foreach($roleFields as $field => $meta)
                <div class="col-md-4">
                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" class="custom-control-input" id="{{ $field }}"
                               name="{{ $field }}" value="1"
                               @checked(old($field, $author?->{$field} ?? ($field === 'can_write')))>
                        <label class="custom-control-label font-weight-bold" for="{{ $field }}">{{ $meta['label'] }}</label>
                    </div>
                    <small class="text-muted d-block mb-3">{{ $meta['help'] }}</small>
                </div>
            @endforeach
        </div>
    </div>
</div>
