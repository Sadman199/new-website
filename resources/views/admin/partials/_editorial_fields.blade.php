@php
    use App\Services\EditorialAssignmentService;

    $entity = $model ?? $post ?? $bonus ?? $broker ?? null;
    $roles = EditorialAssignmentService::roleLabels();
    $options = $editorialOptions ?? EditorialAssignmentService::allAssigneeOptions();
@endphp

<div class="card border mb-4">
    <div class="card-header bg-light">
        <strong>Editorial Credits</strong>
    </div>
    <div class="card-body">
        <p class="text-muted small mb-3">Assign who wrote, edited, and fact-checked this content.</p>
        <div class="row">
            @foreach($roles as $roleKey => $roleLabel)
                @php
                    $fieldName = $roleKey . '_assignee';
                    $roleOptions = $options[$roleKey] ?? [];
                    $selected = old($fieldName, $entity ? EditorialAssignmentService::assigneeValueFor($entity, $roleKey) : null);
                @endphp
                <div class="col-md-4 form-group">
                    <label class="font-weight-bold">{{ $roleLabel }}</label>
                    <select name="{{ $fieldName }}" class="form-control">
                        <option value="">— Not assigned —</option>
                        @php $currentGroup = null; @endphp
                        @foreach($roleOptions as $option)
                            @if($currentGroup !== $option['group'])
                                @if($currentGroup !== null)</optgroup>@endif
                                <optgroup label="{{ $option['group'] }}">
                                @php $currentGroup = $option['group']; @endphp
                            @endif
                            <option value="{{ $option['value'] }}" @selected($selected === $option['value'])>{{ $option['label'] }}</option>
                        @endforeach
                        @if($currentGroup !== null)</optgroup>@endif
                    </select>
                </div>
            @endforeach
        </div>
    </div>
</div>
