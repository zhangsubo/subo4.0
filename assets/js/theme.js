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

        // Add class to navbar on scroll
        const navbar = document.querySelector('.navbar-custom');
        if (navbar) {
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    navbar.classList.add('navbar-scrolled');
                } else {
                    navbar.classList.remove('navbar-scrolled');
                }
            });
        }

        // Active menu item highlighting
        const currentUrl = window.location.href;
        document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
            if (link.href === currentUrl) {
                link.classList.add('active');
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
        }
    });

})();