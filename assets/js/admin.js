/**
 * Admin Scripts (Theme Settings Tabs, Media Uploader, and Meta Box Visibility)
 *
 * @package SUBO4_Classic_Theme
 */

(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // 1. Settings page tabs
        var tabLinks = document.querySelectorAll('[data-subo4-tab]');
        var tabPanels = document.querySelectorAll('[data-subo4-panel]');
        if (tabLinks.length > 0 && tabPanels.length > 0) {
            var activateTab = function (key) {
                tabLinks.forEach(function (link) {
                    link.classList.toggle('nav-tab-active', link.getAttribute('data-subo4-tab') === key);
                });
                tabPanels.forEach(function (panel) {
                    panel.hidden = panel.getAttribute('data-subo4-panel') !== key;
                });
            };

            var hashKey = window.location.hash.replace(/^#tab-/, '');
            if (hashKey && document.querySelector('[data-subo4-panel="' + hashKey + '"]')) {
                activateTab(hashKey);
            }

            tabLinks.forEach(function (link) {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    var key = this.getAttribute('data-subo4-tab');
                    activateTab(key);
                    if (window.history && window.history.replaceState) {
                        window.history.replaceState(null, '', '#tab-' + key);
                    }
                });
            });
        }

        // 2. Native wp.media image picker
        document.querySelectorAll('[data-media-field]').forEach(function (container) {
            var input = container.querySelector('[data-media-input]');
            var preview = container.querySelector('[data-media-preview]');
            var selectBtn = container.querySelector('[data-media-select]');
            var removeBtn = container.querySelector('[data-media-remove]');
            var frame = null;

            if (!input || !preview || !selectBtn || !removeBtn) {
                return;
            }

            var setPreview = function (url) {
                if (url) {
                    preview.innerHTML = '<img src="' + url.replace(/"/g, '&quot;') + '" alt="">';
                    preview.classList.add('has-image');
                    removeBtn.hidden = false;
                } else {
                    preview.innerHTML = '<span class="subo4-media-placeholder">未选择图片</span>';
                    preview.classList.remove('has-image');
                    removeBtn.hidden = true;
                }
            };

            selectBtn.addEventListener('click', function (e) {
                e.preventDefault();
                if (typeof wp === 'undefined' || !wp.media) {
                    return;
                }
                if (frame) {
                    frame.open();
                    return;
                }
                frame = wp.media({
                    title: '选择图片',
                    library: { type: 'image' },
                    button: { text: '使用此图片' },
                    multiple: false
                });
                frame.on('select', function () {
                    var attachment = frame.state().get('selection').first().toJSON();
                    if (!attachment || !attachment.id) {
                        return;
                    }
                    input.value = String(attachment.id);
                    var thumb = attachment.sizes && attachment.sizes.thumbnail
                        ? attachment.sizes.thumbnail.url
                        : attachment.url;
                    setPreview(thumb || attachment.url || '');
                });
                frame.open();
            });

            removeBtn.addEventListener('click', function (e) {
                e.preventDefault();
                input.value = '';
                setPreview('');
            });
        });

        // 3. Project meta box conditional fields
        var featuredToggle = document.querySelector('[data-toggle-featured]');
        var featuredOnly = document.querySelectorAll('[data-featured-only]');
        if (featuredToggle && featuredOnly.length > 0) {
            var syncFeatured = function () {
                featuredOnly.forEach(function (el) {
                    el.hidden = !featuredToggle.checked;
                });
            };
            featuredToggle.addEventListener('change', syncFeatured);
            syncFeatured();
        }

        var detailSelect = document.querySelector('[data-detail-mode]');
        var detailNotNone = document.querySelectorAll('[data-detail-not-none]');
        var detailCustomOnly = document.querySelectorAll('[data-detail-custom-only]');
        if (detailSelect) {
            var syncDetailMode = function () {
                var val = detailSelect.value;
                detailNotNone.forEach(function (el) {
                    el.hidden = val === 'none';
                });
                detailCustomOnly.forEach(function (el) {
                    el.hidden = val !== 'custom';
                });
            };
            detailSelect.addEventListener('change', syncDetailMode);
            syncDetailMode();
        }

        // 4. Show Showcase meta box only when page-vibe-showcase.php template is active
        var showcaseBox = document.getElementById('subo4_showcase_page_meta_box');
        if (showcaseBox) {
            var targetTpl = 'page-vibe-showcase.php';
            var updateBoxVisibility = function (tpl) {
                showcaseBox.style.display = tpl === targetTpl ? '' : 'none';
            };

            var classicSelect = document.getElementById('page_template');
            if (classicSelect) {
                updateBoxVisibility(classicSelect.value);
                classicSelect.addEventListener('change', function () {
                    updateBoxVisibility(this.value);
                });
            } else if (typeof wp !== 'undefined' && wp.data && wp.data.select && wp.data.subscribe) {
                var lastTpl = null;
                var syncBlockTemplate = function () {
                    var editor = wp.data.select('core/editor');
                    if (!editor || typeof editor.getEditedPostAttribute !== 'function') {
                        return;
                    }
                    var tpl = editor.getEditedPostAttribute('template') || '';
                    if (tpl !== lastTpl) {
                        lastTpl = tpl;
                        updateBoxVisibility(tpl);
                    }
                };
                syncBlockTemplate();
                wp.data.subscribe(syncBlockTemplate);
            }
        }
    });
})();
