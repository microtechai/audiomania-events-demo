/**
 * Audiomania Eventos — Animaciones y JS interactivo
 * - Hero carousel con autoplay, transiciones y dots
 * - Scroll reveal con IntersectionObserver
 * - Parallax en hero slides
 * - Header scroll shrink
 * - Scroll progress bar
 * - Back to top
 * - Disco canvas (3D)
 */

(function () {
    'use strict';

    // === UTILITY ===
    function $ (sel, ctx) { return (ctx || document).querySelector(sel); }
    function $$(sel, ctx) { return Array.from((ctx || document).querySelectorAll(sel)); }

    // === HERO CAROUSEL ===
    function initCarousel() {
        var carousel = $('.am-hero-carousel');
        if (!carousel) return;

        var slides = $$('.am-carousel-slide', carousel);
        var dots = $$('.am-carousel-dot', carousel);
        var progress = $('.am-carousel-progress', carousel);
        if (!slides.length) return;

        var INTERVAL = 6000;
        var current = 0;
        var isTransitioning = false;
        var isHovering = false;
        var autoTimer = null;

        // ---- Reset progress bar via CSS ----
        function resetProgress() {
            if (!progress) return;
            progress.style.transition = 'none';
            progress.style.width = '0%';
            requestAnimationFrame(function() {
                requestAnimationFrame(function() {
                    progress.style.transition = 'width ' + INTERVAL + 'ms linear';
                    progress.style.width = '100%';
                });
            });
        }

        // ---- Go to slide (with transition lock, timer reset, progress reset) ----
        function goTo(index) {
            if (isTransitioning) return;
            if (index === current && slides[current].classList.contains('active')) return;

            isTransitioning = true;

            slides[current].classList.remove('active');
            dots.forEach(function(d) { d.classList.remove('active'); });

            current = index;

            slides[current].classList.add('active');
            dots[current].classList.add('active');

            resetProgress();
            restartAutoTimer();

            setTimeout(function() {
                isTransitioning = false;
            }, 1200);
        }

        // ---- Next slide ----
        function nextSlide() {
            if (isTransitioning) return;
            var next = (current + 1) % slides.length;
            goTo(next);
        }

        // ---- Auto timer with proper restart ----
        function restartAutoTimer() {
            if (autoTimer) {
                clearInterval(autoTimer);
                autoTimer = null;
            }
            if (!isHovering) {
                autoTimer = setInterval(function() {
                    if (!isHovering) {
                        nextSlide();
                    }
                }, INTERVAL);
            }
        }

        restartAutoTimer();
        resetProgress();

        // ---- Dot click (with stopPropagation to avoid swipe) ----
        dots.forEach(function(dot, i) {
            dot.addEventListener('click', function(e) {
                e.stopPropagation();
                goTo(i);
            });
        });

        // ---- Pause on hover ----
        carousel.addEventListener('mouseenter', function() {
            isHovering = true;
            if (autoTimer) {
                clearInterval(autoTimer);
                autoTimer = null;
            }
        });
        carousel.addEventListener('mouseleave', function() {
            isHovering = false;
            restartAutoTimer();
        });

        // ---- Touch/swipe (dots excluded via stopPropagation) ----
        var touchStartX = 0;
        var touchStartY = 0;

        carousel.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].clientX;
            touchStartY = e.changedTouches[0].clientY;
        }, { passive: true });

        carousel.addEventListener('touchend', function(e) {
            var touchEndX = e.changedTouches[0].clientX;
            var touchEndY = e.changedTouches[0].clientY;
            var diffX = touchStartX - touchEndX;
            var diffY = Math.abs(touchStartY - touchEndY);

            // Only horizontal swipe (ignore vertical movement and dot clicks)
            if (Math.abs(diffX) > 50 && Math.abs(diffX) > diffY) {
                e.stopPropagation();
                if (!isTransitioning) {
                    if (diffX > 0) {
                        goTo((current + 1) % slides.length);
                    } else {
                        goTo((current - 1 + slides.length) % slides.length);
                    }
                }
            }
        }, { passive: true });

        // ---- Keyboard ----
        document.addEventListener('keydown', function(e) {
            var carouselRect = carousel.getBoundingClientRect();
            if (carouselRect.top < 0 || carouselRect.bottom > window.innerHeight) return;

            if (e.key === 'ArrowRight') {
                clearInterval(autoTimer);
                isHovering = true;
                goTo((current + 1) % slides.length);
                setTimeout(function() { isHovering = false; restartAutoTimer(); }, 3000);
            }
            if (e.key === 'ArrowLeft') {
                clearInterval(autoTimer);
                isHovering = true;
                goTo((current - 1 + slides.length) % slides.length);
                setTimeout(function() { isHovering = false; restartAutoTimer(); }, 3000);
            }
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

    // === PARALLAX ON HERO SLIDES ===
    function initParallax() {
        var slides = $$('.am-carousel-slide');
        if (!slides.length) return;

        var ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(function() {
                    var scrollY = window.scrollY;
                    slides.forEach(function(slide) {
                        var rect = slide.getBoundingClientRect();
                        if (rect.bottom > 0 && rect.top < window.innerHeight) {
                            var yOffset = scrollY * 0.15;
                            if (slide.style.backgroundImage) {
                                slide.style.backgroundPosition = 'center ' + (50 + yOffset * 0.02) + '%';
                            }
                        }
                    });
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });
    }

    // === INIT ===
    function init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', run);
        } else {
            run();
        }

        function run() {
            initCarousel();
            initScrollReveal();
            initHeaderShrink();
            initScrollProgress();
            initBackToTop();
            initDiscoCanvas();
            initParallax();
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
