(function () {
    'use strict';

    var form = document.getElementById('cms-page-form') || document.getElementById('broker-guide-form');
    if (!form) return;

    var dataEl = document.getElementById('cms-section-types-data');
    var listEl = document.getElementById('cms-sections-list');
    var emptyEl = document.getElementById('cms-sections-empty');
    var payloadEl = document.getElementById('sections_payload');
    var titleInput = document.getElementById('title');
    var slugInput = document.getElementById('slug');
    var cardTemplate = document.getElementById('cms-section-card-template');
    var sectionCountEl = document.getElementById('cms-section-count');
    var sectionCountBadge = document.getElementById('cms-section-count-badge');
    var metaTitleInput = document.getElementById('meta_title');
    var metaDescInput = document.getElementById('meta_description');
    var metaTitleCount = document.getElementById('meta-title-count');
    var metaDescCount = document.getElementById('meta-desc-count');

    var config = dataEl ? JSON.parse(dataEl.textContent || '{}') : {};
    var sections = Array.isArray(config.initial) ? config.initial : [];
    var typeLabels = config.types || {};
    var catalog = config.catalog || {};
    var defaults = config.defaults || {};

    var slugTouched = !!(slugInput && slugInput.value);

    if (slugInput) {
        slugInput.addEventListener('input', function () { slugTouched = true; });
    }

    if (titleInput && slugInput) {
        titleInput.addEventListener('input', function () {
            if (!slugTouched) slugInput.value = slugify(titleInput.value);
        });
    }

    function slugify(value) {
        return String(value || '').toLowerCase().trim()
            .replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
    }

    function esc(value) {
        return String(value == null ? '' : value)
            .replace(/&/g, '&amp;').replace(/"/g, '&quot;')
            .replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    function updateCounts() {
        var n = sections.length;
        if (sectionCountEl) sectionCountEl.textContent = n;
        if (sectionCountBadge) sectionCountBadge.textContent = n;
        if (emptyEl) emptyEl.style.display = n ? 'none' : 'block';
    }

    function updateMetaCounts() {
        if (metaTitleCount && metaTitleInput) metaTitleCount.textContent = metaTitleInput.value.length;
        if (metaDescCount && metaDescInput) metaDescCount.textContent = metaDescInput.value.length;
    }

    if (metaTitleInput) metaTitleInput.addEventListener('input', updateMetaCounts);
    if (metaDescInput) metaDescInput.addEventListener('input', updateMetaCounts);
    updateMetaCounts();

    function field(label, name, value, type, hint) {
        type = type || 'text';
        var html = '<div class="cms-field-row">';
        html += '<label>' + esc(label) + '</label>';
        if (type === 'textarea') {
            html += '<textarea class="form-control form-control-sm" data-field="' + esc(name) + '">' + esc(value) + '</textarea>';
        } else if (type === 'select-dark-light') {
            html += '<select class="form-control form-control-sm" data-field="' + esc(name) + '">';
            html += '<option value="dark"' + (value === 'dark' ? ' selected' : '') + '>Dark (site standard)</option>';
            html += '<option value="light"' + (value === 'light' ? ' selected' : '') + '>Light</option>';
            html += '</select>';
        } else if (type === 'select-left-right') {
            html += '<select class="form-control form-control-sm" data-field="' + esc(name) + '">';
            html += '<option value="left"' + (value === 'left' ? ' selected' : '') + '>Left</option>';
            html += '<option value="right"' + (value === 'right' ? ' selected' : '') + '>Right</option>';
            html += '</select>';
        } else if (type === 'select-align') {
            html += '<select class="form-control form-control-sm" data-field="' + esc(name) + '">';
            html += '<option value="left"' + (value === 'left' ? ' selected' : '') + '>Left</option>';
            html += '<option value="center"' + (value === 'center' ? ' selected' : '') + '>Center</option>';
            html += '</select>';
        } else if (type === 'select-columns') {
            html += '<select class="form-control form-control-sm" data-field="' + esc(name) + '">';
            [2, 3, 4].forEach(function (n) {
                html += '<option value="' + n + '"' + (String(value) === String(n) ? ' selected' : '') + '>' + n + ' columns</option>';
            });
            html += '</select>';
        } else if (type === 'checkbox') {
            html += '<div class="custom-control custom-checkbox mt-1">';
            html += '<input type="checkbox" class="custom-control-input" data-field="' + esc(name) + '" id="' + esc(name + '-' + Math.random()) + '"' + (value ? ' checked' : '') + '>';
            html += '<label class="custom-control-label">Enabled</label></div>';
        } else {
            html += '<input type="' + esc(type) + '" class="form-control form-control-sm" data-field="' + esc(name) + '" value="' + esc(value) + '">';
        }
        if (hint) html += '<div class="form-text-inline">' + esc(hint) + '</div>';
        html += '</div>';
        return html;
    }

    function repeater(title, itemKey, fields, items) {
        items = Array.isArray(items) ? items : [];
        var uid = 'rep-' + Math.random().toString(36).slice(2);
        var html = '<div class="cms-repeater" data-repeater="' + esc(itemKey) + '">';
        html += '<div class="cms-repeater__head"><strong>' + esc(title) + '</strong>';
        html += '<button type="button" class="btn btn-sm btn-outline-primary cms-repeater-add">Add</button></div>';
        html += '<div class="cms-repeater-items" id="' + uid + '">';
        if (!items.length) items = [{}];
        items.forEach(function (item, idx) { html += repeaterItem(fields, item, idx); });
        html += '</div></div>';
        return html;
    }

    function repeaterItem(fields, item, idx) {
        item = item || {};
        var html = '<div class="cms-repeater-item" data-index="' + idx + '">';
        html += '<div class="d-flex justify-content-between mb-2"><small class="text-muted">Item ' + (idx + 1) + '</small>';
        html += '<button type="button" class="btn btn-sm btn-link text-danger p-0 cms-repeater-remove">Remove</button></div>';
        fields.forEach(function (f) {
            html += field(f.label, f.key, item[f.key] || '', f.type || 'text', f.hint);
        });
        html += '</div>';
        return html;
    }

    function renderFields(type, data) {
        data = data || defaults[type] || {};
        var html = '';

        switch (type) {
            case 'hero':
                html += field('Eyebrow label', 'eyebrow', data.eyebrow, 'text', 'Small pill above the title');
                html += field('Headline', 'headline', data.headline);
                html += field('Subheadline', 'subheadline', data.subheadline, 'textarea');
                html += field('Background style', 'background_style', data.background_style, 'select-dark-light', 'Dark matches Contact, Promotions, and other site pages');
                html += '<div class="cms-field-grid cms-field-grid--2">';
                html += field('Primary button label', 'cta_label', data.cta_label);
                html += field('Primary button URL', 'cta_url', data.cta_url);
                html += field('Secondary button label', 'secondary_cta_label', data.secondary_cta_label);
                html += field('Secondary button URL', 'secondary_cta_url', data.secondary_cta_url);
                html += '</div>';
                html += repeater('Hero metrics (optional)', 'metrics', [
                    { key: 'label', label: 'Label' },
                    { key: 'value', label: 'Value' },
                    { key: 'tone', label: 'Tone', hint: 'Use "highlight" for accent stat' }
                ], data.metrics);
                break;
            case 'text_content':
                html += field('Heading', 'heading', data.heading);
                html += field('Body', 'body', data.body, 'textarea', 'HTML allowed for links and emphasis');
                html += field('Alignment', 'align', data.align, 'select-align');
                break;
            case 'image_text':
                html += field('Heading', 'heading', data.heading);
                html += field('Body', 'body', data.body, 'textarea');
                html += field('Image URL', 'image', data.image);
                html += field('Image alt text', 'image_alt', data.image_alt);
                html += field('Image position', 'image_position', data.image_position, 'select-left-right');
                break;
            case 'cards':
                html += field('Section heading', 'heading', data.heading);
                html += field('Subheading', 'subheading', data.subheading);
                html += field('Columns', 'columns', data.columns, 'select-columns');
                html += repeater('Cards', 'items', [
                    { key: 'title', label: 'Title' },
                    { key: 'text', label: 'Text', type: 'textarea' },
                    { key: 'icon', label: 'Icon', hint: 'Emoji or Font Awesome class' },
                    { key: 'url', label: 'Link URL (optional)' }
                ], data.items);
                break;
            case 'statistics':
                html += field('Heading', 'heading', data.heading);
                html += repeater('Statistics', 'items', [
                    { key: 'value', label: 'Value' },
                    { key: 'label', label: 'Label' },
                    { key: 'tone', label: 'Tone', hint: '"highlight" for accent' }
                ], data.items);
                break;
            case 'faq':
                html += field('Heading', 'heading', data.heading);
                html += repeater('Questions', 'items', [
                    { key: 'question', label: 'Question' },
                    { key: 'answer', label: 'Answer', type: 'textarea' }
                ], data.items);
                break;
            case 'timeline':
                html += field('Heading', 'heading', data.heading);
                html += repeater('Timeline events', 'items', [
                    { key: 'year', label: 'Year' },
                    { key: 'title', label: 'Title' },
                    { key: 'text', label: 'Description', type: 'textarea' }
                ], data.items);
                break;
            case 'team_members':
                html += field('Heading', 'heading', data.heading);
                html += field('Subheading', 'subheading', data.subheading);
                html += repeater('Team members', 'items', [
                    { key: 'name', label: 'Name' },
                    { key: 'role', label: 'Role' },
                    { key: 'photo', label: 'Photo URL' },
                    { key: 'bio', label: 'Bio', type: 'textarea' }
                ], data.items);
                break;
            case 'table':
                html += field('Heading', 'heading', data.heading);
                html += field('Caption', 'caption', data.caption);
                html += field('Headers', 'headers_csv', (data.headers || []).join(', '), 'text', 'Comma-separated');
                html += field('Rows', 'rows_csv', (data.rows || []).map(function (r) { return r.join(' | '); }).join('\n'), 'textarea', 'One row per line, cells separated by |');
                break;
            case 'cta':
                html += field('Heading', 'heading', data.heading);
                html += field('Text', 'text', data.text, 'textarea');
                html += field('Button label', 'button_label', data.button_label);
                html += field('Button URL', 'button_url', data.button_url);
                html += field('Style', 'style', data.style, 'text', 'primary (ocean blue) or dark (midnight)');
                break;
            case 'contact_form':
                html += field('Heading', 'heading', data.heading);
                html += field('Subheading', 'subheading', data.subheading, 'textarea');
                html += field('Show info cards', 'show_info_cards', data.show_info_cards, 'checkbox');
                break;
            case 'glossary':
                html += field('Heading', 'heading', data.heading);
                html += field('Intro', 'intro', data.intro, 'textarea');
                html += repeater('Terms', 'items', [
                    { key: 'term', label: 'Term' },
                    { key: 'definition', label: 'Definition', type: 'textarea' }
                ], data.items);
                break;
            default:
                html += '<p class="text-muted mb-0">No fields configured.</p>';
        }

        return html;
    }

    function readCard(card) {
        var type = card.getAttribute('data-type');
        var body = card.querySelector('.cms-section-body');
        var data = {};

        body.querySelectorAll('[data-field]').forEach(function (el) {
            var key = el.getAttribute('data-field');
            data[key] = el.type === 'checkbox' ? el.checked : el.value;
        });

        body.querySelectorAll('.cms-repeater').forEach(function (rep) {
            var key = rep.getAttribute('data-repeater');
            var items = [];
            rep.querySelectorAll('.cms-repeater-item').forEach(function (itemEl) {
                var item = {};
                itemEl.querySelectorAll('[data-field]').forEach(function (el) {
                    item[el.getAttribute('data-field')] = el.type === 'checkbox' ? el.checked : el.value;
                });
                if (Object.values(item).some(function (v) { return String(v || '').trim() !== ''; })) items.push(item);
            });
            data[key] = items;
        });

        if (type === 'table') {
            data.headers = String(data.headers_csv || '').split(',').map(function (s) { return s.trim(); }).filter(Boolean);
            data.rows = String(data.rows_csv || '').split('\n').map(function (line) {
                return line.split('|').map(function (cell) { return cell.trim(); });
            }).filter(function (row) { return row.some(Boolean); });
            delete data.headers_csv;
            delete data.rows_csv;
        }

        return { section_type: type, section_data: data };
    }

    function addSection(type) {
        sections.push({
            section_type: type,
            section_data: JSON.parse(JSON.stringify(defaults[type] || {}))
        });
        renderAll();
        initSortable();
        syncPayload();
        var cards = listEl.querySelectorAll('.cms-section-card');
        if (cards.length) {
            var last = cards[cards.length - 1];
            last.classList.remove('is-collapsed');
            last.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }

    function renderAll() {
        listEl.innerHTML = '';
        sections.forEach(function (section, index) { renderCard(section, index); });
        updateCounts();
    }

    function renderCard(section, index) {
        var type = section.section_type || section.type;
        var data = section.section_data || section.data || defaults[type] || {};
        var meta = catalog[type] || {};
        var node = cardTemplate.content.firstElementChild.cloneNode(true);

        node.setAttribute('data-index', index);
        node.setAttribute('data-type', type);
        node.querySelector('.cms-section-label').textContent = (typeLabels[type] || type) + ' · #' + (index + 1);
        node.querySelector('.cms-section-desc').textContent = meta.desc || '';
        var iconEl = node.querySelector('.cms-section-icon i');
        if (iconEl && meta.icon) iconEl.className = 'fas ' + meta.icon;
        node.querySelector('.cms-section-body').innerHTML = renderFields(type, data);

        if (index > 0) node.classList.add('is-collapsed');

        listEl.appendChild(node);
    }

    function syncFromDom() {
        sections = Array.from(listEl.querySelectorAll('.cms-section-card')).map(readCard);
    }

    function syncPayload() {
        if (!payloadEl) return;
        payloadEl.value = JSON.stringify(sections);
    }

    function removeSection(card) {
        syncFromDom();
        var cards = Array.from(listEl.querySelectorAll('.cms-section-card'));
        var idx = cards.indexOf(card);
        if (idx < 0) return;
        sections.splice(idx, 1);
        renderAll();
        initSortable();
        syncPayload();
    }

    document.querySelectorAll('[data-add-section]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            addSection(btn.getAttribute('data-add-section'));
        });
    });

    listEl.addEventListener('click', function (e) {
        var card = e.target.closest('.cms-section-card');
        if (!card) return;

        if (e.target.closest('.cms-section-remove')) {
            if (!confirm('Remove this section?')) return;
            removeSection(card);
            return;
        }

        if (e.target.closest('.cms-section-toggle')) {
            card.classList.toggle('is-collapsed');
            return;
        }

        if (e.target.closest('.cms-repeater-add')) {
            var rep = e.target.closest('.cms-repeater');
            var container = rep.querySelector('.cms-repeater-items');
            var fields = [];
            rep.querySelectorAll('.cms-repeater-item:first-child [data-field]').forEach(function (el) {
                var labelEl = el.closest('.cms-field-row').querySelector('label');
                fields.push({
                    key: el.getAttribute('data-field'),
                    label: labelEl ? labelEl.textContent : el.getAttribute('data-field'),
                    type: el.tagName === 'TEXTAREA' ? 'textarea' : (el.type === 'checkbox' ? 'checkbox' : 'text')
                });
            });
            container.insertAdjacentHTML('beforeend', repeaterItem(fields, {}, container.children.length));
            syncPayload();
            return;
        }

        if (e.target.closest('.cms-repeater-remove')) {
            var item = e.target.closest('.cms-repeater-item');
            if (item) item.remove();
            syncFromDom();
            syncPayload();
        }
    });

    form.addEventListener('submit', function () {
        syncFromDom();
        syncPayload();
    }, true);

    var sortableInstance = null;

    function initSortable() {
        if (typeof Sortable === 'undefined') return;
        if (sortableInstance) sortableInstance.destroy();
        sortableInstance = Sortable.create(listEl, {
            handle: '.cms-section-handle',
            animation: 150,
            ghostClass: 'is-dragging',
            onEnd: function () {
                syncFromDom();
                renderAll();
                initSortable();
                syncPayload();
            }
        });
    }

    renderAll();
    initSortable();
    syncPayload();
})();
