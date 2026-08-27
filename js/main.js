/**
 * Audiomania Eventos — Animaciones y JS interactivo
 * - 3 secciones hero con parallax y scroll suave
 * - Scroll reveal con IntersectionObserver
 * - Header scroll shrink
 * - Scroll progress bar
 * - Back to top
 * - Disco canvas (3D)
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

    // === DISCO CANVAS (3D particles) ===
    function initDiscoCanvas() {
        var canvas = $('#am-disco-canvas');
        if (!canvas) return;

        var ctx = canvas.getContext('2d');
        var w, h;
        var particles = [];
        var animId;

        function resize() {
            w = canvas.width = window.innerWidth;
            h = canvas.height = window.innerHeight;
        }

        function createParticles() {
            particles = [];
            var count = Math.min(80, Math.floor(w * h / 15000));
            for (var i = 0; i < count; i++) {
                particles.push({
                    x: Math.random() * w,
                    y: Math.random() * h,
                    vx: (Math.random() - 0.5) * 0.5,
                    vy: (Math.random() - 0.5) * 0.5,
                    r: Math.random() * 2 + 0.5,
                    alpha: Math.random() * 0.5 + 0.1,
                    color: ['#4d7cff', '#a855f7', '#ec4899', '#22d3ee'][Math.floor(Math.random() * 4)]
                });
            }
        }

        function draw() {
            ctx.clearRect(0, 0, w, h);

            for (var i = 0; i < particles.length; i++) {
                var p = particles[i];
                p.x += p.vx;
                p.y += p.vy;
                if (p.x < 0) p.x = w;
                if (p.x > w) p.x = 0;
                if (p.y < 0) p.y = h;
                if (p.y > h) p.y = 0;

                ctx.beginPath();
                ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                ctx.fillStyle = p.color;
                ctx.globalAlpha = p.alpha;
                ctx.fill();

                for (var j = i + 1; j < particles.length; j++) {
                    var q = particles[j];
                    var dx = p.x - q.x;
                    var dy = p.y - q.y;
                    var dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < 120) {
                        ctx.beginPath();
                        ctx.moveTo(p.x, p.y);
                        ctx.lineTo(q.x, q.y);
                        ctx.strokeStyle = p.color;
                        ctx.globalAlpha = (1 - dist / 120) * 0.15;
                        ctx.lineWidth = 0.5;
                        ctx.stroke();
                    }
                }
            }
            ctx.globalAlpha = 1;
            animId = requestAnimationFrame(draw);
        }

        resize();
        createParticles();
        draw();

        window.addEventListener('resize', function() {
            resize();
            createParticles();
        });

        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (!entry.isIntersecting) {
                    cancelAnimationFrame(animId);
                } else {
                    draw();
                }
            });
        }, { threshold: 0 });
        observer.observe(document.body);
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
            initDiscoCanvas();
            initNavSmoothScroll();
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
