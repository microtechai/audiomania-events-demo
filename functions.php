<?php
/**
 * Audiomania Eventos Child Theme — functions.php
 *
 * Hero carousel animado en home, hero estático en otras páginas,
 * UI moderna con glassmorphism y animaciones de scroll.
 *
 * @package AudiomaniaEventsChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * ------------------------------------------------------------------
 * 1. THEME SETUP
 * ------------------------------------------------------------------
 */
function audiomania_child_setup() {
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );

    register_nav_menus( array(
        'primary'   => __( 'Menú Principal', 'audiomania-events-child' ),
        'footer'    => __( 'Menú Footer', 'audiomania-events-child' ),
        'mobile'    => __( 'Menú Móvil', 'audiomania-events-child' ),
    ) );

    add_theme_support( 'html5', array(
        'search-form', 'comment-form', 'comment-list',
        'gallery', 'caption', 'style', 'script',
    ) );

    add_theme_support( 'custom-header', array(
        'default-image'      => '',
        'width'              => 1920,
        'height'             => 800,
        'flex-height'        => true,
        'flex-width'         => true,
        'default-text-color' => '',
    ) );

    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    add_filter( 'excerpt_length', function() {
        return 35;
    });
}
add_action( 'after_setup_theme', 'audiomania_child_setup' );

/**
 * ------------------------------------------------------------------
 * 2. ENQUEUE SCRIPTS & STYLES
 * ------------------------------------------------------------------
 */
