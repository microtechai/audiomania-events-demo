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

    /* === HOME CAROUSEL === */
    .am-hero-carousel {
        min-height: 100vh;
        position: relative;
    }

    .am-carousel-slide {
        position: absolute;
        inset: 0;
        opacity: 0;
        transition: opacity 1.2s ease-in-out, transform 1.2s ease-in-out;
        transform: scale(1.05);
    }

    .am-carousel-slide.active {
        opacity: 1;
        transform: scale(1);
        z-index: 2;
    }

    .am-carousel-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .am-carousel-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(
            180deg,
            rgba(3,3,8,0.65) 0%,
            rgba(3,3,8,0.45) 40%,
            rgba(3,3,8,0.7) 100%
        );
        z-index: 3;
    }

    .am-carousel-dots {
        position: absolute;
        bottom: 32px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 10px;
        z-index: 10;
    }

    .am-carousel-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: rgba(255,255,255,0.35);
        border: 2px solid rgba(255,255,255,0.5);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .am-carousel-dot.active {
        background: #fff;
        border-color: #fff;
        transform: scale(1.3);
        box-shadow: 0 0 12px rgba(77,124,255,0.6);
    }

    .am-carousel-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        background: linear-gradient(90deg, #4d7cff, #a855f7, #ec4899);
        z-index: 10;
        transition: width linear;
        box-shadow: 0 0 10px rgba(77,124,255,0.5);
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
        .am-hero-carousel { min-height: 85vh; }
        .am-hero-content { padding: 80px 20px 60px; }
        .am-hero-content h1 { font-size: clamp(1.8rem, 8vw, 2.8rem); }
        .am-hero-cta-group { flex-direction: column; align-items: center; }
        .am-hero-cta { width: 100%; max-width: 300px; justify-content: center; }
        .am-hero-single { min-height: 55vh; }
    }

    @media (max-width: 480px) {
        .am-hero-carousel { min-height: 75vh; }
        .am-hero-content { padding: 60px 16px 50px; }
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

    // HOME — Carousel con 3 imágenes
    if ( is_front_page() || is_home() ) {
        $carousel = '<section class="am-hero am-hero-carousel">' . "\n";
        $carousel .= '<!-- Slide 1 -->' . "\n";
        $carousel .= '<div class="am-carousel-slide active" style="background-image:url(\'' . $base . 'hero-hero-bg-1.webp\');">' . "\n";
        $carousel .= '  <div class="am-carousel-overlay"></div>' . "\n";
        $carousel .= '</div>' . "\n";
        $carousel .= '<!-- Slide 2 -->' . "\n";
        $carousel .= '<div class="am-carousel-slide" style="background-image:url(\'' . $base . 'hero-party-bg.webp\');">' . "\n";
        $carousel .= '  <div class="am-carousel-overlay"></div>' . "\n";
        $carousel .= '</div>' . "\n";
        $carousel .= '<!-- Slide 3 -->' . "\n";
        $carousel .= '<div class="am-carousel-slide" style="background-image:url(\'' . $base . 'hero-fiesta-bg.webp\');">' . "\n";
        $carousel .= '  <div class="am-carousel-overlay"></div>' . "\n";
        $carousel .= '</div>' . "\n";

        $carousel .= '<div class="am-hero-content">' . "\n";
        $carousel .= '  <div class="am-hero-badge">🎵 Eventos Profesionales en Tenerife</div>' . "\n";
        $carousel .= '  <h1>Sonido, Iluminación y <span>Animación</span> para tu Evento</h1>' . "\n";
        $carousel .= '  <p class="hero-subtitle">DJ profesional, alquiler de sonido, iluminación LED, photocall y más. Todo lo que necesitas para una fiesta inolvidable en Tenerife.</p>' . "\n";
        $carousel .= '  <div class="am-hero-cta-group">' . "\n";
        $carousel .= '    <a href="/audiomaniaeventos/reservar/" class="am-hero-cta am-hero-cta-primary">Solicitar Presupuesto <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg></a>' . "\n";
        $carousel .= '    <a href="/audiomaniaeventos/servicios/" class="am-hero-cta am-hero-cta-secondary">Ver Servicios</a>' . "\n";
        $carousel .= '  </div>' . "\n";
        $carousel .= '</div>' . "\n";

        $carousel .= '<div class="am-carousel-dots">' . "\n";
        $carousel .= '  <span class="am-carousel-dot active" data-slide="0"></span>' . "\n";
        $carousel .= '  <span class="am-carousel-dot" data-slide="1"></span>' . "\n";
        $carousel .= '  <span class="am-carousel-dot" data-slide="2"></span>' . "\n";
        $carousel .= '</div>' . "\n";
        $carousel .= '<div class="am-carousel-progress" style="width:0%;"></div>' . "\n";
        $carousel .= '<div class="am-scroll-indicator">' . "\n";
        $carousel .= '  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.5)" stroke-width="2"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>' . "\n";
        $carousel .= '  <span>Scroll</span>' . "\n";
        $carousel .= '</div>' . "\n";
        $carousel .= '</section>' . "\n";

        $hero_html = $carousel;
    }

    // SERVICIOS
    elseif ( is_page( 'servicios' ) || is_page( 12 ) ) {
        $hero_html = '<section class="am-hero am-hero-single" style="background-image:url(\'' . $base . 'hero-sonido-bg.webp\');">' . "\n";
        $hero_html .= '<div class="am-hero-content">' . "\n";
        $hero_html .= '  <div class="am-hero-badge">Nuestros Servicios</div>' . "\n";
        $hero_html .= '  <h1>Servicios <span>Profesionales</span></h1>' . "\n";
        $hero_html .= '  <p class="hero-subtitle">Equipos profesionales de sonido, iluminación y animación para bodas, eventos corporativos, fiestas y celebraciones.</p>' . "\n";
        $hero_html .= '  <div class="am-hero-cta-group">' . "\n";
        $hero_html .= '    <a href="/audiomaniaeventos/reservar/" class="am-hero-cta am-hero-cta-primary">Reservar Ahora</a>' . "\n";
        $hero_html .= '  </div>' . "\n";
        $hero_html .= '</div>' . "\n";
        $hero_html .= '</section>' . "\n";
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
        $hero_html = '<section class="am-hero am-hero-single" style="background-image:url(\'' . $base . 'hero-fiesta-bg.webp\');">' . "\n";
        $hero_html .= '<div class="am-hero-content">' . "\n";
        $hero_html .= '  <div class="am-hero-badge">Galería de Eventos</div>' . "\n";
        $hero_html .= '  <h1>Galería de <span>Eventos</span></h1>' . "\n";
        $hero_html .= '  <p class="hero-subtitle">Mira cómo transformamos espacios con nuestros equipos de sonido, iluminación y animación profesional.</p>' . "\n";
        $hero_html .= '  <div class="am-hero-cta-group">' . "\n";
        $hero_html .= '    <a href="/audiomaniaeventos/contacto/" class="am-hero-cta am-hero-cta-primary">Contactar Ahora</a>' . "\n";
        $hero_html .= '  </div>' . "\n";
        $hero_html .= '</div>' . "\n";
        $hero_html .= '</section>' . "\n";
    }

    // CONTACTO
    elseif ( is_page( 'contacto' ) || is_page( 15 ) ) {
        $hero_html = '<section class="am-hero am-hero-single" style="background-image:url(\'' . $base . 'hero-fondo-oscuro-bg-1.webp\');">' . "\n";
        $hero_html .= '<div class="am-hero-content">' . "\n";
        $hero_html .= '  <div class="am-hero-badge">Contacto</div>' . "\n";
        $hero_html .= '  <h1>Hablemos de tu <span>Proyecto</span></h1>' . "\n";
        $hero_html .= '  <p class="hero-subtitle">¿Tienes un evento en mente? Cuéntanos tu idea y te preparamos un presupuesto a medida.</p>' . "\n";
        $hero_html .= '  <div class="am-hero-cta-group">' . "\n";
        $hero_html .= '    <a href="/audiomaniaeventos/contacto/" class="am-hero-cta am-hero-cta-primary">Enviar Mensaje</a>' . "\n";
        $hero_html .= '  </div>' . "\n";
        $hero_html .= '</div>' . "\n";
        $hero_html .= '</section>' . "\n";
    }

    // SOBRE NOSOTROS
    elseif ( is_page( 'sobre-nosotros' ) || is_page( 16 ) ) {
        $hero_html = '<section class="am-hero am-hero-single" style="background-image:url(\'' . $base . 'hero-corporativo-bg-1.webp\');">' . "\n";
        $hero_html .= '<div class="am-hero-content">' . "\n";
        $hero_html .= '  <div class="am-hero-badge">Sobre Nosotros</div>' . "\n";
        $hero_html .= '  <h1>+15 Años <span>Creando Eventos</span></h1>' . "\n";
        $hero_html .= '  <p class="hero-subtitle">Más de 15 años bringing la mejor experiencia de sonido e iluminación a eventos en Tenerife y Canarias.</p>' . "\n";
        $hero_html .= '  <div class="am-hero-cta-group">' . "\n";
        $hero_html .= '    <a href="/audiomaniaeventos/servicios/" class="am-hero-cta am-hero-cta-primary">Nuestros Servicios</a>' . "\n";
        $hero_html .= '  </div>' . "\n";
        $hero_html .= '</div>' . "\n";
        $hero_html .= '</section>' . "\n";
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
                    <li>📧 <a href="mailto:info@audiomaniaeventos.com">info@audiomaniaeventos.com</a></li>
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
