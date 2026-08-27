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
        const carousel = $('.am-hero-carousel');
        if (!carousel) return;

        const slides = $$('.am-carousel-slide', carousel);
        const dots = $$('.am-carousel-dot', carousel);
        const progress = $('.am-carousel-progress', carousel);
        if (!slides.length) return;

        const INTERVAL = 6000;
        let current = 0;
        let timer = null;
        let progressTimer = null;
        let progressStart = 0;

        function goTo(index) {
            slides[current].classList.remove('active');
            dots.forEach(d => d.classList.remove('active'));

            current = index;
            slides[current].classList.add('active');
            dots[current].classList.add('active');

            // Reset progress
            progress.style.transition = 'none';
            progress.style.width = '0%';
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    progress.style.transition = `width ${INTERVAL}ms linear`;
                    progress.style.width = '100%';
                });
            });

            startTimer();
        }

        function startTimer() {
            clearTimeout(timer);
            progressStart = Date.now();
        }

        function nextSlide() {
            goTo((current + 1) % slides.length);
        }

        timer = setInterval(nextSlide, INTERVAL);

        // Progress animation via JS for accuracy
        let progressRAF;
        function animateProgress() {
            if (!progress) return;
            const elapsed = Date.now() - progressStart;
            const pct = Math.min((elapsed / INTERVAL) * 100, 100);
            if (!timer && pct >= 100) return;
            // Only update if within interval
            if (progressRAF) cancelAnimationFrame(progressRAF);
        }
        requestAnimationFrame(animateProgress);

        // Dot click
        dots.forEach((dot, i) => {
            dot.addEventListener('click', () => {
                clearInterval(timer);
                clearTimeout(timer);
                goTo(i);
            });
        });

        // Pause on hover
        carousel.addEventListener('mouseenter', () => {
            clearInterval(timer);
        });
        carousel.addEventListener('mouseleave', () => {
            timer = setInterval(nextSlide, INTERVAL);
        });

        // Touch/swipe
        let touchStartX = 0;
        carousel.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        carousel.addEventListener('touchend', (e) => {
            const diff = touchStartX - e.changedTouches[0].screenX;
            if (Math.abs(diff) > 50) {
                clearInterval(timer);
                goTo(diff > 0 ? Math.min(current + 1, slides.length - 1) : Math.max(current - 1, 0));
            }
        }, { passive: true });

        // Keyboard
        document.addEventListener('keydown', (e) => {
            if (!carousel.classList.contains('active')) return;
            if (e.key === 'ArrowRight') { clearInterval(timer); goTo((current + 1) % slides.length); }
            if (e.key === 'ArrowLeft') { clearInterval(timer); goTo((current - 1 + slides.length) % slides.length); }
        });

        // Init progress bar
        progress.style.transition = 'none';
        progress.style.width = '0%';
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                progress.style.transition = `width ${INTERVAL}ms linear`;
                progress.style.width = '100%';
            });
        });
    }

    // === SCROLL REVEAL ===
    function initScrollReveal() {
        const reveals = $$('.am-reveal, .am-reveal-stagger');
        if (!reveals.length) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('am-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.15,
            rootMargin: '0px 0px -60px 0px'
        });

        reveals.forEach(el => observer.observe(el));
    }

    // === HEADER SCROLL SHRINK ===
    function initHeaderShrink() {
        const header = $('.am-header');
        if (!header) return;

        let lastScroll = 0;
        const onScroll = () => {
            const scrollY = window.scrollY;
            if (scrollY > 80) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
            lastScroll = scrollY;
        };

        // Throttle
        let ticking = false;
        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(() => {
                    onScroll();
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });

        onScroll();
    }

    // === SCROLL PROGRESS BAR ===
    function initScrollProgress() {
        const bar = $('.scroll-progress');
        if (!bar) return;

        let ticking = false;
        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(() => {
                    const scrollTop = window.scrollY;
                    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
                    const pct = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
                    bar.style.width = pct + '%';
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });
    }

    // === BACK TO TOP ===
    function initBackToTop() {
        const btn = $('.am-back-to-top');
        if (!btn) return;

        window.addEventListener('scroll', () => {
            if (window.scrollY > 600) {
                btn.classList.add('am-visible');
            } else {
                btn.classList.remove('am-visible');
            }
        }, { passive: true });

        btn.addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // === DISCO CANVAS (3D particles) ===
    function initDiscoCanvas() {
        const canvas = $('#am-disco-canvas');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        let w, h;
        let particles = [];
        let animId;

        function resize() {
            w = canvas.width = window.innerWidth;
            h = canvas.height = window.innerHeight;
        }

        function createParticles() {
            particles = [];
            const count = Math.min(80, Math.floor(w * h / 15000));
            for (let i = 0; i < count; i++) {
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

            particles.forEach((p, i) => {
                // Move
                p.x += p.vx;
                p.y += p.vy;

                // Wrap
                if (p.x < 0) p.x = w;
                if (p.x > w) p.x = 0;
                if (p.y < 0) p.y = h;
                if (p.y > h) p.y = 0;

                // Draw particle
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                ctx.fillStyle = p.color;
                ctx.globalAlpha = p.alpha;
                ctx.fill();

                // Connect nearby
                for (let j = i + 1; j < particles.length; j++) {
                    const q = particles[j];
                    const dx = p.x - q.x;
                    const dy = p.y - q.y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
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
            });

            ctx.globalAlpha = 1;
            animId = requestAnimationFrame(draw);
        }

        resize();
        createParticles();
        draw();

        window.addEventListener('resize', () => {
            resize();
            createParticles();
        });

        // Pause when not visible
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
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
        const slides = $$('.am-carousel-slide.active');
        if (!slides.length) return;

        let ticking = false;
        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(() => {
                    const scrollY = window.scrollY;
                    slides.forEach(slide => {
                        const rect = slide.getBoundingClientRect();
                        if (rect.bottom > 0 && rect.top < window.innerHeight) {
                            const speed = 0.3;
                            const yOffset = scrollY * speed;
                            const img = slide.querySelector('img') || slide;
                            if (slide.style.backgroundImage) {
                                const bg = slide.style.backgroundImage;
                                const match = bg.match(/url\(['"]?([^'"]+)['"]?\)/);
                                if (match) {
                                    slide.style.backgroundPosition = `center ${50 + (yOffset * 0.02)}%`;
                                }
                            }
                        }
                    });
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });
    }

    // === GALLERY FILTERS ===
    function initGalleryFilters() {
        const grid = $('#am-gallery-grid');
        if (!grid) return;

        const items = $$('.am-gallery-item', grid);
        const buttons = $$('.am-filter-btn');

        buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                const filter = btn.getAttribute('data-filter');

                // Update active button
                buttons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                // Filter items
                items.forEach(item => {
                    const cat = item.getAttribute('data-category');
                    if (filter === 'all' || cat === filter) {
                        item.style.display = '';
                        item.style.opacity = '0';
                        item.style.transform = 'scale(0.95)';
                        requestAnimationFrame(() => {
                            requestAnimationFrame(() => {
                                item.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                                item.style.opacity = '1';
                                item.style.transform = 'scale(1)';
                            });
                        });
                    } else {
                        item.style.opacity = '0';
                        item.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            item.style.display = 'none';
                        }, 400);
                    }
                });
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
            initCarousel();
            initScrollReveal();
            initHeaderShrink();
            initScrollProgress();
            initBackToTop();
            initDiscoCanvas();
            initParallax();
            initGalleryFilters();
        }
    }

    // Run immediately if possible
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