function audiomania_child_enqueue_scripts() {
    wp_enqueue_style(
        'hello-elementor',
        get_template_directory_uri() . '/style.css',
        array(),
        wp_get_theme()->get( 'Version' )
    );

    wp_enqueue_style(
        'audiomania-child',
        get_stylesheet_uri(),
        array( 'hello-elementor' ),
        wp_get_theme()->get( 'Version' )
    );

    wp_enqueue_style(
        'audiomania-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Noto+Serif:wght@400;500;700;900&display=swap',
        array(),
        null
    );

    wp_enqueue_script(
        'audiomania-child-js',
        get_stylesheet_directory_uri() . '/js/main.js',
        array(),
        wp_get_theme()->get( 'Version' ),
        true
    );

    wp_localize_script( 'audiomania-child-js', 'audiomaniaConfig', array(
        'whatsappNumber' => '34669621139',
        'siteUrl'        => esc_url_raw( home_url() ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'audiomania_child_enqueue_scripts', 20 );

/**
 * ------------------------------------------------------------------
 * 3. CUSTOM CSS INLINE
 * ------------------------------------------------------------------
 */
function audiomania_child_custom_css() {
    $mods = get_theme_mods();
    if ( $mods && ! empty( $mods['stylesheet_custom_css'] ) ) {
        wp_add_inline_style( 'audiomania-child', $mods['stylesheet_custom_css'] );
    }
}
add_action( 'wp_enqueue_scripts', 'audiomania_child_custom_css', 15 );

/**
 * ------------------------------------------------------------------
 * 4. DISCO CANVAS
 * ------------------------------------------------------------------
 */
function audiomania_child_disco_canvas() {
    if ( is_admin() ) return;
    ?>
    <canvas id="am-disco-canvas" aria-hidden="true"></canvas>
    <?php
}
add_action( 'wp_body_open', 'audiomania_child_disco_canvas', 5 );

/**
 * ------------------------------------------------------------------
 * 5. HERO SECTION — PHP Server-Side Injection
 * ------------------------------------------------------------------
 */
function audiomania_hero_css() {
    if ( is_admin() ) return;
    ?>
    <style>
    /* === HERO BASE === */
    .am-hero {
        position: relative;
        overflow: hidden;
        color: #fff;
        z-index: 1;
    }

    /* === HOME — 3 SECCIONES HERO === */
    .am-hero-section {
        min-height: 100vh;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        scroll-margin-top: 0;
    }

    .am-hero-section:nth-child(2) {
        background: linear-gradient(180deg, rgba(3,3,8,1) 0%, rgba(3,3,8,0) 5%, rgba(3,3,8,0) 95%, rgba(3,3,8,1) 100%);
    }
    .am-hero-section:last-child {
        background: linear-gradient(180deg, rgba(3,3,8,1) 0%, rgba(3,3,8,0) 5%, rgba(3,3,8,0) 95%, rgba(3,3,8,1) 100%);
    }

    .am-hero-bg {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        z-index: 0;
        will-change: transform;
        transition: transform 0.1s linear;
    }

    .am-hero-section-1 .am-hero-bg {
        background-image: url('/audiomaniaeventos/wp-content/uploads/2026/08/Eventos-Tenerife.webp');
    }

    .am-hero-section-2 .am-hero-bg {
        background-image: url('/audiomaniaeventos/wp-content/uploads/2026/08/Eventos-Canarias.webp');
    }

    .am-hero-section-3 .am-hero-bg {
        background-image: url('/audiomaniaeventos/wp-content/uploads/2026/08/Dj-tenerife.webp');
    }

    .am-hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(
            180deg,
            rgba(3,3,8,0.7) 0%,
            rgba(3,3,8,0.5) 40%,
            rgba(3,3,8,0.6) 70%,
            rgba(3,3,8,0.95) 100%
        );
        z-index: 1;
    }

    /* === HERO CONTENT === */
    .am-hero-content {
        position: relative;
        z-index: 5;
        max-width: 900px;
        margin: 0 auto;
        padding: 120px 24px 80px;
        text-align: center;
    }

    .am-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 20px;
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        color: rgba(255,255,255,0.9);
        margin-bottom: 24px;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .am-hero-content h1 {
        font-family: 'Noto Serif', serif;
        font-size: clamp(2.4rem, 6vw, 4.2rem);
        font-weight: 900;
        line-height: 1.1;
        margin: 0 0 20px;
        text-shadow: 0 2px 40px rgba(0,0,0,0.5);
        letter-spacing: -0.02em;
    }

    .am-hero-content h1 span {
        background: linear-gradient(135deg, #4d7cff, #a855f7);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .am-hero-content .hero-subtitle {
        font-family: 'Inter', sans-serif;
        font-size: clamp(1.05rem, 2.5vw, 1.35rem);
        color: rgba(255,255,255,0.85);
        margin: 0 0 40px;
        line-height: 1.7;
        max-width: 650px;
        margin-left: auto;
        margin-right: auto;
    }

    .am-hero-cta-group {
        display: flex;
        gap: 16px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .am-hero-cta {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 16px 36px;
        border-radius: 12px;
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 1.05rem;
        text-decoration: none;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }

    .am-hero-cta-primary {
        background: linear-gradient(135deg, #123A92, #4d7cff);
        color: #fff;
        border: none;
        box-shadow: 0 4px 24px rgba(77,124,255,0.4);
    }

    .am-hero-cta-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 40px rgba(77,124,255,0.6), 0 0 60px rgba(77,124,255,0.2);
    }

    .am-hero-cta-secondary {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 2px solid rgba(255,255,255,0.25);
        color: #fff;
    }

    .am-hero-cta-secondary:hover {
        background: rgba(255,255,255,0.2);
        border-color: rgba(255,255,255,0.5);
        transform: translateY(-2px);
    }

    .am-hero-cta-whatsapp {
        background: #25D366;
        color: #fff;
        border: none;
        box-shadow: 0 4px 24px rgba(37,211,102,0.4);
    }

    .am-hero-cta-whatsapp:hover {
        background: #20bd5a;
        transform: translateY(-3px);
        box-shadow: 0 8px 40px rgba(37,211,102,0.6);
    }

    /* === FEATURE ITEMS === */
    .am-hero-features {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 40px;
    }

    .am-feature-item {
        background: rgba(255,255,255,0.08);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 50px;
        padding: 8px 20px;
        font-size: 0.85rem;
        font-weight: 600;
        color: rgba(255,255,255,0.85);
        letter-spacing: 0.02em;
        transition: all 0.3s ease;
        cursor: default;
    }

    .am-feature-item:hover {
        background: rgba(255,255,255,0.15);
        border-color: rgba(255,255,255,0.3);
        color: #fff;
        transform: translateY(-2px);
    }

    /* === SINGLE PAGE HERO === */
    .am-hero-single {
        min-height: 65vh;
        position: relative;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        display: flex;
        align-items: center;
    }

    .am-hero-single::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(
            180deg,
            rgba(3,3,8,0.7) 0%,
            rgba(3,3,8,0.5) 50%,
            rgba(3,3,8,0.85) 100%
        );
        z-index: 1;
    }

    .am-hero-single .am-hero-content {
        z-index: 3;
        position: relative;
    }

    /* === SCROLL INDICATOR === */
    .am-scroll-indicator {
        position: absolute;
        bottom: 24px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        animation: scrollBounce 2s ease-in-out infinite;
    }

    .am-scroll-indicator span {
        font-size: 0.75rem;
        color: rgba(255,255,255,0.5);
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-weight: 600;
    }

    @keyframes scrollBounce {
        0%, 100% { transform: translateX(-50%) translateY(0); }
        50% { transform: translateX(-50%) translateY(8px); }
    }

    /* === RESPONSIVE === */
    @media (max-width: 768px) {
        .am-hero-section { min-height: 85vh; }
        .am-hero-content { padding: 80px 20px 60px; }
        .am-hero-content h1 { font-size: clamp(1.8rem, 8vw, 2.8rem); }
        .am-hero-content h2 { font-size: clamp(1.5rem, 6vw, 2.2rem); }
        .am-hero-cta-group { flex-direction: column; align-items: center; }
        .am-hero-cta { width: 100%; max-width: 300px; justify-content: center; }
        .am-hero-features { gap: 8px; }
        .am-feature-item { font-size: 0.75rem; padding: 6px 14px; }
        .am-hero-single { min-height: 55vh; }
    }

    @media (max-width: 480px) {
        .am-hero-section { min-height: 80vh; }
        .am-hero-content { padding: 60px 16px 50px; }
        .am-hero-content h1 { font-size: 1.6rem; }
        .am-hero-content h2 { font-size: 1.4rem; }
        .am-hero-content .hero-subtitle { font-size: 0.95rem; }
        .am-feature-item { font-size: 0.7rem; padding: 5px 12px; }
    }
    </style>
    <?php
}
add_action( 'wp_head', 'audiomania_hero_css', 1 );

add_filter( 'the_content', 'audiomania_hero_content' );
function audiomania_hero_content( $content ) {
    if ( is_admin() || is_cart() || is_checkout() || is_account_page() ) {
        return $content;
    }

    $hero_html = '';
    $base = '/audiomaniaeventos/wp-content/uploads/2026/08/';

    // HOME — 3 secciones hero con imágenes separadas
    if ( is_front_page() || is_home() ) {
        $hero_html = "\n";
        $hero_html .= '<!-- SEO JSON-LD: LocalBusiness + Service -->' . "\n";
        $hero_html .= '<script type="application/ld+json">' . "\n";
        $hero_html .= '{' . "\n";
        $hero_html .= '  "@context": "https://schema.org",' . "\n";
        $hero_html .= '  "@type": "LocalBusiness",' . "\n";
        $hero_html .= '  "name": "Audiomania Eventos",' . "\n";
        $hero_html .= '  "description": "Servicios profesionales de sonido, iluminación y animación para eventos en Tenerife y Canarias. DJ profesional, alquiler de sonido, iluminación LED, photocall y más.",' . "\n";
        $hero_html .= '  "url": "' . esc_url( home_url( '/audiomaniaeventos/' ) ) . '",' . "\n";
        $hero_html .= '  "telephone": "+34669621139",' . "\n";
        $hero_html .= '  "email": "carlostoledodj@gmail.com",' . "\n";
        $hero_html .= '  "address": {' . "\n";
        $hero_html .= '    "@type": "PostalAddress",' . "\n";
        $hero_html .= '    "addressLocality": "Tenerife",' . "\n";
        $hero_html .= '    "addressRegion": "Canarias",' . "\n";
        $hero_html .= '    "addressCountry": "ES"' . "\n";
        $hero_html .= '  },' . "\n";
        $hero_html .= '  "serviceType": ["DJ para Eventos", "Alquiler de Sonido", "Iluminación LED", "Photocall", "Animación de Eventos"],' . "\n";
        $hero_html .= '  "areaServed": {' . "\n";
        $hero_html .= '    "@type": "Place",' . "\n";
        $hero_html .= '    "name": "Tenerife, Canarias, España"' . "\n";
        $hero_html .= '  },' . "\n";
        $hero_html .= '  "priceRange": "$$"' . "\n";
        $hero_html .= '}' . "\n";
        $hero_html .= '</script>' . "\n";

        // === SECCION 1: HERO PRINCIPAL ===
        $hero_html .= '<section class="am-hero-section am-hero-section-1" id="am-hero-1">' . "\n";
        $hero_html .= '  <div class="am-hero-bg" style="background-image:url(\'' . $base . 'Eventos-Tenerife.webp\');"></div>' . "\n";
        $hero_html .= '  <div class="am-hero-overlay"></div>' . "\n";
        $hero_html .= '  <div class="am-hero-content">' . "\n";
        $hero_html .= '    <div class="am-hero-badge">🎵 Eventos Profesionales en Tenerife</div>' . "\n";
        $hero_html .= '    <h1>Sonido, Iluminación y <span>Animación</span> para tu Evento</h1>' . "\n";
        $hero_html .= '    <p class="hero-subtitle">DJ profesional, alquiler de sonido, iluminación LED, photocall y más. Todo lo que necesitas para una fiesta inolvidable en Tenerife y Canarias.</p>' . "\n";
        $hero_html .= '    <div class="am-hero-cta-group">' . "\n";
        $hero_html .= '      <a href="/audiomaniaeventos/reservar/" class="am-hero-cta am-hero-cta-primary">Solicitar Presupuesto <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg></a>' . "\n";
        $hero_html .= '      <a href="/audiomaniaeventos/servicios/" class="am-hero-cta am-hero-cta-secondary">Ver Servicios</a>' . "\n";
        $hero_html .= '    </div>' . "\n";
        $hero_html .= '    <div class="am-hero-features">' . "\n";
        $hero_html .= '      <div class="am-feature-item">🔊 Sonido Profesional</div>' . "\n";
        $hero_html .= '      <div class="am-feature-item">💡 Iluminación LED</div>' . "\n";
        $hero_html .= '      <div class="am-feature-item">🎧 DJ Expertos</div>' . "\n";
        $hero_html .= '    </div>' . "\n";
        $hero_html .= '  </div>' . "\n";
        $hero_html .= '  <a href="#am-hero-2" class="am-scroll-indicator" aria-label="Ver más servicios">' . "\n";
        $hero_html .= '    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.6)" stroke-width="2"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>' . "\n";
        $hero_html .= '    <span>Descubre nuestros servicios</span>' . "\n";
        $hero_html .= '  </a>' . "\n";
        $hero_html .= '</section>' . "\n";

        // === SECCION 2: EVENTOS CANARIAS ===
        $hero_html .= '<section class="am-hero-section am-hero-section-2" id="am-hero-2">' . "\n";
        $hero_html .= '  <div class="am-hero-bg" style="background-image:url(\'' . $base . 'Eventos-Canarias.webp\');"></div>' . "\n";
        $hero_html .= '  <div class="am-hero-overlay"></div>' . "\n";
        $hero_html .= '  <div class="am-hero-content">' . "\n";
        $hero_html .= '    <div class="am-hero-badge">🏝️ Llevamos tu evento por toda Canarias</div>' . "\n";
        $hero_html .= '    <h2>Montaje Profesional en <span>Tenerife y Todas las Islas</span></h2>' . "\n";
        $hero_html .= '    <p class="hero-subtitle">Bodas en Adeje, fiestas en La Laguna, corporativos en Santa Cruz, celebraciones en La Orotava. Llevamos equipamiento profesional a cualquier punto de la isla y las demás islas canarias.</p>' . "\n";
        $hero_html .= '    <div class="am-hero-cta-group">' . "\n";
        $hero_html .= '      <a href="/audiomaniaeventos/servicios/" class="am-hero-cta am-hero-cta-primary">Ver Servicios <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg></a>' . "\n";
        $hero_html .= '      <a href="/audiomaniaeventos/galeria/" class="am-hero-cta am-hero-cta-secondary">Ver Galería</a>' . "\n";
        $hero_html .= '    </div>' . "\n";
        $hero_html .= '    <div class="am-hero-features">' . "\n";
        $hero_html .= '      <div class="am-feature-item">📍 Toda la isla</div>' . "\n";
        $hero_html .= '      <div class="am-feature-item">🎪 Montaje incluido</div>' . "\n";
        $hero_html .= '      <div class="am-feature-item">⚡ Respuesta en 24h</div>' . "\n";
        $hero_html .= '    </div>' . "\n";
        $hero_html .= '  </div>' . "\n";
        $hero_html .= '  <a href="#am-hero-3" class="am-scroll-indicator" aria-label="Ver servicios de DJ">' . "\n";
        $hero_html .= '    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.6)" stroke-width="2"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>' . "\n";
        $hero_html .= '    <span>Servicio DJ Profesional</span>' . "\n";
        $hero_html .= '  </a>' . "\n";
        $hero_html .= '</section>' . "\n";

        // === SECCION 3: DJ TENERIFE ===
        $hero_html .= '<section class="am-hero-section am-hero-section-3" id="am-hero-3">' . "\n";
        $hero_html .= '  <div class="am-hero-bg" style="background-image:url(\'' . $base . 'Dj-tenerife.webp\');"></div>' . "\n";
        $hero_html .= '  <div class="am-hero-overlay"></div>' . "\n";
        $hero_html .= '  <div class="am-hero-content">' . "\n";
        $hero_html .= '    <div class="am-hero-badge">🎧 DJ Profesional para tu Evento</div>' . "\n";
        $hero_html .= '    <h2>El Mejor <span>DJ para tu Fiesta</span> en Tenerife</h2>' . "\n";
        $hero_html .= '    <p class="hero-subtitle">Bodas, cumpleaños, fiestas privadas, eventos corporativos. DJs profesionales con equipamiento premium, playlists a medida y experiencia en más de 500 eventos en Canarias.</p>' . "\n";
        $hero_html .= '    <div class="am-hero-cta-group">' . "\n";
        $hero_html .= '      <a href="https://wa.me/34669621139?text=Hola%2C%20quiero%20un%20DJ%20para%20mi%20evento%20en%20Tenerife" class="am-hero-cta am-hero-cta-whatsapp" target="_blank" rel="noopener noreferrer">WhatsApp Directo <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg></a>' . "\n";
        $hero_html .= '      <a href="/audiomaniaeventos/contacto/" class="am-hero-cta am-hero-cta-secondary">Contactar</a>' . "\n";
        $hero_html .= '    </div>' . "\n";
        $hero_html .= '    <div class="am-hero-features">' . "\n";
        $hero_html .= '      <div class="am-feature-item">🎵 Música a medida</div>' . "\n";
        $hero_html .= '      <div class="am-feature-item">🔥 +500 eventos</div>' . "\n";
        $hero_html .= '      <div class="am-feature-item">⭐ Equipamiento premium</div>' . "\n";
        $hero_html .= '    </div>' . "\n";
        $hero_html .= '  </div>' . "\n";
        $hero_html .= '</section>' . "\n";
    }

    // SERVICIOS
    elseif ( is_page( 'servicios' ) || is_page( 12 ) ) {
        $hero_html = '<section class="am-hero am-hero-single" style="background-image:url(\'' . $base . 'Eventos-Tenerife.webp\');">' . "\n";
        $hero_html .= '<div class="am-hero-content">' . "\n";
        $hero_html .= '  <div class="am-hero-badge">Servicios Profesionales</div>' . "\n";
        $hero_html .= '  <h1>Tu Evento, <span>Nuestra Pasión</span></h1>' . "\n";
        $hero_html .= '  <p class="hero-subtitle">Sonido, iluminación, DJ y animación profesional para bodas, eventos corporativos, fiestas y celebraciones en Tenerife y toda Canarias.</p>' . "\n";
        $hero_html .= '  <div class="am-hero-cta-group">' . "\n";
        $hero_html .= '    <a href="/audiomaniaeventos/reservar/" class="am-hero-cta am-hero-cta-primary">Solicitar Presupuesto</a>' . "\n";
        $hero_html .= '    <a href="https://wa.me/34669621139" class="am-hero-cta am-hero-cta-whatsapp" target="_blank" rel="noopener">WhatsApp Directo</a>' . "\n";
        $hero_html .= '  </div>' . "\n";
        $hero_html .= '  <div class="am-hero-features">' . "\n";
        $hero_html .= '    <div class="am-feature-item">🔊 15+ Años Experiencia</div>' . "\n";
        $hero_html .= '    <div class="am-feature-item">⚡ Presupuesto en 24h</div>' . "\n";
        $hero_html .= '    <div class="am-feature-item">📍 Toda Canarias</div>' . "\n";
        $hero_html .= '  </div>' . "\n";
        $hero_html .= '</div>' . "\n";
        $hero_html .= '</section>' . "\n";

        // === SECCIÓN: GRID DE SERVICIOS CON PRECIOS ===
        $services_content = '<section class="am-services-section">' . "\n";
        $services_content .= '<div class="am-container">' . "\n";

        // SEO Intro
        $services_content .= '<div class="am-services-intro">' . "\n";
        $services_content .= '  <h2>Servicios de Sonido, Iluminación y Animación en Tenerife</h2>' . "\n";
        $services_content .= '  <p class="am-services-intro-text">Audiomania Eventos ofrece servicios profesionales para todo tipo de celebraciones en Tenerife y Canarias. Desde bodas íntimas hasta eventos corporativos de gran formato, adaptamos cada paquete a tu presupuesto y necesidades. Todos los precios son orientativos — contacta con nosotros para un presupuesto personalizado sin compromiso.</p>' . "\n";
        $services_content .= '</div>' . "\n";

        // Services Grid
        $services_content .= '<div class="am-services-grid">' . "\n";

        // Service 1: DJ para Eventos
        $services_content .= '  <div class="am-service-card" id="dj">' . "\n";
        $services_content .= '    <div class="am-service-card-inner">' . "\n";
        $services_content .= '      <div class="am-service-icon-wrap">🎧</div>' . "\n";
        $services_content .= '      <h3>DJ para Eventos</h3>' . "\n";
        $services_content .= '      <div class="am-service-price">' . "\n";
        $services_content .= '        <span class="am-price-from">Desde</span>' . "\n";
        $services_content .= '        <span class="am-price-value">300€</span>' . "\n";
        $services_content .= '      </div>' . "\n";
        $services_content .= '      <ul class="am-service-features">' . "\n";
        $services_content .= '        <li>Equipment Pioneer + USB controlador</li>' . "\n";
        $services_content .= '        <li>2altavoces activos FBT 12&quot;</li>' . "\n";
        $services_content .= '        <li>Mesa de mezclas profesional</li>' . "\n";
        $services_content .= '        <li>Música a medida según estilo</li>' . "\n";
        $services_content .= '        <li>Duración: hasta 4h + preparación</li>' . "\n";
        $services_content .= '        <li>Micrófono inalámbrico para anuncios</li>' . "\n";
        $services_content .= '      </ul>' . "\n";
        $services_content .= '      <a href="https://wa.me/34669621139?text=Hola%2C%20me%20interesa%20el%20servicio%20de%20DJ%20para%20mi%20evento" class="am-service-cta" target="_blank" rel="noopener">Solicitar Presupuesto</a>' . "\n";
        $services_content .= '    </div>' . "\n";
        $services_content .= '  </div>' . "\n";

        // Service 2: Alquiler de Sonido
        $services_content .= '  <div class="am-service-card" id="sonido">' . "\n";
        $services_content .= '    <div class="am-service-card-inner">' . "\n";
        $services_content .= '      <div class="am-service-icon-wrap">🔊</div>' . "\n";
        $services_content .= '      <h3>Alquiler de Sonido</h3>' . "\n";
        $services_content .= '      <div class="am-service-price">' . "\n";
        $services_content .= '        <span class="am-price-from">Desde</span>' . "\n";
        $services_content .= '        <span class="am-price-value">200€</span>' . "\n";
        $services_content .= '      </div>' . "\n";
        $services_content .= '      <ul class="am-service-features">' . "\n";
        $services_content .= '        <li>Sistema FBT profesional completo</li>' . "\n";
        $services_content .= '        <li>Altavoces + subgraves + monitores</li>' . "\n";
        $services_content .= '        <li>Mescla digital Yamaha/Allen &amp; Heath</li>' . "\n";
        $services_content .= '        <li>2micrófonos inalámbricos Shure</li>' . "\n";
        $services_content .= '        <li>Montaje y montaje incluido</li>' . "\n";
        $services_content .= '        <li>Técnico de sonido disponible (+50€)</li>' . "\n";
        $services_content .= '      </ul>' . "\n";
        $services_content .= '      <a href="https://wa.me/34669621139?text=Hola%2C%20me%20interesa%20el%20alquiler%20de%20sonido%20para%20mi%20evento" class="am-service-cta" target="_blank" rel="noopener">Solicitar Presupuesto</a>' . "\n";
        $services_content .= '    </div>' . "\n";
        $services_content .= '  </div>' . "\n";

        // Service 3: Iluminación LED
        $services_content .= '  <div class="am-service-card" id="iluminacion">' . "\n";
        $services_content .= '    <div class="am-service-card-inner">' . "\n";
        $services_content .= '      <div class="am-service-icon-wrap">💡</div>' . "\n";
        $services_content .= '      <h3>Iluminación LED</h3>' . "\n";
        $services_content .= '      <div class="am-service-price">' . "\n";
        $services_content .= '        <span class="am-price-from">Desde</span>' . "\n";
        $services_content .= '        <span class="am-price-value">400€</span>' . "\n";
        $services_content .= '      </div>' . "\n";
        $services_content .= '      <ul class="am-service-features">' . "\n";
        $services_content .= '        <li>Luces robóticas moving heads</li>' . "\n";
        $services_content .= '        <li>Par LEDs RGBW + DMX controller</li>' . "\n";
        $services_content .= '        <li>Máquina de humo/neblina</li>' . "\n";
        $services_content .= '        <li>Iluminación stage wash</li>' . "\n";
        $services_content .= '        <li>Control DMX programado</li>' . "\n";
        $services_content .= '        <li>Efectos láser (+80€)</li>' . "\n";
        $services_content .= '      </ul>' . "\n";
        $services_content .= '      <a href="https://wa.me/34669621139?text=Hola%2C%20me%20interesa%20la%20iluminaci%C3%B3n%20LED%20para%20mi%20evento" class="am-service-cta" target="_blank" rel="noopener">Solicitar Presupuesto</a>' . "\n";
        $services_content .= '    </div>' . "\n";
        $services_content .= '  </div>' . "\n";

        // Service 4: Pantallas LED / Nexus
        $services_content .= '  <div class="am-service-card" id="pantallas">' . "\n";
        $services_content .= '    <div class="am-service-card-inner">' . "\n";
        $services_content .= '      <div class="am-service-icon-wrap">📺</div>' . "\n";
        $services_content .= '      <h3>Pantallas LED / Nexus</h3>' . "\n";
        $services_content .= '      <div class="am-service-price">' . "\n";
        $services_content .= '        <span class="am-price-from">Desde</span>' . "\n";
        $services_content .= '        <span class="am-price-value">600€</span>' . "\n";
        $services_content .= '      </div>' . "\n";
        $services_content .= '      <ul class="am-service-features">' . "\n";
        $services_content .= '        <li>Pantalla LED P3 interior / P4 exterior</li>' . "\n";
        $services_content .= '        <li>Tamaño: hasta 4x3m (personalizable)</li>' . "\n";
        $services_content .= '        <li>Video Wall con controlador Novastar</li>' . "\n";
        $services_content .= '        <li>Reproducción de contenido en vivo</li>' . "\n";
        $services_content .= '        <li>Estructura y montaje incluido</li>' . "\n";
        $services_content .= '        <li>Backup de video (USB/HDMI)</li>' . "\n";
        $services_content .= '      </ul>' . "\n";
        $services_content .= '      <a href="https://wa.me/34669621139?text=Hola%2C%20me%20interesa%20la%20pantalla%20LED%20para%20mi%20evento" class="am-service-cta" target="_blank" rel="noopener">Solicitar Presupuesto</a>' . "\n";
        $services_content .= '    </div>' . "\n";
        $services_content .= '  </div>' . "\n";

        // Service 5: Photocall
        $services_content .= '  <div class="am-service-card" id="photocall">' . "\n";
        $services_content .= '    <div class="am-service-card-inner">' . "\n";
        $services_content .= '      <div class="am-service-icon-wrap">📸</div>' . "\n";
        $services_content .= '      <h3>Photocall &amp; Backdrop</h3>' . "\n";
        $services_content .= '      <div class="am-service-price">' . "\n";
        $services_content .= '        <span class="am-price-from">Desde</span>' . "\n";
        $services_content .= '        <span class="am-price-value">180€</span>' . "\n";
        $services_content .= '      </div>' . "\n";
        $services_content .= '      <ul class="am-service-features">' . "\n";
        $services_content .= '        <li>Photocall personalizado con tu logo/nombre</li>' . "\n";
        $services_content .= '        <li>Backdrop letras individuales (3D)</li>' . "\n";
        $services_content .= '        <li>Iluminación incluida</li>' . "\n";
        $services_content .= '        <li>Impresión fotos al momento</li>' . "\n";
        $services_content .= '        <li>Accesorios y props para fotos</li>' . "\n";
        $services_content .= '        <li>Backdrop LED (+100€)</li>' . "\n";
        $services_content .= '      </ul>' . "\n";
        $services_content .= '      <a href="https://wa.me/34669621139?text=Hola%2C%20me%20interesa%20el%20photocall%20para%20mi%20evento" class="am-service-cta" target="_blank" rel="noopener">Solicitar Presupuesto</a>' . "\n";
        $services_content .= '    </div>' . "\n";
        $services_content .= '  </div>' . "\n";

        // Service 6: Paquetes Bodas
        $services_content .= '  <div class="am-service-card am-service-card-highlight" id="bodas">' . "\n";
        $services_content .= '    <div class="am-service-card-inner">' . "\n";
        $services_content .= '      <div class="am-service-badge">⭐ Más Popular</div>' . "\n";
        $services_content .= '      <div class="am-service-icon-wrap">💒</div>' . "\n";
        $services_content .= '      <h3>Paquete Boda Completo</h3>' . "\n";
        $services_content .= '      <div class="am-service-price">' . "\n";
        $services_content .= '        <span class="am-price-from">Desde</span>' . "\n";
        $services_content .= '        <span class="am-price-value">1.200€</span>' . "\n";
        $services_content .= '      </div>' . "\n";
        $services_content .= '      <ul class="am-service-features">' . "\n";
        $services_content .= '        <li>DJ profesional + animación ceremonia</li>' . "\n";
        $services_content .= '        <li>Sonido completo banquete + fiesta</li>' . "\n";
        $services_content .= '        <li>Iluminación ambientación sala</li>' . "\n";
        $services_content .= '        <li>Photocall personalizado</li>' . "\n";
        $services_content .= '        <li>Micrófonos inalámbricos ceremonia</li>' . "\n";
        $services_content .= '        <li>Hasta 6h de servicio completo</li>' . "\n";
        $services_content .= '        <li>Playlist personalizada a medida</li>' . "\n";
        $services_content .= '        <li>Coordiador de evento incluido</li>' . "\n";
        $services_content .= '      </ul>' . "\n";
        $services_content .= '      <a href="https://wa.me/34669621139?text=Hola%2C%20me%20interesa%20el%20paquete%20boda%20completo" class="am-service-cta am-service-cta-highlight" target="_blank" rel="noopener">Solicitar Presupuesto</a>' . "\n";
        $services_content .= '    </div>' . "\n";
        $services_content .= '  </div>' . "\n";

        $services_content .= '</div>' . "\n"; // end services grid

        // Additional services row
        $services_content .= '<div class="am-services-extra">' . "\n";
        $services_content .= '  <h3>Servicios Complementarios</h3>' . "\n";
        $services_content .= '  <div class="am-services-extra-grid">' . "\n";

        $extras = array(
            array('icon' => '🎪', 'title' => 'Escenarios y Tarimas', 'price' => '350€', 'desc' => 'Tarimas de abedul, estructuras metálicas, escenarios modulares. Montaje profesional incluido.'),
            array('icon' => '🎤', 'title' => 'Karaoke', 'price' => '120€', 'desc' => 'Sistema completo con pantalla, micrófonos, base de canciones actualizada.'),
            array('icon' => '🪩', 'title' => 'Machine Humo / Nieve', 'price' => '80€', 'desc' => 'Máquina de humo, nieve sintética, confeti. Efectos especiales para momentos clave.'),
            array('icon' => '💺', 'title' => 'Mobiliario Premium', 'price' => '2€/pza', 'desc' => 'Sillas Tiffany, mesas redondas, mantelería, banquetes. Todo personalizable.'),
        );

        foreach ( $extras as $ex ) {
            $services_content .= '    <div class="am-extra-card">' . "\n";
            $services_content .= '      <div class="am-extra-icon">' . esc_html( $ex['icon'] ) . '</div>' . "\n";
            $services_content .= '      <h4>' . esc_html( $ex['title'] ) . '</h4>' . "\n";
            $services_content .= '      <p>' . esc_html( $ex['desc'] ) . '</p>' . "\n";
            $services_content .= '      <span class="am-extra-price">' . esc_html( $ex['price'] ) . '</span>' . "\n";
            $services_content .= '    </div>' . "\n";
        }

        $services_content .= '  </div>' . "\n";
        $services_content .= '</div>' . "\n";

        // CTA Final
        $services_content .= '<div class="am-services-cta-section">' . "\n";
        $services_content .= '  <h2>¿Necesitas un servicio personalizado?</h2>' . "\n";
        $services_content .= '  <p>Creamos paquetes a medida para tu evento. Cuéntanos qué necesitas y te prepararemos una propuesta adaptada a tu presupuesto.</p>' . "\n";
        $services_content .= '  <div class="am-services-cta-buttons">' . "\n";
        $services_content .= '    <a href="/audiomaniaeventos/reservar/" class="am-hero-cta am-hero-cta-primary">Solicitar Presupuesto</a>' . "\n";
        $services_content .= '    <a href="https://wa.me/34669621139" class="am-hero-cta am-hero-cta-whatsapp" target="_blank" rel="noopener">WhatsApp Directo</a>' . "\n";
        $services_content .= '  </div>' . "\n";
        $services_content .= '</div>' . "\n";

        $services_content .= '</div>' . "\n"; // end container
        $services_content .= '</section>' . "\n"; // end services section

        // Append services content after hero
        $hero_html .= $services_content;
    }

    // RESERVAR
    elseif ( is_page( 'reservar' ) || is_page( 13 ) ) {
        $hero_html = '<section class="am-hero am-hero-single" style="background-image:url(\'' . $base . 'hero-party-bg.webp\');">' . "\n";
        $hero_html .= '<div class="am-hero-content">' . "\n";
        $hero_html .= '  <div class="am-hero-badge">Reserva tu Equipo</div>' . "\n";
        $hero_html .= '  <h1>Reserva tu <span>Equipo</span></h1>' . "\n";
        $hero_html .= '  <p class="hero-subtitle">Elige el equipo perfecto para tu evento. Presupuesto personalizado sin compromiso en menos de 24 horas.</p>' . "\n";
        $hero_html .= '  <div class="am-hero-cta-group">' . "\n";
        $hero_html .= '    <a href="/audiomaniaeventos/contacto/" class="am-hero-cta am-hero-cta-primary">Contactar</a>' . "\n";
        $hero_html .= '  </div>' . "\n";
        $hero_html .= '</div>' . "\n";
        $hero_html .= '</section>' . "\n";
    }

    // GALERÍA
    elseif ( is_page( 'galeria' ) || is_page( 14 ) ) {
        $gallery_images = array(
            array(
                'file'   => 'Alquiler-altavoz.webp',
                'title'  => 'Equipo de alta gama alquiler',
                'desc'   => 'Equipos de sonido profesional de alta gama para alquiler en eventos en Tenerife.',
                'cat'    => 'sonido',
                'label'  => 'Sonido',
            ),
            array(
                'file'   => 'hinchables-led.webp',
                'title'  => 'Hinchables Led',
                'desc'   => 'Hinchables LED y mobiliario inflable para eventos y fiestas en Tenerife.',
                'cat'    => 'fiestas',
                'label'  => 'Fiestas',
            ),
            array(
                'file'   => 'iluminacion-led-y-efectos.webp',
                'title'  => 'Iluminación LED y Efectos',
                'desc'   => 'Iluminación LED profesional y efectos especiales para eventos en Tenerife.',
                'cat'    => 'iluminacion',
                'label'  => 'Iluminación',
            ),
            array(
                'file'   => 'Equipo-de-alta-gama-alquiler.webp',
                'title'  => 'Alquiler Altavoz',
                'desc'   => 'Alquiler de altavoces y sistemas de sonido profesional para eventos en Tenerife.',
                'cat'    => 'sonido',
                'label'  => 'Sonido',
            ),
            array(
                'file'   => 'dj-para-eventos.webp',
                'title'  => 'Dj para Eventos',
                'desc'   => 'Servicio de DJ profesional para todo tipo de eventos en Tenerife y Canarias.',
                'cat'    => 'dj',
                'label'  => 'DJ',
            ),
            array(
                'file'   => 'Dj-Boda.webp',
                'title'  => 'Dj Boda',
                'desc'   => 'DJ especializado en bodas en Tenerife. Música personalizada y ambientación sonora.',
                'cat'    => 'bodas',
                'label'  => 'Bodas',
            ),
            array(
                'file'   => 'Eventos-Tenerife.webp',
                'title'  => 'Eventos Tenerife',
                'desc'   => 'Servicios completos de sonido y animación para eventos en Tenerife.',
                'cat'    => 'dj',
                'label'  => 'DJ',
            ),
            array(
                'file'   => 'Alquiler-Dj-boot.webp',
                'title'  => 'Alquiler Dj Boot',
                'desc'   => 'Alquiler de DJ con equipo completo: altavoces, mezcladora y controladores.',
                'cat'    => 'dj',
                'label'  => 'DJ',
            ),
            array(
                'file'   => 'Alquiler-nexus-tenerife.webp',
                'title'  => 'Alquiler Nexus',
                'desc'   => 'Alquiler de pantallas Nexus y sistemas LED para eventos en Tenerife.',
                'cat'    => 'iluminacion',
                'label'  => 'Iluminación',
            ),
            array(
                'file'   => 'Mobiliario-eventos-alquiler.webp',
                'title'  => 'Mobiliario Eventos',
                'desc'   => 'Mobiliario profesional para eventos: sillas, mesas, mantelería y photocall.',
                'cat'    => 'corporativo',
                'label'  => 'Corporativo',
            ),
            array(
                'file'   => 'Organizacion-Eventos-Tenerife.webp',
                'title'  => 'Organización de Eventos Tenerife',
                'desc'   => 'Organización integral de eventos en Tenerife: sonido, iluminación, DJ y coordinación completa.',
                'cat'    => 'bodas',
                'label'  => 'Bodas',
            ),
        );

        $gallery_html = '';

        // Hero section
        $gallery_html .= '<section class="am-hero am-hero-single" style="background-image:url(\'' . $base . 'Organizacion-Eventos-Tenerife.webp\');">' . "\n";
        $gallery_html .= '<div class="am-hero-content">' . "\n";
        $gallery_html .= '  <div class="am-hero-badge">📸 Nuestro Portfolio</div>' . "\n";
        $gallery_html .= '  <h1>Galería de <span>Eventos</span></h1>' . "\n";
        $gallery_html .= '  <p class="hero-subtitle">Más de 15 años creando momentos inolvidables en Tenerife y Canarias. Descubre cómo transformamos cada evento con sonido, iluminación y animación profesional.</p>' . "\n";
        $gallery_html .= '  <div class="am-hero-cta-group">' . "\n";
        $gallery_html .= '    <a href="/audiomaniaeventos/contacto/" class="am-hero-cta am-hero-cta-primary">Solicitar Presupuesto</a>' . "\n";
        $gallery_html .= '  </div>' . "\n";
        $gallery_html .= '</div>' . "\n";
        $gallery_html .= '</section>' . "\n";

        // SEO Intro Text
        $gallery_html .= '<section class="am-gallery-seo-intro">' . "\n";
        $gallery_html .= '<div class="am-container">' . "\n";
        $gallery_html .= '  <h2>Galería de Eventos en Tenerife — Audiomania Eventos</h2>' . "\n";
        $gallery_html .= '  <p>En <strong>Audiomania Eventos</strong> llevamos más de 15 años siendo el servicio de referencia para <strong>sonido, iluminación y animación profesional en Tenerife</strong>. Nuestra galería muestra una selección de los eventos que hemos producido: desde <strong>bodas en Tenerife</strong> hasta <strong>eventos corporativos en Canarias</strong>, fiestas privadas, celebraciones y conciertos.</p>' . "\n";
        $gallery_html .= '  <p>Cada montaje que ves aquí representa nuestro compromiso con la excelencia: equipos de última generación, montaje profesional y atención personalizada a cada cliente en toda la isla de Tenerife — Santa Cruz de Tenerife, La Laguna, Los Realejos, Garachico, Puerto de la Cruz, Costa Adeje, Playa de las Américas, Los Cristianos, La Orotava, Icod de los Vinos y toda la isla.</p>' . "\n";
        $gallery_html .= '  <p>Nuestros servicios de <strong>alquiler de sonido para eventos en Tenerife</strong> incluyen altavoces profesionales, mezcladoras, controladores DJ, pantallas LED, iluminación inteligente, efectos visuales, photocall y mobiliario. Todo integrado en paquetes a medida para bodas, cumpleaños, fiestas empresariales, lanzamientos de producto y cualquier tipo de celebración.</p>' . "\n";
        $gallery_html .= '</div>' . "\n";
        $gallery_html .= '</section>' . "\n";

        // Gallery Filters
        $gallery_html .= '<section class="am-gallery-section">' . "\n";
        $gallery_html .= '<div class="am-container">' . "\n";
        $gallery_html .= '  <div class="am-gallery-filters">' . "\n";
        $gallery_html .= '    <button class="am-filter-btn active" data-filter="all">Todas</button>' . "\n";
        $gallery_html .= '    <button class="am-filter-btn" data-filter="bodas">Bodas</button>' . "\n";
        $gallery_html .= '    <button class="am-filter-btn" data-filter="dj">DJ</button>' . "\n";
        $gallery_html .= '    <button class="am-filter-btn" data-filter="sonido">Sonido</button>' . "\n";
        $gallery_html .= '    <button class="am-filter-btn" data-filter="iluminacion">Iluminación</button>' . "\n";
        $gallery_html .= '    <button class="am-filter-btn" data-filter="corporativo">Corporativo</button>' . "\n";
        $gallery_html .= '    <button class="am-filter-btn" data-filter="fiestas">Fiestas</button>' . "\n";
        $gallery_html .= '  </div>' . "\n";

        // Gallery Grid
        $gallery_html .= '  <div class="am-gallery-grid" id="am-gallery-grid">' . "\n";

        foreach ( $gallery_images as $idx => $img ) {
            $url = $base . $img['file'];
            $gallery_html .= '    <div class="am-gallery-item" data-category="' . esc_attr( $img['cat'] ) . '">' . "\n";
            $gallery_html .= '      <div class="am-gallery-item-inner">' . "\n";
            $gallery_html .= '        <div class="am-gallery-img-wrap">' . "\n";
            $gallery_html .= '          <img src="' . esc_url( $url ) . '" alt="' . esc_attr( $img['title'] ) . '" title="' . esc_attr( $img['title'] ) . '" loading="lazy" class="am-gallery-img">' . "\n";
            $gallery_html .= '          <div class="am-gallery-overlay">' . "\n";
            $gallery_html .= '            <span class="am-gallery-zoom-icon">⤢</span>' . "\n";
            $gallery_html .= '            <span class="am-gallery-cat-tag">' . esc_html( $img['label'] ) . '</span>' . "\n";
            $gallery_html .= '          </div>' . "\n";
            $gallery_html .= '        </div>' . "\n";
            $gallery_html .= '        <div class="am-gallery-info">' . "\n";
            $gallery_html .= '          <h3>' . esc_html( $img['title'] ) . '</h3>' . "\n";
            $gallery_html .= '          <p>' . esc_html( $img['desc'] ) . '</p>' . "\n";
            $gallery_html .= '        </div>' . "\n";
            $gallery_html .= '      </div>' . "\n";
            $gallery_html .= '    </div>' . "\n";
        }

        $gallery_html .= '  </div>' . "\n";
        $gallery_html .= '</div>' . "\n";
        $gallery_html .= '</section>' . "\n";

        // SEO Bottom Text
        $gallery_html .= '<section class="am-gallery-seo-bottom">' . "\n";
        $gallery_html .= '<div class="am-container">' . "\n";
        $gallery_html .= '  <div class="am-gallery-seo-content">' . "\n";
        $gallery_html .= '    <h3>¿Por qué elegir Audiomania Eventos para tu evento en Tenerife?</h3>' . "\n";
        $gallery_html .= '    <div class="am-gallery-features">' . "\n";
        $gallery_html .= '      <div class="am-feature-card">' . "\n";
        $gallery_html .= '        <span class="am-feature-icon">🎵</span>' . "\n";
        $gallery_html .= '        <h4>Sonido Profesional</h4>' . "\n";
        $gallery_html .= '        <p>Altavoces line array, mezcladoras digitales y controladores de última generación. Sonido cristalino para bodas, conciertos y eventos corporativos en toda Tenerife.</p>' . "\n";
        $gallery_html .= '      </div>' . "\n";
        $gallery_html .= '      <div class="am-feature-card">' . "\n";
        $gallery_html .= '        <span class="am-feature-icon">💡</span>' . "\n";
        $gallery_html .= '        <h4>Iluminación LED</h4>' . "\n";
        $gallery_html .= '        <p>Pantallas LED, iluminación inteligente, luces robóticas, efectos de humo y láser. Creamos ambientes únicos para cada tipo de evento en Canarias.</p>' . "\n";
        $gallery_html .= '      </div>' . "\n";
        $gallery_html .= '      <div class="am-feature-card">' . "\n";
        $gallery_html .= '        <span class="am-feature-icon">🎧</span>' . "\n";
        $gallery_html .= '        <h4>DJ Expertos</h4>' . "\n";
        $gallery_html .= '        <p>DJs profesionales con experiencia en bodas, fiestas privadas y eventos corporativos. Música a medida, animación y control total del evento.</p>' . "\n";
        $gallery_html .= '      </div>' . "\n";
        $gallery_html .= '      <div class="am-feature-card">' . "\n";
        $gallery_html .= '        <span class="am-feature-icon">📋</span>' . "\n";
        $gallery_html .= '        <h4>Organización Integral</h4>' . "\n";
        $gallery_html .= '        <p>Desde el montaje hasta el desmontaje, nos encargamos de todo: sonorización, iluminación, mobiliario, photocall y coordinación completa del evento.</p>' . "\n";
        $gallery_html .= '      </div>' . "\n";
        $gallery_html .= '    </div>' . "\n";
        $gallery_html .= '    <div class="am-gallery-cta">' . "\n";
        $gallery_html .= '      <h3>¿Listo para tu evento en Tenerife?</h3>' . "\n";
        $gallery_html .= '      <p>Contacta con nosotros y te prepararemos un presupuesto personalizado sin compromiso. Más de 15 años de experiencia nos avalan.</p>' . "\n";
        $gallery_html .= '      <a href="/audiomaniaeventos/contacto/" class="am-hero-cta am-hero-cta-primary">Contactar Ahora</a>' . "\n";
        $gallery_html .= '    </div>' . "\n";
        $gallery_html .= '  </div>' . "\n";
        $gallery_html .= '</div>' . "\n";
        $gallery_html .= '</section>' . "\n";

        $hero_html = $gallery_html;
    }

    // CONTACTO
    elseif ( is_page( 'contacto' ) || is_page( 15 ) ) {
        // Hero
        $hero_html = '<section class="am-hero am-hero-single" style="background-image:url(\'' . $base . 'Organizacion-Eventos-Tenerife.webp\');">' . "\n";
        $hero_html .= '<div class="am-hero-content">' . "\n";
        $hero_html .= '  <div class="am-hero-badge">📞 Contacto Directo</div>' . "\n";
        $hero_html .= '  <h1>Hablemos de tu <span>Próximo Evento</span></h1>' . "\n";
        $hero_html .= '  <p class="hero-subtitle">¿Tienes un evento en mente? Cuéntanos tu idea y te preparamos un presupuesto personalizado sin compromiso en menos de 24 horas.</p>' . "\n";
        $hero_html .= '  <div class="am-hero-cta-group">' . "\n";
        $hero_html .= '    <a href="https://wa.me/34669621139?text=Hola%2C%20me%20interesa%20un%20presupuesto%20para%20mi%20evento" class="am-hero-cta am-hero-cta-whatsapp" target="_blank" rel="noopener">WhatsApp Directo</a>' . "\n";
        $hero_html .= '    <a href="tel:+34669621139" class="am-hero-cta am-hero-cta-primary">Llamar Ahora</a>' . "\n";
        $hero_html .= '  </div>' . "\n";
        $hero_html .= '  <div class="am-hero-features">' . "\n";
        $hero_html .= '    <div class="am-feature-item">⚡ Respuesta en 24h</div>' . "\n";
        $hero_html .= '    <div class="am-feature-item">📋 Presupuesto Gratis</div>' . "\n";
        $hero_html .= '    <div class="am-feature-item">📍 Toda Canarias</div>' . "\n";
        $hero_html .= '  </div>' . "\n";
        $hero_html .= '</div>' . "\n";
        $hero_html .= '</section>' . "\n";

        // === SECCIÓN: FORMULARIO + INFO DE CONTACTO ===
        $contact_html = '<section class="am-contact-section">' . "\n";
        $contact_html .= '<div class="am-container">' . "\n";

        // Contact Layout: Form + Info
        $contact_html .= '<div class="am-contact-layout">' . "\n";

        // LEFT: Formulario
        $contact_html .= '  <div class="am-contact-form-wrapper">' . "\n";
        $contact_html .= '    <h2>Envíanos un Mensaje</h2>' . "\n";
        $contact_html .= '    <p class="am-contact-form-desc">Rellena el formulario y te responderemos en menos de 24 horas con un presupuesto personalizado.</p>' . "\n";
        $contact_html .= '    <form class="am-contact-form" action="' . esc_url( home_url( '/audiomaniaeventos/contacto/' ) ) . '" method="POST" novalidate>' . "\n";
        $contact_html .= '      <div class="am-form-row">' . "\n";
        $contact_html .= '        <div class="am-form-group">' . "\n";
        $contact_html .= '          <label for="am-name">Nombre completo <span class="am-required">*</span></label>' . "\n";
        $contact_html .= '          <input type="text" id="am-name" name="am_name" required placeholder="Tu nombre completo">' . "\n";
        $contact_html .= '        </div>' . "\n";
        $contact_html .= '        <div class="am-form-group">' . "\n";
        $contact_html .= '          <label for="am-phone">Teléfono <span class="am-required">*</span></label>' . "\n";
        $contact_html .= '          <input type="tel" id="am-phone" name="am_phone" required placeholder="+34 600 000 000">' . "\n";
        $contact_html .= '        </div>' . "\n";
        $contact_html .= '      </div>' . "\n";
        $contact_html .= '      <div class="am-form-group">' . "\n";
        $contact_html .= '        <label for="am-email">Email <span class="am-required">*</span></label>' . "\n";
        $contact_html .= '        <input type="email" id="am-email" name="am_email" required placeholder="tu@email.com">' . "\n";
        $contact_html .= '      </div>' . "\n";
        $contact_html .= '      <div class="am-form-group">' . "\n";
        $contact_html .= '        <label for="am-event-type">Tipo de evento</label>' . "\n";
        $contact_html .= '        <select id="am-event-type" name="am_event_type">' . "\n";
        $contact_html .= '          <option value="">Selecciona una opción</option>' . "\n";
        $contact_html .= '          <option value="boda">💒 Boda</option>' . "\n";
        $contact_html .= '          <option value="corporativo">🏢 Evento Corporativo</option>' . "\n";
        $contact_html .= '          <option value="fiesta">🎉 Fiesta Privada</option>' . "\n";
        $contact_html .= '          <option value="dj">🎧 DJ para Evento</option>' . "\n";
        $contact_html .= '          <option value="sonido">🔊 Alquiler de Sonido</option>' . "\n";
        $contact_html .= '          <option value="iluminacion">💡 Iluminación LED</option>' . "\n";
        $contact_html .= '          <option value="photocall">📸 Photocall</option>' . "\n";
        $contact_html .= '          <option value="completo">⭐ Paquete Completo</option>' . "\n";
        $contact_html .= '          <option value="otro">📋 Otro</option>' . "\n";
        $contact_html .= '        </select>' . "\n";
        $contact_html .= '      </div>' . "\n";
        $contact_html .= '      <div class="am-form-row">' . "\n";
        $contact_html .= '        <div class="am-form-group">' . "\n";
        $contact_html .= '          <label for="am-event-date">Fecha del evento</label>' . "\n";
        $contact_html .= '          <input type="date" id="am-event-date" name="am_event_date">' . "\n";
        $contact_html .= '        </div>' . "\n";
        $contact_html .= '        <div class="am-form-group">' . "\n";
        $contact_html .= '          <label for="am-guests">Nº de invitados</label>' . "\n";
        $contact_html .= '          <select id="am-guests" name="am_guests">' . "\n";
        $contact_html .= '            <option value="">Selecciona</option>' . "\n";
        $contact_html .= '            <option value="50-100">50 - 100</option>' . "\n";
        $contact_html .= '            <option value="100-200">100 - 200</option>' . "\n";
        $contact_html .= '            <option value="200-500">200 - 500</option>' . "\n";
        $contact_html .= '            <option value="500+">500+</option>' . "\n";
        $contact_html .= '          </select>' . "\n";
        $contact_html .= '        </div>' . "\n";
        $contact_html .= '      </div>' . "\n";
        $contact_html .= '      <div class="am-form-group">' . "\n";
        $contact_html .= '        <label for="am-message">Mensaje <span class="am-required">*</span></label>' . "\n";
        $contact_html .= '        <textarea id="am-message" name="am_message" rows="5" required placeholder="Cuéntanos los detalles de tu evento: lugar, estilo, servicios que necesitas..."></textarea>' . "\n";
        $contact_html .= '      </div>' . "\n";
        $contact_html .= '      <button type="submit" class="am-submit-btn"><span>Enviar Mensaje</span> <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg></button>' . "\n";
        $contact_html .= '    </form>' . "\n";
        $contact_html .= '  </div>' . "\n";

        // RIGHT: Información de Contacto
        $contact_html .= '  <div class="am-contact-info-wrapper">' . "\n";
        $contact_html .= '    <h2>Información de Contacto</h2>' . "\n";
        $contact_html .= '    <p class="am-contact-info-desc">Estamos aquí para ayudarte. Pídenos presupuesto sin compromiso.</p>' . "\n";

        $contact_html .= '    <div class="am-contact-cards">' . "\n";

        // Teléfono Card
        $contact_html .= '      <div class="am-contact-card am-contact-card-phone">' . "\n";
        $contact_html .= '        <div class="am-contact-card-icon">📞</div>' . "\n";
        $contact_html .= '        <div class="am-contact-card-content">' . "\n";
        $contact_html .= '          <h3>Teléfono</h3>' . "\n";
        $contact_html .= '          <p><a href="tel:+34669621139">+34 669 621 139</a></p>' . "\n";
        $contact_html .= '          <a href="tel:+34669621139" class="am-contact-card-link">Llamar Ahora</a>' . "\n";
        $contact_html .= '        </div>' . "\n";
        $contact_html .= '      </div>' . "\n";

        // Email Card
        $contact_html .= '      <div class="am-contact-card am-contact-card-email">' . "\n";
        $contact_html .= '        <div class="am-contact-card-icon">📧</div>' . "\n";
        $contact_html .= '        <div class="am-contact-card-content">' . "\n";
        $contact_html .= '          <h3>Email</h3>' . "\n";
        $contact_html .= '          <p><a href="mailto:carlostoledodj@gmail.com">carlostoledodj@gmail.com</a></p>' . "\n";
        $contact_html .= '          <a href="mailto:carlostoledodj@gmail.com" class="am-contact-card-link">Enviar Email</a>' . "\n";
        $contact_html .= '        </div>' . "\n";
        $contact_html .= '      </div>' . "\n";

        // WhatsApp Card
        $contact_html .= '      <div class="am-contact-card am-contact-card-whatsapp">' . "\n";
        $contact_html .= '        <div class="am-contact-card-icon">💬</div>' . "\n";
        $contact_html .= '        <div class="am-contact-card-content">' . "\n";
        $contact_html .= '          <h3>WhatsApp</h3>' . "\n";
        $contact_html .= '          <p><a href="https://wa.me/34669621139" target="_blank" rel="noopener">+34 669 621 139</a></p>' . "\n";
        $contact_html .= '          <a href="https://wa.me/34669621139?text=Hola%2C%20me%20interesa%20un%20presupuesto" class="am-contact-card-link" target="_blank" rel="noopener">Escribir por WhatsApp</a>' . "\n";
        $contact_html .= '        </div>' . "\n";
        $contact_html .= '      </div>' . "\n";

        // Ubicación Card
        $contact_html .= '      <div class="am-contact-card am-contact-card-location">' . "\n";
        $contact_html .= '        <div class="am-contact-card-icon">📍</div>' . "\n";
        $contact_html .= '        <div class="am-contact-card-content">' . "\n";
        $contact_html .= '          <h3>Ubicación</h3>' . "\n";
        $contact_html .= '          <p>Tenerife, Canarias, España</p>' . "\n";
        $contact_html .= '          <span class="am-contact-card-link">Cubrimos toda la isla</span>' . "\n";
        $contact_html .= '        </div>' . "\n";
        $contact_html .= '      </div>' . "\n";

        // Horario Card
        $contact_html .= '      <div class="am-contact-card am-contact-card-hours">' . "\n";
        $contact_html .= '        <div class="am-contact-card-icon">🕐</div>' . "\n";
        $contact_html .= '        <div class="am-contact-card-content">' . "\n";
        $contact_html .= '          <h3>Horario</h3>' . "\n";
        $contact_html .= '          <p>Lun - Vie: 9:00 - 20:00</p>' . "\n";
        $contact_html .= '          <span class="am-contact-card-link">Sáb y Dom: Pre-evento</span>' . "\n";
        $contact_html .= '        </div>' . "\n";
        $contact_html .= '      </div>' . "\n";

        $contact_html .= '    </div>' . "\n";
        $contact_html .= '  </div>' . "\n";

        $contact_html .= '</div>' . "\n"; // end contact layout

        $contact_html .= '</div>' . "\n"; // end container
        $contact_html .= '</section>' . "\n"; // end contact section

        $hero_html .= $contact_html;

        // Return hero_html only, DO NOT append Elementor content
        return $hero_html;
    }

    // SOBRE NOSOTROS
    elseif ( is_page( 'sobre-nosotros' ) || is_page( 16 ) ) {
        $about_html = '';

        // Hero — Alquiler-nexus-tenerife.webp
        $about_html .= '<section class="am-hero am-hero-single" style="background-image:url(\'' . $base . 'Alquiler-nexus-tenerife.webp\');">' . "\n";
        $about_html .= '<div class="am-hero-content">' . "\n";
        $about_html .= '  <div class="am-hero-badge">Nuestra Historia</div>' . "\n";
        $about_html .= '  <h1>Más de 10 años haciendo que cada evento sea <span>inolvidable</span></h1>' . "\n";
        $about_html .= '</div>' . "\n";
        $about_html .= '</section>' . "\n";

        // Historia
        $about_html .= '<section class="am-about-section">' . "\n";
        $about_html .= '<div class="am-container">' . "\n";

        $about_html .= '  <div class="am-about-intro">' . "\n";
        $about_html .= '    <h2>Nuestra Historia</h2>' . "\n";
        $about_html .= '    <p>AudioManía Eventos nació de la pasión por la tecnología sonora y la iluminación profesional. Desde nuestros inicios, hemos trabajado con bodas, eventos corporativos, conciertos y fiestas privadas en toda España.</p>' . "\n";
        $about_html .= '    <p>Contamos con los mejores equipos del mercado: sonido L-Acoustics, iluminación Grand MA2, pantallas LED de última generación y DJs profesionales. Nuestro equipo técnico se encarga de todo: diseño, montaje, operación y desmontaje.</p>' . "\n";
        $about_html .= '    <p>¿Qué nos diferencia?</p>' . "\n";
        $about_html .= '  </div>' . "\n";

        // Cards — same style as contact page
        $about_html .= '  <div class="am-about-cards">' . "\n";

        // Card 1 — Equipos (blue)
        $about_html .= '    <div class="am-about-card am-about-card-equipos">' . "\n";
        $about_html .= '      <div class="am-about-card-icon">🎵</div>' . "\n";
        $about_html .= '      <div class="am-about-card-content">' . "\n";
        $about_html .= '        <h3>Equipos de gama alta</h3>' . "\n";
        $about_html .= '        <p>Solo trabajamos con marcas profesionales: L-Acoustics, Pioneer, Linsn, Grand MA2.</p>' . "\n";
        $about_html .= '      </div>' . "\n";
        $about_html .= '    </div>' . "\n";

        // Card 2 — Operarios (green)
        $about_html .= '    <div class="am-about-card am-about-card-operarios">' . "\n";
        $about_html .= '      <div class="am-about-card-icon">👥</div>' . "\n";
        $about_html .= '      <div class="am-about-card-content">' . "\n";
        $about_html .= '        <h3>Operarios profesionales</h3>' . "\n";
        $about_html .= '        <p>Equipo técnico certificado con años de experiencia en directo.</p>' . "\n";
        $about_html .= '      </div>' . "\n";
        $about_html .= '    </div>' . "\n";

        // Card 3 — Servicio integral (purple)
        $about_html .= '    <div class="am-about-card am-about-card-servicio">' . "\n";
        $about_html .= '      <div class="am-about-card-icon">🔧</div>' . "\n";
        $about_html .= '      <div class="am-about-card-content">' . "\n";
        $about_html .= '        <h3>Servicio integral</h3>' . "\n";
        $about_html .= '        <p>Alquiler, montaje, operación y desmontaje. Nos encargamos de todo.</p>' . "\n";
        $about_html .= '      </div>' . "\n";
        $about_html .= '    </div>' . "\n";

        $about_html .= '  </div>' . "\n"; // end am-about-cards

        $about_html .= '</div>' . "\n"; // end container
        $about_html .= '</section>' . "\n"; // end am-about-section

        $hero_html = $about_html;
    }

    // WOOCOMMERCE ARCHIVE
    elseif ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
        $hero_html = '<section class="am-hero am-hero-single" style="background-image:url(\'' . $base . 'hero-evento-bg.webp\');">' . "\n";
        $hero_html .= '<div class="am-hero-content">' . "\n";
        $hero_html .= '  <div class="am-hero-badge">Tienda de Equipos</div>' . "\n";
        $hero_html .= '  <h1>Nuestra <span>Tienda</span></h1>' . "\n";
        $hero_html .= '  <p class="hero-subtitle">Alquiler de equipos profesionales: altavoces, mezcladoras, iluminación LED, estructuras y más.</p>' . "\n";
        $hero_html .= '  <div class="am-hero-cta-group">' . "\n";
        $hero_html .= '    <a href="/audiomaniaeventos/servicios/" class="am-hero-cta am-hero-cta-primary">Ver Servicios</a>' . "\n";
        $hero_html .= '  </div>' . "\n";
        $hero_html .= '</div>' . "\n";
        $hero_html .= '</section>' . "\n";
    }

    if ( $hero_html ) {
        return $hero_html . $content;
    }

    return $content;
}

/**
 * ------------------------------------------------------------------
 * 6. CUSTOM HEADER
 * ------------------------------------------------------------------
 */
function audiomania_child_header() {
    ?>
    <header class="site-header am-header" role="banner">
        <div class="header-inner">
            <div class="header-left">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-branding">
                    <span class="brand-main">AUDIO</span><span class="brand-dot">.</span><span class="brand-sub">eventos</span>
                </a>
            </div>
            <nav class="header-nav" role="navigation" aria-label="<?php esc_attr_e( 'Menú principal', 'audiomania-events-child' ); ?>">
                <?php
                if ( has_nav_menu( 'primary' ) ) {
                    wp_nav_menu( array(
                        'theme_location'  => 'primary',
                        'container'       => false,
                        'menu_class'      => 'nav-menu',
                        'fallback_cb'     => 'audiomania_child_fallback_menu',
                        'depth'           => 1,
                    ) );
                } else {
                    audiomania_child_fallback_menu();
                }
                ?>
            </nav>
            <button class="mobile-menu-toggle" aria-label="Abrir menú" aria-expanded="false">
                <span class="hamburger-icon">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </span>
            </button>
        </div>
    </header>
    <?php
}

function audiomania_child_fallback_menu() {
    ?>
    <ul class="nav-menu">
        <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Inicio', 'audiomania-events-child' ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/servicios/' ) ); ?>"><?php esc_html_e( 'Servicios', 'audiomania-events-child' ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/reservar/' ) ); ?>"><?php esc_html_e( 'Reservar', 'audiomania-events-child' ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/galeria/' ) ); ?>"><?php esc_html_e( 'Galería', 'audiomania-events-child' ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/sobre-nosotros/' ) ); ?>"><?php esc_html_e( 'Sobre Nosotros', 'audiomania-events-child' ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>"><?php esc_html_e( 'Contacto', 'audiomania-events-child' ); ?></a></li>
    </ul>
    <?php
}

