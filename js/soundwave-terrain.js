/**
 * Audiomania Eventos — 3D Soundwave Terrain Background
 *
 * Three.js terrain mesh with audio-reactive vertex displacement.
 * Global Three.js loaded via CDN scripts in functions.php.
 *
 * Usage: Loaded automatically on page load.
 *        Mobile / reduced-motion users get a pure CSS fallback.
 */

(function () {
    'use strict';

    // ---- Feature detection: skip if Three.js not available ----
    if (typeof THREE === 'undefined') return;
    if (typeof EffectComposer === 'undefined') return;
    if (typeof RenderPass === 'undefined') return;
    if (typeof UnrealBloomPass === 'undefined') return;

    // ---- Mobile / reduced motion fallback ----
    var isMobile = /Mobi|Android/i.test(navigator.userAgent);
    var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (isMobile || reducedMotion) {
        var canvas = document.getElementById('am-soundwave-canvas');
        if (canvas) canvas.remove();
        return;
    }

    // ---- Get canvas ----
    var canvas = document.getElementById('am-soundwave-canvas');
    if (!canvas) return;

    // ---- Renderer ----
    var renderer = new THREE.WebGLRenderer({
        canvas: canvas,
        antialias: true,
        alpha: true,
        powerPreference: 'high-performance'
    });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1.2;

    // ---- Scene ----
    var scene = new THREE.Scene();

    // ---- Camera ----
    var camera = new THREE.PerspectiveCamera(55, window.innerWidth / window.innerHeight, 0.1, 200);
    camera.position.set(0, 14, 22);
    camera.lookAt(0, 0, 0);

    // ---- Lights ----
    scene.add(new THREE.AmbientLight(0x123A92, 0.6));

    var dirLight = new THREE.DirectionalLight(0x4d7cff, 1.5);
    dirLight.position.set(5, 15, 10);
    scene.add(dirLight);

    var pinkLight = new THREE.PointLight(0xec4899, 2.5, 60);
    pinkLight.position.set(-10, 5, -5);
    pinkLight.userData = { phase: 0, radius: 10 };
    scene.add(pinkLight);

    var purpleLight = new THREE.PointLight(0xa855f7, 2.5, 60);
    purpleLight.position.set(10, 5, 5);
    purpleLight.userData = { phase: Math.PI, radius: 10 };
    scene.add(purpleLight);

    var cyanLight = new THREE.PointLight(0x22d3ee, 1.5, 50);
    cyanLight.position.set(0, 8, 10);
    cyanLight.userData = { phase: Math.PI * 0.5, radius: 12 };
    scene.add(cyanLight);

    // ---- Terrain mesh ----
    var segs = 100;
    var geo = new THREE.PlaneGeometry(44, 44, segs, segs);
    geo.rotateX(-Math.PI / 2);

    // Store original positions for height calculation
    geo.userData.originalPositions = new Float32Array(geo.attributes.position.array);

    var mat = new THREE.MeshStandardMaterial({
        color: 0x060618,
        metalness: 0.9,
        roughness: 0.3,
        vertexColors: true,
        flatShading: false,
    });

    var terrain = new THREE.Mesh(geo, mat);
    scene.add(terrain);

    // ---- Wireframe overlay for the grid lines ----
    var wireMat = new THREE.MeshBasicMaterial({
        color: 0x4d7cff,
        wireframe: true,
        transparent: true,
        opacity: 0.15,
    });

    var wireMesh = new THREE.Mesh(geo.clone(), wireMat);
    wireMesh.position.y = 0.02;
    scene.add(wireMesh);

    // ---- Bloom post-processing ----
    var composer = new EffectComposer(renderer);

    var renderPass = new RenderPass(scene, camera);
    composer.addPass(renderPass);

    var bloomPass = new UnrealBloomPass(
        new THREE.Vector2(window.innerWidth, window.innerHeight),
        1.5,  // strength
        0.6,  // radius
        0.7   // threshold
    );
    composer.addPass(bloomPass);

    // ---- Mouse interaction ----
    var mouse = { x: 0, y: 0 };
    document.addEventListener('mousemove', function (e) {
        mouse.x = (e.clientX / window.innerWidth) * 2 - 1;
        mouse.y = -(e.clientY / window.innerHeight) * 2 + 1;
    });

    document.addEventListener('touchmove', function (e) {
        if (e.touches.length > 0) {
            mouse.x = (e.touches[0].clientX / window.innerWidth) * 2 - 1;
            mouse.y = -(e.touches[0].clientY / window.innerHeight) * 2 + 1;
        }
    }, { passive: true });

    // ---- Resize ----
    var resizeTimeout;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function () {
            var w = window.innerWidth;
            var h = window.innerHeight;
            camera.aspect = w / h;
            camera.updateProjectionMatrix();
            renderer.setSize(w, h);
            composer.setSize(w, h);
        }, 200);
    });

    // ---- Terrain height function: layered sine waves ----
    function getTerrainHeight(x, z, t) {
        var h = 0;

        // Bass — large slow waves
        h += Math.sin(x * 0.3 + t * 0.8) * 2.2;
        h += Math.sin(z * 0.35 + t * 0.6) * 2.0;

        // Mids — medium complexity
        h += Math.sin((x + z) * 0.5 + t * 1.2) * 0.9;
        h += Math.cos(x * 0.65 - z * 0.3 + t * 1.0) * 0.7;

        // Treble — fast small ripples
        h += Math.sin(x * 1.4 + z * 1.1 + t * 2.0) * 0.35;
        h += Math.cos(z * 1.7 - x * 0.8 + t * 1.8) * 0.3;

        // Pulse beat — radial wave from center
        var dist = Math.sqrt(x * x + z * z);
        var pulse = Math.sin(t * 0.5) * 0.6 + Math.sin(t * 1.3) * 0.35;
        h += pulse * Math.exp(-dist / 25);

        return h;
    }

    // ---- Height to neon color (blue → purple → pink) ----
    function heightToColor(h) {
        var norm = (h + 3.5) / 7; // normalize ~0-1
        var r = Math.round(77 + norm * 159);   // 77 → 236
        var g = Math.round(124 + norm * (-52)); // 124 → 72
        var b = Math.round(255 - norm * 102);   // 255 → 153
        return [r / 255, g / 255, b / 255];
    }

    // ---- Animation loop ----
    var clock = new THREE.Clock();
    var pos, colorData, origPos, wirePos, count;
    var positionAttr, colorAttr, wirePositionAttr;

    // Pre-allocate buffers
    pos = new Float32Array(geo.attributes.position.array);
    origPos = geo.userData.originalPositions;
    colorData = new Float32Array(count || 0);
    wirePos = new Float32Array(wireMesh.geometry.attributes.position.array);
    count = pos.length / 3;
    colorData = new Float32Array(count * 3);

    function animate() {
        requestAnimationFrame(animate);

        var t = clock.getElapsedTime();

        // ---- Update terrain vertices & colors ----
        for (var i = 0; i < count; i++) {
            var ox = origPos[i * 3];
            var oz = origPos[i * 3 + 2];

            var h = getTerrainHeight(ox, oz, t);

            // Height displacement
            pos[i * 3 + 1] = h;

            // Color from height
            var c = heightToColor(h);
            colorData[i * 3] = c[0];
            colorData[i * 3 + 1] = c[1];
            colorData[i * 3 + 2] = c[2];

            // Wireframe height
            wirePos[i * 3 + 1] = h + 0.02;
        }

        positionAttr = new THREE.BufferAttribute(pos, 3);
        geo.setAttribute('position', positionAttr);

        colorAttr = new THREE.BufferAttribute(colorData, 3);
        geo.setAttribute('color', colorAttr);

        wirePositionAttr = new THREE.BufferAttribute(wirePos, 3);
        wireMesh.geometry.setAttribute('position', wirePositionAttr);

        // ---- Camera follows mouse gently ----
        var targetCamX = mouse.x * 5;
        var targetCamZ = 22 + mouse.y * 3;
        camera.position.x += (targetCamX - camera.position.x) * 0.02;
        camera.position.z += (targetCamZ - camera.position.z) * 0.02;
        camera.lookAt(0, 1, 0);

        // ---- Orbit lights ----
        for (var l = 0; l < scene.children.length; l++) {
            var light = scene.children[l];
            if (light.isPointLight && light.userData && light.userData.radius) {
                var d = light.userData;
                var dir = light.position.x > 0 ? 1 : -1;
                light.position.x = dir * 10 + Math.sin(t * 0.3 + d.phase) * d.radius;
                light.position.z += Math.cos(t * 0.4 + d.phase) * 7;
                light.intensity = 2.5 + Math.sin(t * 1.2 + d.phase) * 0.8;
            }
        }

        // ---- Pulse bloom ----
        bloomPass.strength = 1.3 + Math.sin(t * 0.8) * 0.3;

        // ---- Render with bloom ----
        composer.render();
    }

    animate();

    // ---- Visibility: pause when tab is hidden ----
    document.addEventListener('visibilitychange', function () {
        if (document.hidden) {
            clock.stop();
        } else {
            clock.start();
        }
    });

})();
