/**
 * Theme JavaScript (Pure Vanilla JS)
 *
 * @package SUBO4_Classic_Theme
 */

(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        var prefersReducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // 1. Mobile navigation toggle
        var navToggle = document.querySelector('[data-nav-toggle]');
        var navCollapse = document.getElementById('navbarNav');
        if (navToggle && navCollapse) {
            var closeNav = function () {
                navCollapse.classList.remove('is-open');
                navToggle.setAttribute('aria-expanded', 'false');
            };

            navToggle.addEventListener('click', function () {
                var isOpen = navCollapse.classList.toggle('is-open');
                navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });

            navCollapse.querySelectorAll('.nav-link').forEach(function (link) {
                link.addEventListener('click', closeNav);
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && navCollapse.classList.contains('is-open')) {
                    closeNav();
                }
            });
        }

        // 2. Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
            anchor.addEventListener('click', function (e) {
                var targetId = this.getAttribute('href');
                if (targetId && targetId.length > 1) {
                    var targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        e.preventDefault();
                        targetElement.scrollIntoView({
                            behavior: prefersReducedMotion ? 'auto' : 'smooth'
                        });
                    }
                }
            });
        });

        // 3. Scroll handling: navbar state & back-to-top visibility
        var navbar = document.querySelector('.navbar-custom');
        var backToTopBtn = document.getElementById('backToTop');
        if (navbar || backToTopBtn) {
            var onScroll = function () {
                var y = window.scrollY;
                if (navbar) {
                    navbar.classList.toggle('navbar-scrolled', y > 50);
                }
                if (backToTopBtn) {
                    backToTopBtn.hidden = y <= 400;
                }
            };
            window.addEventListener('scroll', onScroll, { passive: true });
            onScroll();
        }

        if (backToTopBtn) {
            backToTopBtn.addEventListener('click', function () {
                window.scrollTo({
                    top: 0,
                    behavior: prefersReducedMotion ? 'auto' : 'smooth'
                });
            });
        }

        // 4. Active menu item highlighting
        var normalizePath = function (urlStr) {
            try {
                var u = new URL(urlStr, window.location.origin);
                var path = u.pathname.replace(/\/+$/, '') || '/';
                return u.origin + path;
            } catch (e) {
                return urlStr;
            }
        };
        var currentPath = normalizePath(window.location.href);
        document.querySelectorAll('.navbar-nav .nav-link').forEach(function (link) {
            if (normalizePath(link.href) === currentPath) {
                link.classList.add('active');
                link.setAttribute('aria-current', 'page');
            }
        });

        // 5. Native QR code modal for WeChat triggers
        var qrModal = document.getElementById('qrModal');
        var qrBackdrop = document.getElementById('qrModalBackdrop');
        var qrClose = document.getElementById('qrModalClose');
        var qrTitle = document.getElementById('qrModalTitle');
        var qrImage = document.getElementById('qrModalImage');
        var wechatTriggers = document.querySelectorAll('.wechat-qr-trigger');

        if (qrModal && qrBackdrop && wechatTriggers.length > 0) {
            var openQrModal = function (url, title) {
                if (qrImage && url) {
                    qrImage.src = url;
                }
                if (qrTitle && title) {
                    qrTitle.textContent = title;
                }
                qrBackdrop.hidden = false;
                qrModal.hidden = false;
            };

            var closeQrModal = function () {
                qrModal.hidden = true;
                qrBackdrop.hidden = true;
            };

            wechatTriggers.forEach(function (trigger) {
                trigger.addEventListener('click', function () {
                    openQrModal(
                        this.getAttribute('data-qr') || '',
                        this.getAttribute('data-title') || ''
                    );
                });
            });

            if (qrClose) {
                qrClose.addEventListener('click', closeQrModal);
            }
            qrBackdrop.addEventListener('click', closeQrModal);

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !qrModal.hidden) {
                    closeQrModal();
                }
            });
        }
    });
})();