/**
 * ------------------------------------------------------------------
 * 7. CUSTOM FOOTER
 * ------------------------------------------------------------------
 */
function audiomania_child_footer() {
    ?>
    <footer class="site-footer" role="contentinfo">
        <div class="footer-grid">
            <div class="footer-col">
                <h3><?php esc_html_e( 'Audiomania Eventos', 'audiomania-events-child' ); ?></h3>
                <p><?php esc_html_e( 'Servicios profesionales de sonido, iluminación y animación para eventos en Tenerife y toda Canarias.', 'audiomania-events-child' ); ?></p>
            </div>
            <div class="footer-col">
                <h4><?php esc_html_e( 'Servicios', 'audiomania-events-child' ); ?></h4>
                <ul class="footer-links">
                    <li><a href="<?php echo esc_url( home_url( '/servicios/#dj' ) ); ?>"><?php esc_html_e( 'DJ para Eventos', 'audiomania-events-child' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/servicios/#sonido' ) ); ?>"><?php esc_html_e( 'Alquiler de Sonido', 'audiomania-events-child' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/servicios/#iluminacion' ) ); ?>"><?php esc_html_e( 'Iluminación LED', 'audiomania-events-child' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/servicios/#photocall' ) ); ?>"><?php esc_html_e( 'Photocall', 'audiomania-events-child' ); ?></a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4><?php esc_html_e( 'Contacto', 'audiomania-events-child' ); ?></h4>
                <ul class="footer-links">
                    <li>📞 <a href="tel:+34669621139">+34 669 621 139</a></li>
                    <li>📧 <a href="mailto:carlostoledodj@gmail.com">carlostoledodj@gmail.com</a></li>
                    <li>📍 Tenerife, Canarias, España</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date( 'Y' ); ?> Audiomania Eventos. <?php esc_html_e( 'Todos los derechos reservados.', 'audiomania-events-child' ); ?></p>
        </div>
    </footer>
    <?php
}

/**
 * ------------------------------------------------------------------
 * 8. WHATSAPP FLOATING BUTTON
 * ------------------------------------------------------------------
 */
function audiomania_child_whatsapp_button() {
    if ( is_admin() ) return;
    $number = '34669621139';
    $url = 'https://wa.me/' . $number;
    ?>
    <a href="<?php echo esc_url( $url ); ?>" class="am-whatsapp-float" target="_blank" rel="noopener noreferrer" aria-label="Contactar por WhatsApp">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>
    <?php
}

/**
 * ------------------------------------------------------------------
 * 9. WOOCOMMERCE OVERRIDES
 * ------------------------------------------------------------------
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

function audiomania_woocommerce_before_main_content() {
    echo '<main id="primary" class="site-main"><div class="woocommerce-wrapper">';
}
add_action( 'woocommerce_before_main_content', 'audiomania_woocommerce_before_main_content', 10 );

function audiomania_woocommerce_after_main_content() {
    echo '</div></main>';
}
add_action( 'woocommerce_after_main_content', 'audiomania_woocommerce_after_main_content', 10 );

add_filter( 'loop_shop_columns', function() { return 3; } );
add_filter( 'loop_shop_per_page', function( $cols ) { return 12; }, 20 );

add_action( 'wp_enqueue_scripts', function() {
    wp_dequeue_style( 'woocommerce-smallscreen' );
    wp_dequeue_style( 'woocommerce-general' );
}, 100 );

/**
 * ------------------------------------------------------------------
 * 10. MOBILE MENU JS
 * ------------------------------------------------------------------
 */
function audiomania_child_mobile_menu_js() {
    if ( ! is_admin() ) {
        ?>
        <script>
        (function() {
            var toggle = document.querySelector('.mobile-menu-toggle');
            var nav = document.querySelector('.header-nav');
            if (!toggle || !nav) return;

            toggle.addEventListener('click', function() {
                var isOpen = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', !isOpen);
                nav.classList.toggle('nav-open');
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    toggle.setAttribute('aria-expanded', 'false');
                    nav.classList.remove('nav-open');
                }
            });

            window.addEventListener('resize', function() {
                if (window.innerWidth > 900) {
                    toggle.setAttribute('aria-expanded', 'false');
                    nav.classList.remove('nav-open');
                }
            });
        })();
        </script>
        <?php
    }
}
add_action( 'wp_footer', 'audiomania_child_mobile_menu_js', 99 );

