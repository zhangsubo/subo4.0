/**
 * Reading Enhancements: Table of Contents, Code Highlight & Copy, Image Lightbox
 *
 * @package SUBO4_Classic_Theme
 */

(function () {
    'use strict';

    var prefersReducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function initTableOfContents() {
        var article = document.querySelector('[data-post-toc]');
        if (!article) {
            return;
        }

        var content = article.querySelector('.post-content');
        if (!content) {
            return;
        }

        var rawHeadings = Array.prototype.slice.call(content.querySelectorAll('h2, h3'));
        var headings = rawHeadings.filter(function (heading) {
            if (heading.closest('.footnotes-section')) {
                return false;
            }
            return heading.textContent.trim().length > 0;
        });

        if (headings.length < 2) {
            return;
        }

        var usedIds = {};
        headings.forEach(function (heading, idx) {
            var id = heading.getAttribute('id');
            if (!id || usedIds[id]) {
                var base = 'section-' + (idx + 1);
                id = base;
                var n = 2;
                while (document.getElementById(id) || usedIds[id]) {
                    id = base + '-' + n;
                    n += 1;
                }
                heading.setAttribute('id', id);
            }
            usedIds[id] = true;
        });

        function buildList(onItemClick) {
            var ul = document.createElement('ul');
            ul.className = 'post-toc-list';

            headings.forEach(function (heading) {
                var id = heading.getAttribute('id');
                var level = heading.tagName.toLowerCase();
                var li = document.createElement('li');
                li.className = 'post-toc-item post-toc-item--' + level;

                var a = document.createElement('a');
                a.className = 'post-toc-link';
                a.href = '#' + id;
                a.textContent = heading.textContent.trim();
                a.setAttribute('data-toc-target', id);

                a.addEventListener('click', function (e) {
                    var target = document.getElementById(id);
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({
                            behavior: prefersReducedMotion ? 'auto' : 'smooth',
                            block: 'start'
                        });
                        if (window.history && window.history.replaceState) {
                            window.history.replaceState(null, '', '#' + id);
                        }
                    }
                    if (typeof onItemClick === 'function') {
                        onItemClick();
                    }
                });

                li.appendChild(a);
                ul.appendChild(li);
            });

            return ul;
        }

        // Mobile / Tablet collapsible TOC at top of post content
        var mobileToc = document.createElement('details');
        mobileToc.className = 'post-toc-mobile';
        var summary = document.createElement('summary');
        summary.textContent = '文章目录';
        mobileToc.appendChild(summary);
        mobileToc.appendChild(buildList(function () {
            mobileToc.open = false;
        }));
        content.parentNode.insertBefore(mobileToc, content);

        // Desktop floating TOC beside main article card
        var aside = document.createElement('aside');
        aside.className = 'post-toc-aside';
        aside.setAttribute('aria-label', '文章目录');
        var asideTitle = document.createElement('div');
        asideTitle.className = 'post-toc-title';
        asideTitle.textContent = '目录';
        aside.appendChild(asideTitle);
        aside.appendChild(buildList());
        article.parentNode.insertBefore(aside, article.nextSibling);

        var desktopLinks = Array.prototype.slice.call(aside.querySelectorAll('.post-toc-link'));
        var activeId = '';
        var ticking = false;

        function setActive(id) {
            if (activeId === id) {
                return;
            }
            activeId = id;
            desktopLinks.forEach(function (link) {
                var isMatch = link.getAttribute('data-toc-target') === id;
                link.classList.toggle('is-active', isMatch);
                if (isMatch && aside.offsetParent !== null) {
                    var linkTop = link.offsetTop;
                    var linkBottom = linkTop + link.offsetHeight;
                    if (linkTop < aside.scrollTop || linkBottom > aside.scrollTop + aside.clientHeight) {
                        aside.scrollTop = Math.max(0, linkTop - aside.clientHeight / 2);
                    }
                }
            });
        }

        function updateActiveHeading() {
            ticking = false;
            var offset = 110;
            var currentId = headings[0].getAttribute('id');

            for (var i = 0; i < headings.length; i += 1) {
                var rect = headings[i].getBoundingClientRect();
                if (rect.top <= offset) {
                    currentId = headings[i].getAttribute('id');
                } else {
                    break;
                }
            }
            setActive(currentId);
        }

        window.addEventListener('scroll', function () {
            if (!ticking) {
                ticking = true;
                window.requestAnimationFrame(updateActiveHeading);
            }
        }, { passive: true });

        updateActiveHeading();
    }

    function copyText(text) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            return navigator.clipboard.writeText(text);
        }
        return new Promise(function (resolve, reject) {
            var ta = document.createElement('textarea');
            ta.value = text;
            ta.setAttribute('readonly', '');
            ta.style.position = 'fixed';
            ta.style.opacity = '0';
            document.body.appendChild(ta);
            ta.select();
            try {
                var ok = document.execCommand('copy');
                document.body.removeChild(ta);
                if (ok) {
                    resolve();
                } else {
                    reject(new Error('Copy command failed'));
                }
            } catch (err) {
                document.body.removeChild(ta);
                reject(err);
            }
        });
    }

    function extractLanguage(codeEl, preEl) {
        var combined = (codeEl.className || '') + ' ' + (preEl.className || '');
        var match = combined.match(/(?:^|\s)(?:language-|lang-)([a-zA-Z0-9_+-]+)/);
        return match ? match[1] : '';
    }

    function initCodeBlocks() {
        var preBlocks = Array.prototype.slice.call(document.querySelectorAll('.post-content pre, .page-content pre'));
        if (preBlocks.length === 0) {
            return;
        }

        preBlocks.forEach(function (pre) {
            if (pre.closest('.code-block-wrapper')) {
                return;
            }

            var codeEl = pre.querySelector('code');
            if (!codeEl) {
                codeEl = document.createElement('code');
                codeEl.textContent = pre.textContent;
                pre.textContent = '';
                pre.appendChild(codeEl);
            }

            var lang = extractLanguage(codeEl, pre);

            if (window.hljs && typeof window.hljs.highlightElement === 'function') {
                try {
                    window.hljs.highlightElement(codeEl);
                    if (!lang && codeEl.result && codeEl.result.language) {
                        lang = codeEl.result.language;
                    }
                } catch (e) {
                    // Keep plain code styling on highlight errors
                }
            }

            var wrapper = document.createElement('div');
            wrapper.className = 'code-block-wrapper';

            var header = document.createElement('div');
            header.className = 'code-block-header';

            var langBadge = document.createElement('span');
            langBadge.className = 'code-block-lang';
            langBadge.textContent = lang ? lang.toUpperCase() : 'CODE';

            var copyBtn = document.createElement('button');
            copyBtn.type = 'button';
            copyBtn.className = 'code-copy-btn';
            copyBtn.textContent = '复制';
            copyBtn.setAttribute('aria-label', '复制代码');

            var resetTimer = null;
            copyBtn.addEventListener('click', function () {
                copyText(codeEl.textContent || '').then(function () {
                    copyBtn.textContent = '已复制';
                    copyBtn.classList.add('is-copied');
                    if (resetTimer) {
                        window.clearTimeout(resetTimer);
                    }
                    resetTimer = window.setTimeout(function () {
                        copyBtn.textContent = '复制';
                        copyBtn.classList.remove('is-copied');
                    }, 2000);
                }).catch(function () {
                    copyBtn.textContent = '复制失败';
                });
            });

            header.appendChild(langBadge);
            header.appendChild(copyBtn);

            pre.parentNode.insertBefore(wrapper, pre);
            wrapper.appendChild(header);
            wrapper.appendChild(pre);
        });
    }

    function initLightbox() {
        var candidates = Array.prototype.slice.call(
            document.querySelectorAll('.post-content img, .page-content img, .sc-detail-shot .sc-shot-img')
        );
        var imageExtRe = /\.(jpe?g|png|gif|webp|avif|svg)(\?.*)?$/i;
        var items = [];

        candidates.forEach(function (img) {
            if (img.hasAttribute('data-no-lightbox') || img.classList.contains('emoji')) {
                return;
            }

            var anchor = img.closest('a');
            var src = img.currentSrc || img.getAttribute('src') || '';
            if (anchor) {
                var href = (anchor.getAttribute('href') || '').trim();
                if (!imageExtRe.test(href)) {
                    return;
                }
                src = href;
            }

            if (!src) {
                return;
            }

            var figure = img.closest('figure');
            var captionEl = figure ? figure.querySelector('figcaption') : null;
            var caption = captionEl ? captionEl.textContent.trim() : (img.getAttribute('alt') || '').trim();

            var index = items.length;
            items.push({
                src: src,
                alt: img.getAttribute('alt') || caption || '',
                caption: caption,
                trigger: anchor || img
            });

            img.classList.add('subo4-lightbox-target');
            (anchor || img).addEventListener('click', function (e) {
                e.preventDefault();
                openLightbox(index);
            });
        });

        if (items.length === 0) {
            return;
        }

        var overlay = null;
        var lbImg = null;
        var lbCaption = null;
        var lbCounter = null;
        var prevBtn = null;
        var nextBtn = null;
        var closeBtn = null;
        var currentIndex = 0;
        var lastTrigger = null;
        var prevOverflow = '';

        function buildOverlay() {
            overlay = document.createElement('div');
            overlay.className = 'subo4-lightbox';
            overlay.setAttribute('role', 'dialog');
            overlay.setAttribute('aria-modal', 'true');
            overlay.setAttribute('aria-label', '图片预览');
            overlay.hidden = true;

            closeBtn = document.createElement('button');
            closeBtn.type = 'button';
            closeBtn.className = 'subo4-lb-close';
            closeBtn.setAttribute('aria-label', '关闭预览');
            closeBtn.innerHTML = '&times;';

            prevBtn = document.createElement('button');
            prevBtn.type = 'button';
            prevBtn.className = 'subo4-lb-nav subo4-lb-prev';
            prevBtn.setAttribute('aria-label', '上一张');
            prevBtn.innerHTML = '&lsaquo;';

            nextBtn = document.createElement('button');
            nextBtn.type = 'button';
            nextBtn.className = 'subo4-lb-nav subo4-lb-next';
            nextBtn.setAttribute('aria-label', '下一张');
            nextBtn.innerHTML = '&rsaquo;';

            var stage = document.createElement('div');
            stage.className = 'subo4-lb-stage';

            lbImg = document.createElement('img');
            lbImg.className = 'subo4-lb-img';
            lbImg.alt = '';

            var meta = document.createElement('div');
            meta.className = 'subo4-lb-meta';

            lbCaption = document.createElement('span');
            lbCaption.className = 'subo4-lb-caption';

            lbCounter = document.createElement('span');
            lbCounter.className = 'subo4-lb-counter';

            meta.appendChild(lbCaption);
            meta.appendChild(lbCounter);
            stage.appendChild(lbImg);
            stage.appendChild(meta);

            overlay.appendChild(closeBtn);
            overlay.appendChild(prevBtn);
            overlay.appendChild(stage);
            overlay.appendChild(nextBtn);
            document.body.appendChild(overlay);

            closeBtn.addEventListener('click', closeLightbox);
            prevBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                showItem((currentIndex - 1 + items.length) % items.length);
            });
            nextBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                showItem((currentIndex + 1) % items.length);
            });
            overlay.addEventListener('click', function (e) {
                if (e.target === overlay) {
                    closeLightbox();
                }
            });
            document.addEventListener('keydown', function (e) {
                if (!overlay || overlay.hidden) {
                    return;
                }
                if (e.key === 'Escape') {
                    closeLightbox();
                } else if (e.key === 'ArrowLeft' && items.length > 1) {
                    showItem((currentIndex - 1 + items.length) % items.length);
                } else if (e.key === 'ArrowRight' && items.length > 1) {
                    showItem((currentIndex + 1) % items.length);
                }
            });
        }

        function showItem(idx) {
            currentIndex = idx;
            var item = items[idx];
            lbImg.src = item.src;
            lbImg.alt = item.alt;
            lbCaption.textContent = item.caption;
            lbCaption.hidden = !item.caption;
            lbCounter.textContent = (idx + 1) + ' / ' + items.length;
            var hasMulti = items.length > 1;
            prevBtn.hidden = !hasMulti;
            nextBtn.hidden = !hasMulti;
        }

        function openLightbox(idx) {
            if (!overlay) {
                buildOverlay();
            }
            lastTrigger = items[idx].trigger || document.activeElement;
            showItem(idx);
            prevOverflow = document.body.style.overflow;
            document.body.style.overflow = 'hidden';
            overlay.hidden = false;
            window.requestAnimationFrame(function () {
                overlay.classList.add('is-open');
                closeBtn.focus();
            });
        }

        function closeLightbox() {
            if (!overlay || overlay.hidden) {
                return;
            }
            overlay.classList.remove('is-open');
            overlay.hidden = true;
            document.body.style.overflow = prevOverflow;
            if (lastTrigger && typeof lastTrigger.focus === 'function') {
                lastTrigger.focus();
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        initTableOfContents();
        initCodeBlocks();
        initLightbox();
    });
})();
