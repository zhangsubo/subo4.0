/**
 * Vibe Coding Showcase 分类筛选与实时搜索交互
 *
 * @package SUBO4_Classic_Theme
 */

(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        var root = document.querySelector('[data-showcase-root]');
        if (!root) {
            return;
        }

        var grid = root.querySelector('[data-showcase-grid]');
        if (!grid) {
            return;
        }

        var cards = Array.prototype.slice.call(grid.querySelectorAll('.showcase-card'));
        var catButtons = Array.prototype.slice.call(root.querySelectorAll('[data-category-filter]'));
        var searchInput = root.querySelector('[data-showcase-search]');
        var clearBtn = root.querySelector('[data-showcase-search-clear]');
        var emptyBox = root.querySelector('[data-showcase-empty]');
        var resetBtn = root.querySelector('[data-showcase-reset]');
        var countPill = root.querySelector('[data-showcase-count]');
        var statusRegion = root.querySelector('[data-showcase-status]');

        var activeCategory = 'all';
        var totalCount = cards.length;

        function setCategoryPressed(slug) {
            catButtons.forEach(function (btn) {
                var isMatch = btn.getAttribute('data-category-filter') === slug;
                btn.setAttribute('aria-pressed', isMatch ? 'true' : 'false');
            });
        }

        function applyFilters() {
            var rawQuery = searchInput ? searchInput.value.trim().toLowerCase() : '';
            var tokens = rawQuery ? rawQuery.split(/\s+/).filter(Boolean) : [];
            var visibleCount = 0;

            if (clearBtn) {
                clearBtn.hidden = rawQuery.length === 0;
            }

            cards.forEach(function (card) {
                var catAttr = (card.getAttribute('data-categories') || '').trim();
                var cardCats = catAttr ? catAttr.split(/\s+/) : [];
                var searchHaystack = (card.getAttribute('data-search') || '').toLowerCase();

                var matchesCat = activeCategory === 'all' || cardCats.indexOf(activeCategory) !== -1;
                var matchesQuery = tokens.length === 0 || tokens.every(function (token) {
                    return searchHaystack.indexOf(token) !== -1;
                });

                var shouldShow = matchesCat && matchesQuery;
                card.hidden = !shouldShow;
                if (shouldShow) {
                    visibleCount += 1;
                }
            });

            var isFiltered = activeCategory !== 'all' || tokens.length > 0;

            grid.hidden = visibleCount === 0;
            if (emptyBox) {
                emptyBox.hidden = visibleCount !== 0;
            }

            if (countPill) {
                countPill.textContent = isFiltered
                    ? '显示 ' + visibleCount + ' / ' + totalCount + ' 款作品'
                    : '共 ' + totalCount + ' 款作品';
            }

            if (statusRegion) {
                statusRegion.textContent = '已筛选，共显示 ' + visibleCount + ' 款作品';
            }
        }

        catButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                activeCategory = btn.getAttribute('data-category-filter') || 'all';
                setCategoryPressed(activeCategory);
                applyFilters();
            });
        });

        if (searchInput) {
            searchInput.addEventListener('input', applyFilters);
            searchInput.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && searchInput.value !== '') {
                    event.preventDefault();
                    searchInput.value = '';
                    applyFilters();
                }
            });
        }

        if (clearBtn && searchInput) {
            clearBtn.addEventListener('click', function () {
                searchInput.value = '';
                applyFilters();
                searchInput.focus();
            });
        }

        if (resetBtn) {
            resetBtn.addEventListener('click', function () {
                activeCategory = 'all';
                setCategoryPressed('all');
                if (searchInput) {
                    searchInput.value = '';
                }
                applyFilters();
            });
        }
    });
})();