/**
 * ------------------------------------------------------------------
 * 11. SHORTCODES
 * ------------------------------------------------------------------
 */
function audiomania_stats_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'count'  => '0',
        'label'  => '',
        'suffix' => '',
    ), $atts, 'am_stats' );

    return '
        <div class="stat-item">
            <span class="stat-number">' . esc_html( $atts['count'] ) . esc_html( $atts['suffix'] ) . '</span>
            <span class="stat-label">' . esc_html( $atts['label'] ) . '</span>
        </div>
    ';
}
add_shortcode( 'am_stats', 'audiomania_stats_shortcode' );

function audiomania_services_shortcode( $atts ) {
    ob_start();
    $atts = shortcode_atts( array(
        'columns' => '3',
    ), $atts, 'am_services' );
    ?>
    <div class="services-grid" style="grid-template-columns: repeat(<?php echo intval( $atts['columns'] ); ?>, 1fr);">
        <?php echo do_shortcode( '[woocommerce_products]' ); ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'am_services', 'audiomania_services_shortcode' );

/**
 * ------------------------------------------------------------------
 * 12. PERFORMANCE
 * ------------------------------------------------------------------
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'wp_lazy_loading_enabled', '__return_true' );

/**
 * ------------------------------------------------------------------
 * 13. SECURITY
 * ------------------------------------------------------------------
 */
add_filter( 'login_errors', function() { return null; } );
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'template_redirect', 'rest_output_link_header', 11 );
