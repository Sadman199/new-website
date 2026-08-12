@php
    use App\Services\EditorialAssignmentService;

    $entity = $model ?? $post ?? $bonus ?? $broker ?? null;
    $roles = EditorialAssignmentService::roleLabels();
    $options = $editorialOptions ?? EditorialAssignmentService::allAssigneeOptions();
@endphp

<div class="tw-bg-white tw-border tw-border-slate-200/70 tw-rounded-2xl tw-overflow-hidden tw-mb-6">
    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-justify-between tw-gap-4">
        <div class="tw-flex tw-items-center tw-gap-3">
            <span class="tw-inline-flex tw-items-center tw-justify-center tw-w-9 tw-h-9 tw-rounded-xl tw-bg-slate-50 tw-border tw-border-slate-200">
                <i class="fas fa-pen-nib tw-text-brand"></i>
            </span>
            <div>
                <h3 class="tw-text-sm tw-font-extrabold tw-text-slate-900">Editorial Credits</h3>
                <p class="tw-text-xs tw-text-slate-600">Assign who wrote, edited, and fact-checked this content.</p>
            </div>
        </div>
    </div>

    <div class="tw-px-6 tw-py-5">
        <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-3 tw-gap-4">
            @foreach($roles as $roleKey => $roleLabel)
                @php
                    $fieldName = $roleKey . '_assignee';
                    $roleOptions = $options[$roleKey] ?? [];
                    $selected = old($fieldName, $entity ? EditorialAssignmentService::assigneeValueFor($entity, $roleKey) : null);
                @endphp
                <div class="tw-flex tw-flex-col tw-gap-2">
                    <label class="tw-text-sm tw-font-semibold tw-text-slate-800">{{ $roleLabel }}</label>
                    <select name="{{ $fieldName }}"
                            class="tw-border tw-border-slate-200 tw-rounded-xl tw-bg-white tw-px-3 tw-py-2 tw-text-sm
                                   focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-brand/20">
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
