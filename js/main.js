/**
 * Audiomania Eventos — Animaciones y JS interactivo
 * - 3 secciones hero con parallax y scroll suave
 * - Scroll reveal con IntersectionObserver
 * - Header scroll shrink
 * - Scroll progress bar
 * - Back to top
 * - 3D Soundwave Terrain (Three.js module)
 */

(function () {
    'use strict';

    // === UTILITY ===
    function $(sel, ctx) { return (ctx || document).querySelector(sel); }
    function $$(sel, ctx) { return Array.from((ctx || document).querySelectorAll(sel)); }

    // === SMOOTH SCROLL (scroll indicators) ===
    function initSmoothScroll() {
        // Scroll indicators within hero sections
        $$('.am-scroll-indicator').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                var href = this.getAttribute('href');
                if (href && href.charAt(0) === '#') {
                    var target = document.querySelector(href);
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            });
        });
    }

    // === HERO PARALLAX ===
    function initHeroParallax() {
        var sections = $$('.am-hero-section');
        if (!sections.length) return;

        var ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(function() {
                    var scrollY = window.scrollY;
                    sections.forEach(function(section) {
                        var bg = $('.am-hero-bg', section);
                        if (!bg) return;
                        var rect = section.getBoundingClientRect();
                        if (rect.bottom > 0 && rect.top < window.innerHeight) {
                            var yOffset = scrollY - (section.offsetTop - window.innerHeight);
                            bg.style.transform = 'translateY(' + (yOffset * 0.3) + 'px) scale(1.1)';
                        }
                    });
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });
    }

    // === HERO SECTION REVEAL ===
    function initHeroReveal() {
        var sections = $$('.am-hero-section');
        if (!sections.length) return;

        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('am-hero-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.2,
            rootMargin: '-80px 0px -60px 0px'
        });

        sections.forEach(function(section) {
            section.classList.add('am-hero-reveal');
            observer.observe(section);
        });
    }

    // === SCROLL REVEAL ===
    function initScrollReveal() {
        var reveals = $$('.am-reveal, .am-reveal-stagger');
        if (!reveals.length) return;

        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('am-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.15,
            rootMargin: '0px 0px -60px 0px'
        });

        reveals.forEach(function(el) { observer.observe(el); });
    }

    // === HEADER SCROLL SHRINK ===
    function initHeaderShrink() {
        var header = $('.am-header');
        if (!header) return;

        var ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(function() {
                    var scrollY = window.scrollY;
                    if (scrollY > 80) {
                        header.classList.add('scrolled');
                    } else {
                        header.classList.remove('scrolled');
                    }
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });

        if (window.scrollY > 80) {
            header.classList.add('scrolled');
        }
    }

    // === SCROLL PROGRESS BAR ===
    function initScrollProgress() {
        var bar = $('.scroll-progress');
        if (!bar) return;

        var ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(function() {
                    var scrollTop = window.scrollY;
                    var docHeight = document.documentElement.scrollHeight - window.innerHeight;
                    var pct = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
                    bar.style.width = pct + '%';
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });
    }

    // === BACK TO TOP ===
    function initBackToTop() {
        var btn = $('.am-back-to-top');
        if (!btn) return;

        window.addEventListener('scroll', function() {
            if (window.scrollY > 600) {
                btn.classList.add('am-visible');
            } else {
                btn.classList.remove('am-visible');
            }
        }, { passive: true });

        btn.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // === NAV LINK SMOOTH SCROLL ===
    function initNavSmoothScroll() {
        $$('a[href^="#"]').forEach(function(link) {
            link.addEventListener('click', function(e) {
                var href = this.getAttribute('href');
                if (href && href.charAt(0) === '#' && href.length > 1) {
                    var target = document.querySelector(href);
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            });
        });
    }

    // === INIT ===
    function init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', run);
        } else {
            run();
        }

        function run() {
            initSmoothScroll();
            initHeroParallax();
            initHeroReveal();
            initScrollReveal();
            initHeaderShrink();
            initScrollProgress();
            initBackToTop();
            initNavSmoothScroll();
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
