@extends('admin.layout.app')
@include('admin.partials._ab_assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Edit Trading Tool')

@php
    $faqRows = old('faqs', $tool->normalizedFaqs() ?: [['question' => '', 'answer' => '']]);
    $selectedToolIds = array_map('intval', old('related_tool_ids', $tool->relatedToolIdList()));
    $selectedBrokerIds = array_map('intval', old('related_broker_ids', $tool->relatedBrokerIdList()));
@endphp

@section('main_content')
<div class="ab-page ab-page--hub">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Trading Tools</p>
                <h1 class="ab-header__title">{{ $tool->name }}</h1>
                <p class="ab-header__sub">Internal key <code>{{ $tool->slug }}</code> stays the same so public URLs do not change.</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_trading_tools_index') }}" class="ab-btn ab-btn--ghost">All tools</a>
            </div>
        </header>

        @if($errors->any())
            <div class="ab-banner" role="alert">
                <h3>Please fix {{ $errors->count() }} {{ \Illuminate\Support\Str::plural('issue', $errors->count()) }} before saving</h3>
                <ol>
                    @foreach($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ol>
            </div>
        @endif

        <form action="{{ route('admin_trading_tools_update', $tool->id) }}" method="POST">
            @csrf

            <nav class="ab-section-nav" aria-label="Form sections">
                <a href="#details" data-ab-nav class="is-active">Details</a>
                <a href="#seo" data-ab-nav>SEO</a>
                <a href="#copy" data-ab-nav>Content</a>
                <a href="#related" data-ab-nav>Related</a>
                <a href="#faq" data-ab-nav>FAQ</a>
            </nav>

            <section class="ab-section" id="details">
                <div class="ab-section__head">
                    <h2>Basic information</h2>
                    <p>Name, icon, category, and visibility on the public tools hub.</p>
                </div>
                <div class="ab-section__body">
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $tool->name) }}" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="icon">Icon class</label>
                            <input type="text" name="icon" id="icon" class="form-control" value="{{ old('icon', $tool->icon) }}" placeholder="fas fa-calculator">
                        </div>
                        <div class="col-md-8 form-group">
                            <label for="short_description">Short description</label>
                            <input type="text" name="short_description" id="short_description" class="form-control" value="{{ old('short_description', $tool->short_description) }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="sort_order">Sort order</label>
                            <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', $tool->sort_order) }}" min="0">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="category">Category <span class="text-danger">*</span></label>
                            <select name="category" id="category" class="form-control" required>
                                @foreach($categories as $key => $category)
                                    <option value="{{ $key }}" @selected(old('category', $tool->categoryKey()) === $key)>{{ $category['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="description">Description / rich text</label>
                            <textarea name="description" id="description" class="form-control snote" rows="6">{{ old('description', $tool->description) }}</textarea>
                        </div>
                        <div class="col-md-12 form-group mb-0">
                            <label class="ab-check">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $tool->is_active))>
                                Visible on the public dashboard
                            </label>
                        </div>
                    </div>
                </div>
            </section>

            <section class="ab-section" id="seo">
                <div class="ab-section__head">
                    <h2>SEO</h2>
                    <p>Leave blank to use the tool name and the built-in fallback description.</p>
                </div>
                <div class="ab-section__body">
                    <div class="form-group">
                        <label for="seo_title">SEO title</label>
                        <input type="text" name="seo_title" id="seo_title" class="form-control" maxlength="255" value="{{ old('seo_title', $tool->seo_title) }}">
                    </div>
                    <div class="form-group">
                        <label for="meta_description">Meta description</label>
                        <textarea name="meta_description" id="meta_description" rows="3" class="form-control" maxlength="500">{{ old('meta_description', $tool->meta_description) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="canonical_url">Canonical URL</label>
                        <input type="text" name="canonical_url" id="canonical_url" class="form-control" maxlength="500" value="{{ old('canonical_url', $tool->canonical_url) }}" placeholder="Leave blank for the default public URL">
                    </div>
                    <div class="form-group">
                        <label for="og_title">Open Graph title</label>
                        <input type="text" name="og_title" id="og_title" class="form-control" maxlength="255" value="{{ old('og_title', $tool->og_title) }}">
                    </div>
                    <div class="form-group mb-0">
                        <label for="og_description">Open Graph description</label>
                        <textarea name="og_description" id="og_description" rows="3" class="form-control" maxlength="500">{{ old('og_description', $tool->og_description) }}</textarea>
                    </div>
                </div>
            </section>

            <section class="ab-section" id="copy">
                <div class="ab-section__head">
                    <h2>Page content</h2>
                    <p>Shown under the calculator. Empty fields fall back to the built-in help copy.</p>
                </div>
                <div class="ab-section__body">
                    <div class="form-group">
                        <label for="introduction">Introduction</label>
                        <textarea name="introduction" id="introduction" rows="4" class="form-control">{{ old('introduction', $tool->introduction) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="how_to_use">How to use</label>
                        <textarea name="how_to_use" id="how_to_use" rows="4" class="form-control">{{ old('how_to_use', $tool->how_to_use) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="formula">Formula</label>
                        <textarea name="formula" id="formula" rows="3" class="form-control">{{ old('formula', $tool->formula) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="example">Example</label>
                        <textarea name="example" id="example" rows="3" class="form-control">{{ old('example', $tool->example) }}</textarea>
                    </div>
                    <div class="form-group mb-0">
                        <label for="additional_explanation">Additional explanation</label>
                        <textarea name="additional_explanation" id="additional_explanation" rows="4" class="form-control">{{ old('additional_explanation', $tool->additional_explanation) }}</textarea>
                    </div>
                </div>
            </section>

            <section class="ab-section" id="related">
                <div class="ab-section__head">
                    <h2>Related tools and brokers</h2>
                    <p>If none are selected, related calculators use the default next-tool list. Related brokers fall back to top-rated brokers with published spreads when the tool is cost-related.</p>
                </div>
                <div class="ab-section__body">
                    <div class="form-group">
                        <label>Related tools</label>
                        <div class="ab-pick">
                            @forelse($otherTools as $item)
                                <label class="ab-pick__item" data-name="{{ strtolower($item->name.' '.$item->slug) }}">
                                    <input type="checkbox" name="related_tool_ids[]" value="{{ $item->id }}" @checked(in_array((int) $item->id, $selectedToolIds, true))>
                                    <span>{{ $item->name }} <small>{{ $item->slug }}</small></span>
                                </label>
                            @empty
                                <p class="ab-pick__empty">No other tools are available.</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label>Related brokers</label>
                        <div class="ab-pick" data-broker-picks>
                            <input type="search" id="related-broker-filter" class="form-control" placeholder="Filter brokers" autocomplete="off">
                            <div class="ab-pick__list">
                                @forelse($brokers as $broker)
                                    <label class="ab-pick__item" data-name="{{ strtolower($broker->name.' '.$broker->slug) }}">
                                        <input type="checkbox" name="related_broker_ids[]" value="{{ $broker->id }}" @checked(in_array((int) $broker->id, $selectedBrokerIds, true))>
                                        <span>{{ $broker->name }} <small>{{ $broker->slug }}</small></span>
                                    </label>
                                @empty
                                    <p class="ab-pick__empty">No brokers are available.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="ab-section" id="faq">
                <div class="ab-section__head">
                    <h2>FAQ</h2>
                    <p>Optional questions shown on the public page with FAQ structured data.</p>
                </div>
                <div class="ab-section__body">
                    <div class="ab-repeater" id="faq-rows">
                        @foreach($faqRows as $index => $faq)
                            <div class="ab-repeater__item faq-row">
                                <div class="ab-repeater__head">
                                    <strong>Question {{ $index + 1 }}</strong>
                                    <button type="button" class="ab-btn ab-btn--ghost ab-btn--sm" data-remove-faq>Remove</button>
                                </div>
                                <div class="form-group">
                                    <label>Question</label>
                                    <input type="text" name="faqs[{{ $index }}][question]" class="form-control" value="{{ $faq['question'] ?? '' }}">
                                </div>
                                <div class="form-group mb-0">
                                    <label>Answer</label>
                                    <textarea name="faqs[{{ $index }}][answer]" rows="3" class="form-control">{{ $faq['answer'] ?? '' }}</textarea>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="ab-btn ab-btn--ghost mt-3" id="add-faq-row">
                        <i class="fas fa-plus" aria-hidden="true"></i>
                        Add FAQ
                    </button>
                </div>
            </section>

            <div class="ab-save">
                <button type="submit" class="ab-btn ab-btn--primary">Save changes</button>
                <a href="{{ route('admin_trading_tools_index') }}" class="ab-btn ab-btn--ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        var filter = document.getElementById('related-broker-filter');
        var items = Array.prototype.slice.call(document.querySelectorAll('[data-broker-picks] .ab-pick__item'));
        var faqWrap = document.getElementById('faq-rows');

        if (filter) {
            filter.addEventListener('input', function () {
                var query = (filter.value || '').toLowerCase().trim();
                items.forEach(function (item) {
                    var name = item.getAttribute('data-name') || '';
                    item.classList.toggle('is-hidden', query !== '' && name.indexOf(query) === -1);
                });
            });
        }

        document.getElementById('add-faq-row')?.addEventListener('click', function () {
            if (!faqWrap) return;
            var index = faqWrap.querySelectorAll('.faq-row').length;
            var row = document.createElement('div');
            row.className = 'ab-repeater__item faq-row';
            row.innerHTML = '<div class="ab-repeater__head"><strong>Question ' + (index + 1) + '</strong><button type="button" class="ab-btn ab-btn--ghost ab-btn--sm" data-remove-faq>Remove</button></div><div class="form-group"><label>Question</label><input type="text" name="faqs[' + index + '][question]" class="form-control"></div><div class="form-group mb-0"><label>Answer</label><textarea name="faqs[' + index + '][answer]" rows="3" class="form-control"></textarea></div>';
            faqWrap.appendChild(row);
        });

        faqWrap?.addEventListener('click', function (event) {
            var button = event.target.closest('[data-remove-faq]');
            if (!button) return;
            var rows = faqWrap.querySelectorAll('.faq-row');
            if (rows.length < 2) {
                rows[0].querySelectorAll('input, textarea').forEach(function (field) { field.value = ''; });
                return;
            }
            button.closest('.faq-row')?.remove();
        });
    })();
</script>
@endpush
