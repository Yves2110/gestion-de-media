document.addEventListener('DOMContentLoaded', function () {
    var config = window.learningSpaceConfig || {};
    var form = document.getElementById('learningFilterForm');
    var searchInput = document.getElementById('learningSearchInput');
    var suggestionsBox = document.getElementById('learningSuggestions');
    var suggestionsHub = document.getElementById('learningSuggestionsHub');
    var advancedToggle = document.querySelector('.learning-advanced-toggle');
    var advancedPanel = document.getElementById('learningAdvancedFilters');
    var debounceTimer = null;

    if (!form || !searchInput) {
        return;
    }

    function toggleAdvancedPanel() {
        if (!advancedPanel || !advancedToggle) {
            return;
        }

        var isHidden = advancedPanel.hasAttribute('hidden');
        if (isHidden) {
            advancedPanel.removeAttribute('hidden');
            advancedToggle.setAttribute('aria-expanded', 'true');
        } else {
            advancedPanel.setAttribute('hidden', 'hidden');
            advancedToggle.setAttribute('aria-expanded', 'false');
        }
    }

    advancedToggle?.addEventListener('click', toggleAdvancedPanel);

    if (advancedPanel && !advancedPanel.hasAttribute('hidden')) {
        advancedToggle?.setAttribute('aria-expanded', 'true');
    }

    document.querySelectorAll('.learning-type-pill').forEach(function (pill) {
        var input = pill.querySelector('.learning-type-input');
        if (!input) {
            return;
        }

        pill.addEventListener('click', function (event) {
            if (event.target === input) {
                return;
            }

            event.preventDefault();
            input.checked = !input.checked;
            pill.classList.toggle('active', input.checked);
        });

        input.addEventListener('change', function () {
            pill.classList.toggle('active', input.checked);
        });
    });

    document.querySelectorAll('.learning-chip-suggestion').forEach(function (chip) {
        chip.addEventListener('click', function () {
            searchInput.value = chip.dataset.suggestion || '';
            form.submit();
        });
    });

    function hideSuggestions() {
        if (!suggestionsBox) {
            return;
        }
        suggestionsBox.hidden = true;
        suggestionsBox.innerHTML = '';
        suggestionsHub?.classList.remove('is-dimmed');
    }

    function showSuggestionsPanel() {
        suggestionsHub?.classList.add('is-dimmed');
    }

    function renderSuggestions(data) {
        if (!suggestionsBox) {
            return;
        }

        var sections = [];
        var hasContent = false;
        var isCurated = data.mode === 'curated' || data.mode === 'personalized';

        if (data.message) {
            sections.push('<div class="learning-suggestion-intro">' + escapeHtml(data.message) + '</div>');
        } else if (isCurated) {
            sections.push('<div class="learning-suggestion-intro">Suggestions intelligentes — choisissez un point de départ</div>');
        }

        if (data.quick_searches && data.quick_searches.length) {
            hasContent = true;
            sections.push('<div class="learning-suggestion-group"><span class="learning-suggestion-label">Recherches populaires</span>' +
                data.quick_searches.map(function (label) {
                    return '<button type="button" class="learning-suggestion-item learning-suggestion-pick" data-value="' + escapeAttr(label) + '">' + escapeHtml(label) + '</button>';
                }).join('') + '</div>');
        }

        if (data.resources && data.resources.length) {
            hasContent = true;
            sections.push('<div class="learning-suggestion-group"><span class="learning-suggestion-label">Ressources</span>' +
                data.resources.map(function (item) {
                    return '<a class="learning-suggestion-item learning-suggestion-resource" href="' + escapeAttr(item.url) + '">' +
                        '<strong>' + escapeHtml(item.title) + '</strong>' +
                        '<small>' + escapeHtml(item.auteur || '') + ' · ' + escapeHtml(item.type || '') + '</small></a>';
                }).join('') + '</div>');
        }

        if (data.titles && data.titles.length) {
            hasContent = true;
            sections.push('<div class="learning-suggestion-group"><span class="learning-suggestion-label">Titres</span>' +
                data.titles.map(function (title) {
                    return '<button type="button" class="learning-suggestion-item learning-suggestion-pick" data-value="' + escapeAttr(title) + '">' + escapeHtml(title) + '</button>';
                }).join('') + '</div>');
        }

        if (data.authors && data.authors.length) {
            hasContent = true;
            sections.push('<div class="learning-suggestion-group"><span class="learning-suggestion-label">Auteurs</span>' +
                data.authors.map(function (author) {
                    return '<button type="button" class="learning-suggestion-item learning-suggestion-pick" data-value="' + escapeAttr(author) + '">' + escapeHtml(author) + '</button>';
                }).join('') + '</div>');
        }

        if (data.thematiques && data.thematiques.length) {
            hasContent = true;
            sections.push('<div class="learning-suggestion-group"><span class="learning-suggestion-label">Thématiques</span>' +
                data.thematiques.map(function (item) {
                    return '<button type="button" class="learning-suggestion-item learning-suggestion-thematique" data-id="' + item.id + '">' + escapeHtml(item.label) + '</button>';
                }).join('') + '</div>');
        }

        if (data.sources && data.sources.length) {
            hasContent = true;
            sections.push('<div class="learning-suggestion-group"><span class="learning-suggestion-label">Sources</span>' +
                data.sources.map(function (item) {
                    return '<button type="button" class="learning-suggestion-item learning-suggestion-source" data-id="' + item.id + '">' + escapeHtml(item.label) + '</button>';
                }).join('') + '</div>');
        }

        if (!hasContent) {
            hideSuggestions();
            return;
        }

        sections.push('<div class="learning-suggestion-footer"><span class="text-muted small">↑ ↓ pour naviguer · Entrée pour sélectionner</span></div>');

        suggestionsBox.innerHTML = sections.join('');
        suggestionsBox.hidden = false;
        showSuggestionsPanel();

        suggestionsBox.querySelectorAll('.learning-suggestion-pick').forEach(function (button) {
            button.addEventListener('click', function () {
                searchInput.value = button.dataset.value || '';
                form.submit();
            });
        });

        suggestionsBox.querySelectorAll('.learning-suggestion-thematique').forEach(function (button) {
            button.addEventListener('click', function () {
                applyThematiqueFilter(button.dataset.id);
            });
        });

        suggestionsBox.querySelectorAll('.learning-suggestion-source').forEach(function (button) {
            button.addEventListener('click', function () {
                applySourceFilter(button.dataset.id);
            });
        });
    }

    function applyThematiqueFilter(id) {
        var existing = form.querySelector('input[name="thematique_id[]"][value="' + id + '"]');
        if (existing) {
            existing.checked = true;
        }
        form.submit();
    }

    function applySourceFilter(id) {
        var existing = form.querySelector('input[name="source_id[]"][value="' + id + '"]');
        if (existing) {
            existing.checked = true;
        }
        form.submit();
    }

    function fetchSuggestions(term) {
        if (!config.suggestionsUrl) {
            return;
        }

        fetch(config.suggestionsUrl + '?q=' + encodeURIComponent(term), {
            headers: { 'Accept': 'application/json' },
        })
            .then(function (response) { return response.json(); })
            .then(renderSuggestions)
            .catch(hideSuggestions);
    }

    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () {
            var term = searchInput.value.trim();
            if (term.length === 0 || term.length >= 2) {
                fetchSuggestions(term);
            } else {
                hideSuggestions();
            }
        }, 220);
    });

    searchInput.addEventListener('focus', function () {
        fetchSuggestions(searchInput.value.trim());
    });

    document.addEventListener('click', function (event) {
        if (!suggestionsBox || suggestionsBox.hidden) {
            return;
        }

        if (!suggestionsBox.contains(event.target) && event.target !== searchInput) {
            hideSuggestions();
        }
    });

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function escapeAttr(value) {
        return escapeHtml(value).replace(/'/g, '&#39;');
    }

    if (typeof feather !== 'undefined') {
        feather.replace({ width: 14, height: 14 });
    }
});
