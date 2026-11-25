/**
 * Theme JavaScript
 *
 * @package SUBO4_Classic_Theme
 */

(function() {
    'use strict';

    // DOM Ready
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Initialize Bootstrap popovers
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const targetId = this.getAttribute('href');
                if (targetId !== '#' && targetId !== 'javascript:void(0);') {
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        e.preventDefault();
                        targetElement.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });

        // Scroll handling: navbar state & back-to-top visibility
        const navbar = document.querySelector('.navbar-custom');
        const backToTopBtn = document.getElementById('backToTop');
        const prefersReducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (navbar || backToTopBtn) {
            const onScroll = function() {
                const y = window.scrollY;
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
            backToTopBtn.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: prefersReducedMotion ? 'auto' : 'smooth'
                });
            });
        }

        // Active menu item highlighting (normalize trailing slash and hash)
        const normalizePath = function(urlStr) {
            try {
                const u = new URL(urlStr, window.location.origin);
                const path = u.pathname.replace(/\/+$/, '') || '/';
                return u.origin + path;
            } catch (e) {
                return urlStr;
            }
        };
        const currentPath = normalizePath(window.location.href);
        document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
            if (normalizePath(link.href) === currentPath) {
                link.classList.add('active');
                link.setAttribute('aria-current', 'page');
            }
        });

        // Bootstrap 5 Toast for WeChat QR Codes
        const qrToast = document.getElementById('qrToast');
        const toastBackdrop = document.getElementById('toastBackdrop');
        const wechatTriggers = document.querySelectorAll('.wechat-qr-trigger');
        const toastTitle = document.getElementById('toastTitle');
        const toastQrImage = document.getElementById('toastQrImage');

        if (qrToast && wechatTriggers.length > 0) {
            const toast = new bootstrap.Toast(qrToast);

            // Open toast when clicking WeChat QR trigger
            wechatTriggers.forEach(trigger => {
                trigger.addEventListener('click', function(e) {
                    e.preventDefault();

                    const qrUrl = this.getAttribute('data-qr');
                    const title = this.getAttribute('data-title');

                    if (toastQrImage && qrUrl) {
                        toastQrImage.src = qrUrl;
                    }
                    if (toastTitle && title) {
                        toastTitle.textContent = title;
                    }

                    // Show backdrop
                    if (toastBackdrop) {
                        toastBackdrop.classList.add('show');
                    }

                    // Show toast
                    toast.show();
                });
            });

            // Hide backdrop when toast is hidden
            qrToast.addEventListener('hidden.bs.toast', function() {
                if (toastBackdrop) {
                    toastBackdrop.classList.remove('show');
                }
            });

            // Close when clicking backdrop
            if (toastBackdrop) {
                toastBackdrop.addEventListener('click', function() {
                    toast.hide();
                });
            }

            // Close when pressing Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && qrToast.classList.contains('show')) {
                    toast.hide();
                }
            });
        }
    });

})();