(function () {
    'use strict';

    var config = window.BROKER_COMPARE || {};
    var brokers = config.brokers || [];
    var tabGroups = config.tabGroups || {};
    var slots = [null, null, null];
    var activeTab = 'overall';
    var openSlotIndex = null;

    var els = {};

    function $(id) {
        return document.getElementById(id);
    }

    function brokerBySlug(slug) {
        return brokers.find(function (b) {
            return b.slug === slug;
        }) || null;
    }

    function usedSlugs(excludeIndex) {
        return slots
            .map(function (s, i) {
                return i === excludeIndex ? null : (s ? s.slug : null);
            })
            .filter(Boolean);
    }

    function init() {
        els.pickers = document.querySelectorAll('[data-compare-slot]');
        els.matrixWrap = $('bcCompareMatrixWrap');
        els.suggestions = $('bcCompareSuggestions');
        els.sidebarHead = $('bcCompareSidebarHead');
        els.sidebarRows = $('bcCompareSidebarRows');
        els.tabButtons = document.querySelectorAll('[data-compare-tab]');
        els.clearBtn = $('bcCompareClearBtn');

        bindTabs();
        bindPickers();
        bindSuggestions();
        bindActions();
        renderAll();

        document.addEventListener('click', function (e) {
            if (!e.target.closest('[data-compare-slot]')) {
                closeAllSlots();
            }
        });
    }

    function bindTabs() {
        els.tabButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                activeTab = btn.getAttribute('data-compare-tab');
                els.tabButtons.forEach(function (b) {
                    b.classList.toggle('is-active', b === btn);
                });
                renderAll();
            });
        });
    }

    function bindPickers() {
        els.pickers.forEach(function (slotEl) {
            var index = parseInt(slotEl.getAttribute('data-compare-slot'), 10);
            var inner = slotEl.querySelector('.bc-compare-slot__inner');
            var clearBtn = slotEl.querySelector('.bc-compare-slot__clear');
            var searchInput = slotEl.querySelector('.bc-compare-slot__search-input');

            inner.addEventListener('click', function (e) {
                if (e.target.closest('.bc-compare-slot__clear')) {
                    return;
                }
                toggleSlot(index);
            });

            if (clearBtn) {
                clearBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    slots[index] = null;
                    closeAllSlots();
                    renderAll();
                });
            }

            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    renderSearchResults(index, searchInput.value.trim());
                });

                searchInput.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') {
                        closeAllSlots();
                    }
                });
            }
        });
    }

    function bindSuggestions() {
        document.querySelectorAll('[data-suggest-slug]').forEach(function (card) {
            card.addEventListener('click', function () {
                var slug = card.getAttribute('data-suggest-slug');
                var broker = brokerBySlug(slug);
                if (!broker) {
                    return;
                }
                var emptyIndex = slots.findIndex(function (s) {
                    return s === null;
                });
                if (emptyIndex === -1) {
                    emptyIndex = 0;
                }
                slots[emptyIndex] = broker;
                renderAll();
            });
        });
    }

    function bindActions() {
        if (els.clearBtn) {
            els.clearBtn.addEventListener('click', function () {
                slots = [null, null, null];
                renderAll();
            });
        }
    }

    function toggleSlot(index) {
        if (openSlotIndex === index) {
            closeAllSlots();
            return;
        }
        closeAllSlots();
        openSlotIndex = index;
        var slotEl = els.pickers[index];
        slotEl.classList.add('is-open');
        var input = slotEl.querySelector('.bc-compare-slot__search-input');
        if (input) {
            input.value = '';
            renderSearchResults(index, '');
            setTimeout(function () {
                input.focus();
            }, 50);
        }
    }

    function closeAllSlots() {
        openSlotIndex = null;
        els.pickers.forEach(function (slotEl) {
            slotEl.classList.remove('is-open');
        });
    }

    function renderSearchResults(index, query) {
        var slotEl = els.pickers[index];
        var resultsEl = slotEl.querySelector('.bc-compare-slot__results');
        var taken = usedSlugs(index);
        var q = query.toLowerCase();
        var filtered = brokers.filter(function (b) {
            if (q && b.name.toLowerCase().indexOf(q) === -1) {
                return false;
            }
            return true;
        }).slice(0, 20);

        if (!filtered.length) {
            resultsEl.innerHTML = '<div class="bc-compare-slot__empty">No brokers found</div>';
            return;
        }

        resultsEl.innerHTML = filtered.map(function (b) {
            var disabled = taken.indexOf(b.slug) !== -1;
            var logo = b.logo
                ? '<img src="' + escapeAttr(b.logo) + '" alt="">'
                : '<span>' + escapeHtml(b.name.charAt(0)) + '</span>';
            return (
                '<button type="button" class="bc-compare-slot__result' + (disabled ? ' is-disabled' : '') + '" ' +
                'data-pick-slug="' + escapeAttr(b.slug) + '" data-slot-index="' + index + '"' +
                (disabled ? ' disabled' : '') + '>' +
                '<span class="bc-compare-slot__logo">' + logo + '</span>' +
                '<span>' + escapeHtml(b.name) + '</span>' +
                '</button>'
            );
        }).join('');

        resultsEl.querySelectorAll('[data-pick-slug]').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                if (btn.disabled) {
                    return;
                }
                var slug = btn.getAttribute('data-pick-slug');
                var broker = brokerBySlug(slug);
                if (broker) {
                    slots[index] = broker;
                    closeAllSlots();
                    renderAll();
                }
            });
        });
    }

    function selectedBrokers() {
        return slots.filter(Boolean);
    }

    function currentRows() {
        var group = tabGroups[activeTab];
        return group ? group.rows : [];
    }

    function renderAll() {
        renderPickers();
        renderSidebar();
        var selected = selectedBrokers();
        if (selected.length) {
            els.suggestions.classList.add('bc-compare-hidden');
            els.matrixWrap.classList.remove('bc-compare-hidden');
            renderMatrix(selected);
        } else {
            els.suggestions.classList.remove('bc-compare-hidden');
            els.matrixWrap.classList.add('bc-compare-hidden');
        }
    }

    function renderPickers() {
        els.pickers.forEach(function (slotEl, index) {
            var broker = slots[index];
            var inner = slotEl.querySelector('.bc-compare-slot__inner');
            var placeholder = slotEl.querySelector('.bc-compare-slot__placeholder');
            var selectedWrap = slotEl.querySelector('.bc-compare-slot__selected');

            if (broker) {
                inner.classList.add('has-broker');
                placeholder.classList.add('bc-compare-hidden');
                selectedWrap.classList.remove('bc-compare-hidden');
                selectedWrap.querySelector('.bc-compare-slot__name').textContent = broker.name;
                var logoEl = selectedWrap.querySelector('.bc-compare-slot__logo');
                logoEl.innerHTML = broker.logo
                    ? '<img src="' + escapeAttr(broker.logo) + '" alt="">'
                    : '<span>' + escapeHtml(broker.name.charAt(0)) + '</span>';
            } else {
                inner.classList.remove('has-broker');
                placeholder.classList.remove('bc-compare-hidden');
                selectedWrap.classList.add('bc-compare-hidden');
            }
        });
    }

    function renderSidebar() {
        var group = tabGroups[activeTab];
        if (!group) {
            return;
        }
        els.sidebarHead.textContent = group.label;
        els.sidebarRows.innerHTML = group.rows.map(function (row) {
            return '<li class="bc-compare-sidebar__row">' + escapeHtml(row.label) + '</li>';
        }).join('');
    }

    function renderMatrix(selected) {
        var rows = currentRows();
        var uniqueRows = dedupeRows(rows);

        var headHtml = selected.map(function (b) {
            var logo = b.logo
                ? '<img src="' + escapeAttr(b.logo) + '" alt="">'
                : '<span>' + escapeHtml(b.name.charAt(0)) + '</span>';
            var score = b.rating !== null ? numberFormat(b.rating, 1) : '—';
            return (
                '<th><div class="bc-compare-matrix__broker-head">' +
                '<div class="bc-compare-matrix__broker-logo">' + logo + '</div>' +
                '<div class="bc-compare-matrix__broker-name">' + escapeHtml(b.name) + '</div>' +
                '<div class="bc-compare-matrix__broker-score">' + score + '</div>' +
                '</div></th>'
            );
        }).join('');

        var bodyHtml = uniqueRows.map(function (row) {
            var values = selected.map(function (b) {
                return formatValue(row.key, b[row.key]);
            });
            var bestIndex = findBestIndex(row.key, values);
            var cells = values.map(function (val, i) {
                var cls = cellClass(row.key, val, i === bestIndex);
                return '<td class="' + cls + '">' + escapeHtml(String(val)) + '</td>';
            }).join('');
            return '<tr>' + cells + '</tr>';
        }).join('');

        els.matrixWrap.innerHTML =
            '<table class="bc-compare-matrix">' +
            '<thead><tr>' + headHtml + '</tr></thead>' +
            '<tbody>' + bodyHtml + '</tbody>' +
            '</table>';
    }

    function dedupeRows(rows) {
        var seen = {};
        return rows.filter(function (row) {
            var id = row.key + '|' + row.label;
            if (seen[id]) {
                return false;
            }
            seen[id] = true;
            return true;
        });
    }

    function formatValue(key, val) {
        if (val === null || val === undefined || val === '') {
            return '—';
        }
        return val;
    }

    function cellClass(key, val, isBest) {
        if (val === 'Yes') {
            return 'bc-val--bool-yes';
        }
        if (val === 'No') {
            return 'bc-val--bool-no';
        }
        if (isBest && isComparable(key)) {
            return 'bc-val--best';
        }
        return '';
    }

    function isComparable(key) {
        return ['rating', 'trust_score', 'review_count', 'instrument_count'].indexOf(key) !== -1
            || key === 'minimum_deposit';
    }

    function findBestIndex(key, values) {
        if (!isComparable(key)) {
            return -1;
        }
        var numeric = values.map(function (v, i) {
            return { index: i, num: parseComparable(key, v) };
        }).filter(function (x) {
            return x.num !== null;
        });
        if (numeric.length < 2) {
            return -1;
        }
        numeric.sort(function (a, b) {
            if (key === 'minimum_deposit') {
                return a.num - b.num;
            }
            return b.num - a.num;
        });
        return numeric[0].index;
    }

    function parseComparable(key, val) {
        if (val === '—' || val === null) {
            return null;
        }
        if (key === 'minimum_deposit') {
            var m = String(val).replace(/[^0-9.]/g, '');
            return m ? parseFloat(m) : null;
        }
        if (key === 'instrument_count') {
            var n = String(val).replace(/[^0-9]/g, '');
            return n ? parseInt(n, 10) : null;
        }
        var num = parseFloat(String(val).replace(/[^0-9.]/g, ''));
        return isNaN(num) ? null : num;
    }

    function numberFormat(n, d) {
        return parseFloat(n).toFixed(d);
    }

    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function escapeAttr(str) {
        return escapeHtml(str).replace(/'/g, '&#39;');
    }

    function initResultPage() {
        var tocLinks = document.querySelectorAll('[data-result-toc]');
        var sections = document.querySelectorAll('[data-result-section]');

        if (!tocLinks.length || !sections.length) {
            return;
        }

        tocLinks.forEach(function (link) {
            link.addEventListener('click', function (e) {
                var target = document.getElementById('bc-result-' + link.getAttribute('data-result-toc'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        if (!('IntersectionObserver' in window)) {
            return;
        }

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) {
                    return;
                }

                var id = entry.target.getAttribute('data-result-section');
                tocLinks.forEach(function (link) {
                    link.classList.toggle('is-active', link.getAttribute('data-result-toc') === id);
                });
            });
        }, {
            rootMargin: '-20% 0px -60% 0px',
            threshold: 0
        });

        sections.forEach(function (section) {
            observer.observe(section);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            init();
            if (document.querySelector('.bc-result-page')) {
                initResultPage();
            }
        });
    } else {
        init();
        if (document.querySelector('.bc-result-page')) {
            initResultPage();
        }
    }
})();
