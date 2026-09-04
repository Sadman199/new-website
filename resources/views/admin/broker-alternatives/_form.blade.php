@php
    $isEdit = $page->exists;
    $selectedIds = array_map('intval', old('alternative_broker_ids', $selectedIds ?? []));
    $faqRows = old('faqs', $page->normalizedFaqs() ?: [['question' => '', 'answer' => '']]);
    $liveSlug = $page->broker->slug ?? '{slug}';
@endphp

<nav class="ab-section-nav" aria-label="Form sections">
    <a href="#page" data-ab-nav class="is-active">Page</a>
    <a href="#copy" data-ab-nav>SEO &amp; copy</a>
    <a href="#picks" data-ab-nav>Alternatives</a>
    <a href="#faq" data-ab-nav>FAQ</a>
    <a href="#publish" data-ab-nav>Publish</a>
</nav>

<div class="ab-layout ab-layout--form">
    <div class="ab-form-main">
        <section class="ab-section" id="page">
            <div class="ab-section__head">
                <h2>Source broker</h2>
                <p>The public page lives at <code>/broker-alternatives/{slug}</code> using this broker’s catalog slug.</p>
            </div>
            <div class="ab-section__body">
                <div class="form-group mb-0">
                    <label for="broker_id">Broker <span class="text-danger">*</span></label>
                    <select name="broker_id" id="broker_id" class="form-control @error('broker_id') is-invalid @enderror" required @disabled($isEdit)>
                        <option value="">Choose a broker</option>
                        @foreach($brokers as $broker)
                            @if($isEdit || ! in_array((int) $broker->id, $usedBrokerIds, true) || (string) old('broker_id', $page->broker_id) === (string) $broker->id)
                                <option value="{{ $broker->id }}" data-slug="{{ $broker->slug }}" @selected((string) old('broker_id', $page->broker_id) === (string) $broker->id)>{{ $broker->name }}</option>
                            @endif
                        @endforeach
                    </select>
                    @if($isEdit)
                        <input type="hidden" name="broker_id" value="{{ $page->broker_id }}">
                    @endif
                    @error('broker_id')<small class="ab-error">{{ $message }}</small>@enderror
                    <p class="ab-note mt-2 mb-0">Brokers that already have an alternatives page are hidden here.</p>
                </div>
            </div>
        </section>

        <section class="ab-section" id="copy">
            <div class="ab-section__head">
                <h2>SEO &amp; copy</h2>
                <p>Leave blank to generate a title, description, intro, and “why consider” blurb from live broker data.</p>
            </div>
            <div class="ab-section__body">
                <div class="form-group">
                    <label for="seo_title">SEO title</label>
                    <input type="text" name="seo_title" id="seo_title" class="form-control @error('seo_title') is-invalid @enderror" maxlength="255" value="{{ old('seo_title', $page->seo_title) }}" placeholder="Best Alternatives to {Broker} {{ date('Y') }}">
                    @error('seo_title')<small class="ab-error">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <label for="meta_description">Meta description</label>
                    <textarea name="meta_description" id="meta_description" rows="3" class="form-control @error('meta_description') is-invalid @enderror" maxlength="500" placeholder="Shown in search results">{{ old('meta_description', $page->meta_description) }}</textarea>
                    @error('meta_description')<small class="ab-error">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <label for="intro">Introduction</label>
                    <textarea name="intro" id="intro" rows="4" class="form-control" placeholder="Optional intro under the H1">{{ old('intro', $page->intro) }}</textarea>
                </div>
                <div class="form-group mb-0">
                    <label for="why_consider">Why consider alternatives</label>
                    <textarea name="why_consider" id="why_consider" rows="4" class="form-control" placeholder="Optional reasons traders compare this broker">{{ old('why_consider', $page->why_consider) }}</textarea>
                </div>
            </div>
        </section>

        <section class="ab-section" id="picks">
            <div class="ab-section__head">
                <h2>Recommended alternatives</h2>
                <p>Select brokers from the catalog. If none are selected, the public page automatically recommends similar brokers from live data.</p>
            </div>
            <div class="ab-section__body">
                <div class="ab-pick" data-alt-picks>
                    <input type="search" id="alt-broker-filter" class="form-control" placeholder="Filter brokers" autocomplete="off">
                    <div class="ab-pick__list" id="alt-broker-list">
                        @forelse($brokers as $broker)
                            <label class="ab-pick__item" data-name="{{ strtolower($broker->name.' '.$broker->slug) }}" data-id="{{ $broker->id }}">
                                <input type="checkbox" name="alternative_broker_ids[]" value="{{ $broker->id }}" @checked(in_array((int) $broker->id, $selectedIds, true))>
                                <span>
                                    {{ $broker->name }}
                                    <small>{{ $broker->slug }}</small>
                                </span>
                            </label>
                        @empty
                            <p class="ab-pick__empty">No brokers are available to recommend.</p>
                        @endforelse
                    </div>
                    <p class="ab-note mb-0"><span data-alt-count>0</span> selected. Hold nothing — just tick the names you want, in any order.</p>
                </div>
                @error('alternative_broker_ids')<small class="ab-error">{{ $message }}</small>@enderror
                @error('alternative_broker_ids.*')<small class="ab-error">{{ $message }}</small>@enderror
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
                                <input type="text" name="faqs[{{ $index }}][question]" class="form-control" value="{{ $faq['question'] ?? '' }}" placeholder="e.g. Why compare this broker?">
                            </div>
                            <div class="form-group mb-0">
                                <label>Answer</label>
                                <textarea name="faqs[{{ $index }}][answer]" rows="3" class="form-control" placeholder="Short answer shown on the public page">{{ $faq['answer'] ?? '' }}</textarea>
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
    </div>

    <aside class="ab-side" id="publish">
        <section class="ab-section">
            <div class="ab-section__head">
                <h2>Publish</h2>
                <p>Draft stays off the public site and sitemap.</p>
            </div>
            <div class="ab-section__body">
                <label class="ab-check">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" name="is_published" id="is_published" value="1" @checked(old('is_published', $page->is_published))>
                    <span>
                        Published
                        <small>Live at /broker-alternatives/<span data-live-slug>{{ $liveSlug }}</span></small>
                    </span>
                </label>
                @if($isEdit)
                    <p class="ab-note mb-0">Last saved {{ $page->updated_at?->format('M j, Y H:i') }}</p>
                @endif
            </div>
        </section>
        <div class="ab-save ab-save--side">
            <button type="submit" class="ab-btn ab-btn--primary">
                <i class="fas fa-save" aria-hidden="true"></i>
                {{ $isEdit ? 'Save changes' : 'Create page' }}
            </button>
            <a href="{{ route('admin_broker_alternatives_show') }}" class="ab-btn ab-btn--ghost">Cancel</a>
        </div>
    </aside>
