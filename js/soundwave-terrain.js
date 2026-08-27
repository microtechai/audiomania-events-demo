/**
 * Audiomania Eventos — 3D Soundwave Terrain Background
 *
 * Three.js terrain mesh with audio-reactive vertex displacement.
 * Wireframe grid + colored vertices + animated point lights.
 * Bloom post-processing on desktop, clean fallback on mobile.
 *
 * Usage: import SoundwaveTerrain from './soundwave-terrain.js';
 *        SoundwaveTerrain.init();
 *
 * No import needed — it's a module, loaded via importmap in functions.php.
 */

import * as THREE from 'three';
import { EffectComposer } from 'three/addons/postprocessing/EffectComposer.js';
import { RenderPass } from 'three/addons/postprocessing/RenderPass.js';
import { UnrealBloomPass } from 'three/addons/postprocessing/UnrealBloomPass.js';

export default {

    _scene: null,
    _camera: null,
    _renderer: null,
    _composer: null,
    _terrain: null,
    _wireMesh: null,
    _clock: null,
    _mouse: { x: 0, y: 0 },
    _animId: null,
    _isMobile: false,
    _reducedMotion: false,

    /**
     * Initialize the 3D soundwave terrain.
     */
    init() {
        this._isMobile = /Mobi|Android/i.test(navigator.userAgent);
        this._reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (this._isMobile || this._reducedMotion) {
            // On mobile or for users who prefer reduced motion,
            // fall back to a simpler version: subtle gradient animation via CSS.
            document.getElementById('am-soundwave-canvas')?.remove();
            return;
        }

        const canvas = document.getElementById('am-soundwave-canvas');
        if (!canvas) return;

        // ---- Renderer ----
        this._renderer = new THREE.WebGLRenderer({
            canvas,
            antialias: true,
            alpha: true,
            powerPreference: 'high-performance'
        });
        this._renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        this._renderer.setSize(window.innerWidth, window.innerHeight);
        this._renderer.toneMapping = THREE.ACESFilmicToneMapping;
        this._renderer.toneMappingExposure = 1.2;

        // ---- Scene ----
        this._scene = new THREE.Scene();

        // ---- Camera ----
        this._camera = new THREE.PerspectiveCamera(55, window.innerWidth / window.innerHeight, 0.1, 200);
        this._camera.position.set(0, 14, 22);
        this._camera.lookAt(0, 0, 0);

        // ---- Lights ----
        this._setupLights();

        // ---- Terrain ----
        this._setupTerrain();

        // ---- Bloom ----
        this._setupBloom();

        // ---- Mouse ----
        this._setupInteraction();

        // ---- Resize ----
        this._setupResize();

        // ---- Animate ----
        this._clock = new THREE.Clock();
        this._animate();
    },

    /**
     * Setup scene lights.
     */
    _setupLights() {
        this._scene.add(new THREE.AmbientLight(0x123A92, 0.6));

        const dirLight = new THREE.DirectionalLight(0x4d7cff, 1.5);
        dirLight.position.set(5, 15, 10);
        this._scene.add(dirLight);

        const pinkLight = new THREE.PointLight(0xec4899, 2, 50);
        pinkLight.position.set(-10, 5, -5);
        pinkLight.userData = { phase: 0, radius: 8 };
        this._scene.add(pinkLight);

        const purpleLight = new THREE.PointLight(0xa855f7, 2, 50);
        purpleLight.position.set(10, 5, 5);
        purpleLight.userData = { phase: Math.PI, radius: 8 };
        this._scene.add(purpleLight);
    },

    /**
     * Create the terrain mesh — a subdivided plane with vertex displacement.
     */
    _setupTerrain() {
        const segs = 100; // 100x100 = 10000 vertices — good balance
        const geo = new THREE.PlaneGeometry(44, 44, segs, segs);
        geo.rotateX(-Math.PI / 2);

        // Store original positions for height calculation
        geo.userData.originalPositions = geo.attributes.position.array.slice();

        const mat = new THREE.MeshStandardMaterial({
            color: 0x060618,
            metalness: 0.9,
            roughness: 0.35,
            flatShading: false,
            vertexColors: true,
        });

        this._terrain = new THREE.Mesh(geo, mat);
        this._scene.add(this._terrain);

        // Wireframe overlay for the grid effect
        const wireMat = new THREE.MeshBasicMaterial({
            color: 0x4d7cff,
            wireframe: true,
            transparent: true,
            opacity: 0.12,
        });

        this._wireMesh = new THREE.Mesh(geo.clone(), wireMat);
        this._wireMesh.position.y = 0.02;
        this._scene.add(this._wireMesh);
    },

    /**
     * Setup bloom post-processing.
     */
    _setupBloom() {
        this._composer = new EffectComposer(this._renderer);

        const renderPass = new RenderPass(this._scene, this._camera);
        this._composer.addPass(renderPass);

        const bloomPass = new UnrealBloomPass(
            new THREE.Vector2(window.innerWidth, window.innerHeight),
            1.4,  // strength
            0.5,  // radius
            0.7   // threshold
        );
        this._composer.addPass(bloomPass);
    },

    /**
     * Mouse + touch interaction.
     */
    _setupInteraction() {
        const self = this;
        document.addEventListener('mousemove', function (e) {
            self._mouse.x = (e.clientX / window.innerWidth) * 2 - 1;
            self._mouse.y = -(e.clientY / window.innerHeight) * 2 + 1;
        });

        document.addEventListener('touchmove', function (e) {
            if (e.touches.length > 0) {
                self._mouse.x = (e.touches[0].clientX / window.innerWidth) * 2 - 1;
                self._mouse.y = -(e.touches[0].clientY / window.innerHeight) * 2 + 1;
            }
        }, { passive: true });
    },

    /**
     * Handle window resize.
     */
    _setupResize() {
        let resizeTimeout;
        window.addEventListener('resize', function () {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function () {
                const w = window.innerWidth;
                const h = window.innerHeight;
                self._camera.aspect = w / h;
                self._camera.updateProjectionMatrix();
                self._renderer.setSize(w, h);
                self._composer.setSize(w, h);
            }, 200);
        });
    },

    /**
     * Compute terrain height at a given (x, z) for a given time.
     * Layered sine waves simulate audio frequency bands.
     */
    _getTerrainHeight(x, z, t) {
        let h = 0;

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
        const dist = Math.sqrt(x * x + z * z);
        const pulse = Math.sin(t * 0.5) * 0.6 + Math.sin(t * 1.3) * 0.35;
        h += pulse * Math.exp(-dist / 25);

        return h;
    },

    /**
     * Color a height value as neon (blue → purple → pink).
     */
    _heightToColor(h) {
        const norm = (h + 3.5) / 7; // normalize ~0-1
        const r = Math.round(77 + norm * 159);   // 77 → 236
        const g = Math.round(124 + norm * (-52)); // 124 → 72
        const b = Math.round(255 - norm * 102);   // 255 → 153
        return [r / 255, g / 255, b / 255];
    },

    /**
     * Main animation loop.
     */
    _animate() {
        this._animId = requestAnimationFrame(() => this._animate());

        const t = this._clock.getElapsedTime();
        const pos = this._terrain.geometry.attributes.position.array;
        const origPos = this._terrain.geometry.userData.originalPositions;
        const count = pos.length / 3;

        // Build vertex colors
        const colorData = new Float32Array(count * 3);

        for (let i = 0; i < count; i++) {
            const ox = origPos[i * 3];
            const oz = origPos[i * 3 + 2];

            // Height displacement
            const h = this._getTerrainHeight(ox, oz, t);
            pos[i * 3 + 1] = h;

            // Color from height
            const [cr, cg, cb] = this._heightToColor(h);
            colorData[i * 3] = cr;
            colorData[i * 3 + 1] = cg;
            colorData[i * 3 + 2] = cb;
        }

        this._terrain.geometry.setAttribute('position', new THREE.BufferAttribute(pos, 3));
        this._terrain.geometry.setAttribute('color', new THREE.BufferAttribute(colorData, 3));

        // Update wireframe geometry
        const wirePos = this._wireMesh.geometry.attributes.position.array;
        for (let i = 0; i < count; i++) {
            const ox = origPos[i * 3];
            const oz = origPos[i * 3 + 2];
            const h = this._getTerrainHeight(ox, oz, t);
            wirePos[i * 3 + 1] = h + 0.02;
        }
        this._wireMesh.geometry.setAttribute('position', new THREE.BufferAttribute(wirePos, 3));

        // Camera follows mouse gently
        const targetCamX = this._mouse.x * 5;
        const targetCamZ = 22 + this._mouse.y * 3;
        this._camera.position.x += (targetCamX - this._camera.position.x) * 0.02;
        this._camera.position.z += (targetCamZ - this._camera.position.z) * 0.02;
        this._camera.lookAt(0, 1, 0);

        // Orbit lights
        this._scene.children.forEach(function (child) {
            if (child.isPointLight) {
                const d = child.userData;
                child.position.x = (child.position.x > 0 ? 1 : -1) * 10;
                child.position.x += Math.sin(t * 0.3 + d.phase) * d.radius;
                child.position.z += Math.cos(t * 0.4 + d.phase) * 6;
                // Pulse intensity
                child.intensity = 2 + Math.sin(t * 1.2 + d.phase) * 0.5;
            }
        });

        // Render with bloom
        this._composer.render();
    },
};
