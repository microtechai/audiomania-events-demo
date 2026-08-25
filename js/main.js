/**
 * Audiomania Eventos — main.js
 * 
 * Animación 3D disco/fiesta de fondo + menú móvil.
 * Usa Canvas 2D con partículas, conos de luz rotativos y reflejos.
 */
(function() {
    'use strict';

    /* ======================================================
       1. DISCO 3D BACKGROUND
       ====================================================== */
    function initDisco() {
        var canvas = document.getElementById('am-disco-canvas');
        if (!canvas) return;

        var ctx = canvas.getContext('2d');
        var W, H;
        var time = 0;

        /* Paleta de luces de discoteca */
        var lights = [
            { h: 220, s: 80, l: 60 },  // Azul neon
            { h: 280, s: 80, l: 60 },  // Morado neon
            { h: 330, s: 80, l: 60 },  // Rosa neon
            { h: 170, s: 80, l: 55 },  // Cyan neon
            { h: 140, s: 70, l: 55 },  // Verde neon
        ];

        /* Conos de luz */
        var beamCount = 8;
        var beams = [];
        for (var i = 0; i < beamCount; i++) {
            beams.push({
                angle: (Math.PI * 2 / beamCount) * i,
                speed: 0.003 + Math.random() * 0.004,
                width: 0.15 + Math.random() * 0.1,
                lightIndex: i % lights.length,
                pulse: Math.random() * Math.PI * 2,
                pulseSpeed: 0.02 + Math.random() * 0.02,
            });
        }

        /* Partículas flotantes */
        var particles = [];
        var particleCount = 60;
        for (var i = 0; i < particleCount; i++) {
            particles.push({
                x: Math.random() * 2000,
                y: Math.random() * 1000,
                size: 1 + Math.random() * 3,
                speedX: (Math.random() - 0.5) * 0.5,
                speedY: -0.2 - Math.random() * 0.5,
                opacity: 0.2 + Math.random() * 0.6,
                lightIndex: Math.floor(Math.random() * lights.length),
            });
        }

        function resize() {
            W = canvas.width = window.innerWidth;
            H = canvas.height = window.innerHeight;
        }
        resize();
        window.addEventListener('resize', resize);

        function hsl(l, s, h, a) {
            return 'hsla(' + h + ',' + s + '%,' + l + '%,' + a + ')';
        }

        function drawBeam(beam, t) {
            var cx = W * 0.5;
            var cy = 0;
            var length = Math.max(W, H) * 1.2;
            var a = beam.angle + Math.sin(t * beam.speed) * 0.5;
            var lx = Math.cos(a) * length;
            var ly = Math.sin(a) * length;
            var lw = beam.width * W * 0.15;

            var pulse = 0.3 + 0.3 * Math.sin(t * beam.pulseSpeed + beam.pulse);
            var col = lights[beam.lightIndex];

            var grad = ctx.createLinearGradient(cx, cy, lx, ly);
            grad.addColorStop(0, hsl(col.l + 20, col.s, col.h, 0.3 * pulse));
            grad.addColorStop(0.3, hsl(col.l, col.s, col.h, 0.15 * pulse));
            grad.addColorStop(1, hsl(col.l, col.s, col.h, 0));

            ctx.save();
            ctx.beginPath();
            ctx.moveTo(cx, cy);
            ctx.lineTo(lx - lw, ly - lw);
            ctx.lineTo(lx + lw, ly + lw);
            ctx.closePath();
            ctx.fillStyle = grad;
            ctx.fill();
            ctx.restore();
        }

        function drawParticles(t) {
            for (var i = 0; i < particles.length; i++) {
                var p = particles[i];
                p.x += p.speedX;
                p.y += p.speedY;
                if (p.y < -10) {
                    p.y = H + 10;
                    p.x = Math.random() * W;
                }
                if (p.x < -10) p.x = W + 10;
                if (p.x > W + 10) p.x = -10;

                var col = lights[p.lightIndex];
                var twinkle = 0.4 + 0.6 * Math.sin(t * 0.03 + i);

                ctx.beginPath();
                ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                ctx.fillStyle = hsl(70, 80, col.h, p.opacity * twinkle);
                ctx.fill();
            }
        }

        function drawReflectors(t) {
            var floorY = H * 0.85;
            ctx.save();
            for (var i = 0; i < 5; i++) {
                var x = W * 0.15 + (W * 0.7 / 4) * i;
                var col = lights[i % lights.length];
                var pulse = 0.15 + 0.1 * Math.sin(t * 0.02 + i * 1.2);
                var grad = ctx.createRadialGradient(x, floorY, 0, x, floorY, 120);
                grad.addColorStop(0, hsl(50, col.s, col.h, pulse));
                grad.addColorStop(1, hsl(50, col.s, col.h, 0));
                ctx.fillStyle = grad;
                ctx.fillRect(x - 120, floorY - 20, 240, 40);
            }
            ctx.restore();
        }

        function drawVignette() {
            var grad = ctx.createRadialGradient(W/2, H/2, H*0.3, W/2, H/2, Math.max(W, H)*0.8);
            grad.addColorStop(0, 'rgba(7,7,14,0)');
            grad.addColorStop(1, 'rgba(7,7,14,0.7)');
            ctx.fillStyle = grad;
            ctx.fillRect(0, 0, W, H);
        }

        function animate() {
            time++;
            ctx.clearRect(0, 0, W, H);

            // Capa 1: conos de luz
            ctx.globalCompositeOperation = 'screen';
            for (var i = 0; i < beams.length; i++) {
                drawBeam(beams[i], time);
            }

            // Capa 2: partículas
            drawParticles(time);

            // Capa 3: reflejos de suelo
            drawReflectors(time);

            // Capa 4: viñeta
            ctx.globalCompositeOperation = 'source-over';
            drawVignette();

            requestAnimationFrame(animate);
        }

        animate();
    }

    /* ======================================================
       2. MOBILE MENU
       ====================================================== */
    function initMobileMenu() {
        var toggle = document.querySelector('.mobile-menu-toggle');
        var nav = document.querySelector('.header-nav');
        
        if (toggle && nav) {
            toggle.addEventListener('click', function() {
                var isOpen = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', String(!isOpen));
                nav.classList.toggle('nav-open');
                toggle.classList.toggle('active');
            });
            
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    toggle.setAttribute('aria-expanded', 'false');
                    nav.classList.remove('nav-open');
                    toggle.classList.remove('active');
                }
            });
            
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    toggle.setAttribute('aria-expanded', 'false');
                    nav.classList.remove('nav-open');
                    toggle.classList.remove('active');
                }
            });
        }
    }

    /* ======================================================
       3. INIT
       ====================================================== */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initDisco();
            initMobileMenu();
        });
    } else {
        initDisco();
        initMobileMenu();
    }

})();