</div>

<script>
    (function () {
        var source = document.getElementById('broker_id');
        var slugLabel = document.querySelector('[data-live-slug]');
        var filter = document.getElementById('alt-broker-filter');
        var items = Array.prototype.slice.call(document.querySelectorAll('.ab-pick__item'));
        var countEl = document.querySelector('[data-alt-count]');
        var faqWrap = document.getElementById('faq-rows');

        function selectedSourceId() {
            return source ? String(source.value || '') : '';
        }

        function refreshPicks() {
            var query = (filter && filter.value ? filter.value : '').toLowerCase().trim();
            var sourceId = selectedSourceId();
            var visible = 0;
            items.forEach(function (item) {
                var id = String(item.getAttribute('data-id') || '');
                var name = item.getAttribute('data-name') || '';
                var isSource = sourceId !== '' && id === sourceId;
                var matches = !query || name.indexOf(query) !== -1;
                item.classList.toggle('is-hidden', isSource || !matches);
                if (isSource) {
                    var box = item.querySelector('input[type="checkbox"]');
                    if (box) box.checked = false;
                }
                if (!item.classList.contains('is-hidden')) visible += 1;
            });
            if (countEl) {
                countEl.textContent = document.querySelectorAll('.ab-pick__item input[type="checkbox"]:checked').length;
            }
            return visible;
        }

        if (source) {
            source.addEventListener('change', function () {
                if (slugLabel) {
                    var option = source.options[source.selectedIndex];
                    slugLabel.textContent = (option && option.getAttribute('data-slug')) || '{slug}';
                }
                refreshPicks();
            });
        }

        if (filter) filter.addEventListener('input', refreshPicks);
        items.forEach(function (item) {
            var box = item.querySelector('input[type="checkbox"]');
            if (box) box.addEventListener('change', refreshPicks);
        });
        refreshPicks();

        document.getElementById('add-faq-row')?.addEventListener('click', function () {
            if (!faqWrap) return;
            var index = faqWrap.querySelectorAll('.faq-row').length;
            var row = document.createElement('div');
            row.className = 'ab-repeater__item faq-row';
            row.innerHTML = '<div class="ab-repeater__head"><strong>Question ' + (index + 1) + '</strong><button type="button" class="ab-btn ab-btn--ghost ab-btn--sm" data-remove-faq>Remove</button></div><div class="form-group"><label>Question</label><input type="text" name="faqs[' + index + '][question]" class="form-control" placeholder="e.g. Why compare this broker?"></div><div class="form-group mb-0"><label>Answer</label><textarea name="faqs[' + index + '][answer]" rows="3" class="form-control" placeholder="Short answer shown on the public page"></textarea></div>';
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
