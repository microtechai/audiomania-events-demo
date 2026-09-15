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
 * THEME: Audiomania Eventos Child
 * Developed by: MicroTech AI (microtechai.es)
 * Version: 6.0.4
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
 * 4. SEO — META TAGS, TITLE, OG, TWITTER, CANONICAL
 * ------------------------------------------------------------------
 */
function audiomania_child_seo_head() {
    ?>
    <!-- Title & Description -->
    <?php if ( is_front_page() || is_home() ) : ?>
        <meta name="description" content="DJ, sonido e iluminación profesional para bodas, fiestas privadas y eventos corporativos en Tenerife. Más de 15 años y muchos más de 500 eventos. Presupuesto en menos de 24 horas.">
    <?php elseif ( is_page( 'servicios' ) || is_page( 12 ) ) : ?>
        <meta name="description" content="Servicios de DJ, sonido, iluminación, pantallas LED, photocall, mobiliario y efectos para bodas, fiestas y eventos corporativos en Tenerife.">
    <?php elseif ( is_page( 'reservar' ) || is_page( 13 ) ) : ?>
        <meta name="description" content="Pide presupuesto para DJ, sonido, iluminación y producción técnica en Tenerife. Te respondemos en menos de 24 horas.">
    <?php elseif ( is_page( 'galeria' ) || is_page( 14 ) ) : ?>
        <meta name="description" content="Galería de eventos de Audiomania: bodas, fiestas, eventos corporativos con DJ, sonido e iluminación profesional en Tenerife y Canarias.">
    <?php elseif ( is_page( 'sobre-nosotros' ) || is_page( 16 ) ) : ?>
        <meta name="description" content="Más de 15 años y más de 500 eventos con DJ, sonido, iluminación, pantallas LED, photocall y efectos en Tenerife.">
    <?php elseif ( is_page( 'contacto' ) || is_page( 15 ) ) : ?>
        <meta name="description" content="Contacta con Audiomania Eventos. Teléfono: +34 669 621 139. Email: carlostoledodj@gmail.com. Tenerife, Canarias.">
    <?php elseif ( is_woocommerce() ) : ?>
        <meta name="description" content="Equipamiento profesional de sonido, iluminación y DJ para eventos en Tenerife.">
    <?php else : ?>
        <meta name="description" content="Audiomania Eventos – Servicios profesionales de sonido, iluminación y animación para eventos en Tenerife y Canarias.">
    <?php endif; ?>


    <!-- Open Graph -->
    <meta property="og:locale" content="es_ES">
    <meta property="og:type" content="<?php echo is_front_page() ? 'website' : 'article'; ?>">
    <meta property="og:title" content="<?php echo is_front_page() ? 'Audiomania Eventos – DJ, Sonido e Iluminación en Tenerife' : wp_title( '', false ); ?>">
    <meta property="og:description" content="Audiomania Eventos: servicios profesionales de DJ, sonido, iluminación LED y photocall para bodas, fiestas y eventos corporativos en Tenerife y toda Canarias.">
    <meta property="og:url" content="<?php echo esc_url( is_front_page() ? home_url( '/' ) : get_permalink() ); ?>">
    <meta property="og:site_name" content="Audiomania Eventos">
    <meta property="og:image" content="<?php echo esc_url( home_url( '/wp-content/uploads/2026/08/Eventos-Tenerife.webp' ) ); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="Audiomania Eventos - DJ y Sonido Profesional en Tenerife">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Audiomania Eventos – DJ, Sonido e Iluminación en Tenerife">
    <meta name="twitter:description" content="Servicios profesionales de DJ, sonido, iluminación LED y photocall para eventos en Tenerife y Canarias.">
    <meta name="twitter:image" content="<?php echo esc_url( home_url( '/wp-content/uploads/2026/08/Eventos-Tenerife.webp' ) ); ?>">

    <!-- Robots -->
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">

    <?php
}
add_action( 'wp_head', 'audiomania_child_seo_head', 1 );

add_filter( 'pre_get_document_title', function( $title ) {
    if ( is_front_page() || is_home() ) {
        return 'Audiomania Eventos – DJ, Sonido e Iluminación Profesional en Tenerife';
    }
    if ( is_page( 'servicios' ) || is_page( 12 ) ) {
        return 'Servicios – DJ, Sonido e Iluminación para Eventos en Tenerife | Audiomania';
    }
    if ( is_page( 'reservar' ) || is_page( 13 ) ) {
        return 'Reservar Equipo – Presupuesto DJ e Iluminación en Tenerife | Audiomania';
    }
    if ( is_page( 'galeria' ) || is_page( 14 ) ) {
        return 'Galería – Eventos de DJ e Iluminación en Tenerife | Audiomania Eventos';
    }
    if ( is_page( 'sobre-nosotros' ) || is_page( 16 ) ) {
        return 'Sobre Nosotros – Audiomania Eventos, Tenerife | +15 años de experiencia';
    }
    if ( is_page( 'contacto' ) || is_page( 15 ) ) {
        return 'Contacto – Audiomania Eventos Tenerife | +34 669 621 139';
    }
    return $title;
}, 9999 );

/**
 * ------------------------------------------------------------------
 * 5. SEO — SCHEMA.ORG JSON-LD (invisible al usuario)
 * ------------------------------------------------------------------
 */
function audiomania_child_schema_jsonld() {
    // LocalBusiness: Audiomania Eventos
    ?>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "LocalBusiness",
        "name": "Audiomania Eventos",
        "description": "Servicios profesionales de DJ, sonido, iluminación LED, photocall y animación para eventos en Tenerife y toda Canarias. Bodas, fiestas, eventos corporativos.",
        "url": "<?php echo esc_url_raw( home_url( '/' ) ); ?>",
        "telephone": "+34-669-621-139",
        "email": "carlostoledodj@gmail.com",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Tenerife",
            "addressRegion": "Canarias",
            "addressCountry": "ES"
        },
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": "28.4636",
            "longitude": "-16.2518"
        },
        "serviceType": ["DJ para Eventos", "Alquiler de Sonido", "Iluminación LED", "Photocall", "Animación de Eventos", "Sonido para Bodas"],
        "areaServed": {
            "@type": "Place",
            "name": "Tenerife, Canarias, España"
        },
        "priceRange": "€€",
        "openingHoursSpecification": [
            {
                "@type": "OpeningHoursSpecification",
                "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],
                "opens": "00:00",
                "closes": "23:59"
            }
        ],
        "sameAs": []
    }
    </script>
    <?php

    // Organization: MicroTech AI (desarrollador)
    ?>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "MicroTech AI",
        "url": "https://microtechai.es",
        "description": "Desarrollo web y soluciones tecnológicas con inteligencia artificial.",
        "founder": {
            "@type": "Organization",
            "name": "MicroTech AI"
        }
    }
    </script>
    <?php

    // Breadcrumbs (solo en páginas internas)
    if ( ! is_front_page() && ! is_home() && ! is_woocommerce() ) {
        ?>
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [
                {
                    "@type": "ListItem",
                    "position": 1,
                    "name": "Inicio",
                    "item": "<?php echo esc_url_raw( home_url( '/' ) ); ?>"
                },
                {
                    "@type": "ListItem",
                    "position": 2,
                    "name": "<?php echo esc_html( get_the_title() ); ?>",
                    "item": "<?php echo esc_url_raw( get_permalink() ); ?>"
                }
            ]
        }
        </script>
        <?php
    }

    // Product schema para WooCommerce
    if ( function_exists( 'is_product' ) && is_product() ) {
        global $product;
        if ( $product ) {
            ?>
            <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "Product",
                "name": "<?php echo esc_js( $product->get_name() ); ?>",
                "description": "<?php echo esc_js( wp_strip_all_tags( $product->get_short_description() ?: $product->get_description() ) ); ?>",
                "brand": {
                    "@type": "Brand",
                    "name": "Audiomania Eventos"
                },
                "offers": {
                    "@type": "Offer",
                    "url": "<?php echo esc_url_raw( $product->get_permalink() ); ?>",
                    "priceCurrency": "<?php echo esc_attr( get_woocommerce_currency() ); ?>",
                    "price": "<?php echo esc_attr( $product->get_price() ); ?>",
                    "availability": "<?php echo $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock'; ?>",
                    "seller": {
                        "@type": "Organization",
                        "name": "Audiomania Eventos"
                    }
                }
            }
            </script>
            <?php
        }
    }

    // Product listing schema
    if ( is_shop() || is_product_category() || is_product_tag() ) {
        ?>
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "CollectionPage",
            "name": "<?php echo esc_js( get_the_title() ?: 'Tienda' ); ?>",
            "description": "Catálogo de productos de Audiomania Eventos – equipamiento de sonido, iluminación y DJ para eventos profesionales en Tenerife.",
            "url": "<?php echo esc_url_raw( is_shop() ? get_permalink() : get_term_link( get_queried_object() ) ); ?>",
            "publisher": {
                "@type": "Organization",
                "name": "Audiomania Eventos",
                "url": "<?php echo esc_url_raw( home_url( '/' ) ); ?>"
            }
        }
        </script>
        <?php
    }
}
add_action( 'wp_head', 'audiomania_child_schema_jsonld', 1 );


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
        $hero_html .= '    <div class="am-hero-badge"><svg style="width:20px;height:20px;display:inline-flex;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55C7.79 13 6 14.79 6 17s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/></svg> DJ, sonido e iluminación para eventos en Tenerife</div>' . "\n";
        $hero_html .= '    <h1>Tu evento empieza con el <span>ambiente adecuado</span></h1>' . "\n";
        $hero_html .= '    <p class="hero-subtitle">Ponemos música, sonido e iluminación a bodas, fiestas privadas y eventos corporativos en Tenerife. Diseñamos cada montaje según el espacio, el número de invitados y el tipo de celebración. Más de 15 años y muchos más de 500 eventos.</p>' . "\n";
        $hero_html .= '    <div class="am-hero-cta-group">' . "\n";
        $hero_html .= '      <a href="/audiomaniaeventos/reservar/" class="am-hero-cta am-hero-cta-primary">Solicitar Presupuesto <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg></a>' . "\n";
        $hero_html .= '      <a href="/audiomaniaeventos/servicios/" class="am-hero-cta am-hero-cta-secondary">Ver Servicios</a>' . "\n";
        $hero_html .= '    </div>' . "\n";
        $hero_html .= '    <div class="am-hero-features">' . "\n";
        $hero_html .= '      <div class="am-feature-item"><svg style="width:16px;height:16px;display:inline-flex;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/></svg> Sonido Profesional</div>' . "\n";
        $hero_html .= '      <div class="am-feature-item"><svg style="width:16px;height:16px;display:inline-flex;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M9 21c0 .55.45 1 1 1h4c.55 0 1-.45 1-1v-1H9v1zm3-19C8.14 2 5 5.14 5 9c0 2.38 1.19 4.47 3 5.74V17c0 .55.45 1 1 1h6c.55 0 1-.45 1-1v-2.26c1.81-1.27 3-3.36 3-5.74 0-3.86-3.14-7-7-7z"/></svg> Iluminación LED</div>' . "\n";
        $hero_html .= '      <div class="am-feature-item"><svg style="width:16px;height:16px;display:inline-flex;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 1c-4.97 0-9 4.03-9 9v7c0 1.66 1.34 3 3 3h3v-8H5v-2c0-3.87 3.13-7 7-7s7 3.13 7 7v2h-4v8h3c1.66 0 3-1.34 3-3v-7c0-4.97-4.03-9-9-9z"/></svg> DJ Expertos</div>' . "\n";
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
        $hero_html .= '    <div class="am-hero-badge"><svg style="width:20px;height:20px;display:inline-flex;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M16.63 2.16c-.6-.41-1.39-.51-2.08-.27l-.76.24C13.05 1.83 12.54 1.5 12 1.5s-1.05.33-1.79.63l-.76-.24c-.69-.24-1.48-.14-2.08.27C6.61 2.64 6 3.58 6 5v8.21l-2.76.92A1.001 1.001 0 0 0 3 15.15v.85c0 .55.45 1 1 1h.38l.62 4.34c.07.47.48.81.96.81h.02c.48 0 .89-.34.96-.81L7.5 17h9l.54 4.34c.07.47.48.81.96.81h.02c.48 0 .89-.34.96-.81l.62-4.34H20c.55 0 1-.45 1-1v-.85c0-.42-.27-.79-.66-.92L18 13.21V5c0-1.42-.61-2.36-1.37-2.84zM12 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/></svg> Llevamos tu evento por toda Canarias</div>' . "\n";
        $hero_html .= '    <h2>Eventos en <span>todos los municipios de Tenerife</span></h2>' . "\n";
        $hero_html .= '    <p class="hero-subtitle">Trabajamos en todos los municipios de Tenerife con sonido profesional, iluminación, pantallas LED, photocall, mobiliario y efectos. También estudiamos montajes en el resto de Canarias según la fecha y las necesidades técnicas.</p>' . "\n";
        $hero_html .= '    <div class="am-hero-cta-group">' . "\n";
        $hero_html .= '      <a href="/audiomaniaeventos/servicios/" class="am-hero-cta am-hero-cta-primary">Ver Servicios <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg></a>' . "\n";
        $hero_html .= '      <a href="/audiomaniaeventos/galeria/" class="am-hero-cta am-hero-cta-secondary">Ver Galería</a>' . "\n";
        $hero_html .= '    </div>' . "\n";
        $hero_html .= '    <div class="am-hero-features">' . "\n";
        $hero_html .= '      <div class="am-feature-item"><svg style="width:16px;height:16px;display:inline-flex;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg> Todos los municipios de Tenerife</div>' . "\n";
        $hero_html .= '      <div class="am-feature-item"><svg style="width:16px;height:16px;display:inline-flex;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg> Montaje incluido</div>' . "\n";
        $hero_html .= '      <div class="am-feature-item"><svg style="width:16px;height:16px;display:inline-flex;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M11 21h-1l1-7H7.5c-.58 0-.57-.32-.38-.66.19-.34.05-.08.07-.12C8.48 10.94 10.42 7.54 13 3h1l-1 7h3.5c.49 0 .56.33.47.51l-.07.15C12.96 13.66 11 16.5 11 21z"/></svg> Respuesta en menos de 24h</div>' . "\n";
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
        $hero_html .= '    <div class="am-hero-badge"><svg style="width:20px;height:20px;display:inline-flex;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 1c-4.97 0-9 4.03-9 9v7c0 1.66 1.34 3 3 3h3v-8H5v-2c0-3.87 3.13-7 7-7s7 3.13 7 7v2h-4v8h3c1.66 0 3-1.34 3-3v-7c0-4.97-4.03-9-9-9z"/></svg> DJ Profesional para tu Evento</div>' . "\n";
        $hero_html .= '    <h2><span>DJ para bodas y fiestas</span> en Tenerife</h2>' . "\n";
        $hero_html .= '    <p class="hero-subtitle">Música adaptada a bodas, cumpleaños, fiestas privadas y eventos corporativos. Trabajamos con equipos profesionales y contamos con más de 15 años de experiencia y muchos más de 500 eventos.</p>' . "\n";
        $hero_html .= '    <div class="am-hero-cta-group">' . "\n";
        $hero_html .= '      <a href="https://wa.me/34669621139?text=Hola%2C%20quiero%20un%20DJ%20para%20mi%20evento%20en%20Tenerife" class="am-hero-cta am-hero-cta-whatsapp" target="_blank" rel="noopener noreferrer">WhatsApp Directo <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg></a>' . "\n";
        $hero_html .= '      <a href="/audiomaniaeventos/contacto/" class="am-hero-cta am-hero-cta-secondary">Contactar</a>' . "\n";
        $hero_html .= '    </div>' . "\n";
        $hero_html .= '    <div class="am-hero-features">' . "\n";
        $hero_html .= '      <div class="am-feature-item"><svg style="width:16px;height:16px;display:inline-flex;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55C7.79 13 6 14.79 6 17s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/></svg> Música a medida</div>' . "\n";
        $hero_html .= '      <div class="am-feature-item"><svg style="width:16px;height:16px;display:inline-flex;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M13.5.67s.74 2.65.74 4.8c0 2.06-1.35 3.73-3.41 3.73-2.07 0-3.63-1.67-3.63-3.73l.03-.36C5.21 7.51 4 10.62 4 14c0 4.42 3.58 8 8 8s8-3.58 8-8C20 8.61 17.41 3.8 13.5.67zM11.71 19c-1.78 0-3.22-1.4-3.22-3.14 0-1.62 1.05-2.76 2.81-3.12 1.77-.36 3.6-1.21 4.62-2.58.39 1.29.59 2.65 0.59 4.04 0 2.65-2.15 4.8-4.8 4.8z"/></svg> Más de 500 eventos</div>' . "\n";
        $hero_html .= '      <div class="am-feature-item"><svg style="width:16px;height:16px;display:inline-flex;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg> Equipamiento premium</div>' . "\n";
        $hero_html .= '    </div>' . "\n";
        $hero_html .= '  </div>' . "\n";
        $hero_html .= '</section>' . "\n";
    }

    // SERVICIOS
    elseif ( is_page( 'servicios' ) || is_page( 12 ) ) {
        $hero_html = '<section class="am-hero am-hero-single" style="background-image:url(\'' . $base . 'Eventos-Tenerife.webp\');">' . "\n";
        $hero_html .= '<div class="am-hero-content">' . "\n";
        $hero_html .= '  <div class="am-hero-badge">Servicios Profesionales</div>' . "\n";
        $hero_html .= '  <h1>Servicios de <span>DJ, sonido e iluminación</span> en Tenerife</h1>' . "\n";
        $hero_html .= '  <p class="hero-subtitle">Soluciones para bodas, fiestas privadas y eventos corporativos: DJ, sonido, iluminación, pantallas LED, photocall, mobiliario y efectos.</p>' . "\n";
        $hero_html .= '  <div class="am-hero-cta-group">' . "\n";
        $hero_html .= '    <a href="/audiomaniaeventos/reservar/" class="am-hero-cta am-hero-cta-primary">Solicitar Presupuesto</a>' . "\n";
        $hero_html .= '    <a href="https://wa.me/34669621139" class="am-hero-cta am-hero-cta-whatsapp" target="_blank" rel="noopener">WhatsApp Directo</a>' . "\n";
        $hero_html .= '  </div>' . "\n";
        $hero_html .= '  <div class="am-hero-features">' . "\n";
        $hero_html .= '    <div class="am-feature-item"><svg style="width:16px;height:16px;display:inline-flex;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/></svg> 15+ Años Experiencia</div>' . "\n";
        $hero_html .= '    <div class="am-feature-item"><svg style="width:16px;height:16px;display:inline-flex;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M11 21h-1l1-7H7.5c-.58 0-.57-.32-.38-.66.19-.34.05-.08.07-.12C8.48 10.94 10.42 7.54 13 3h1l-1 7h3.5c.49 0 .56.33.47.51l-.07.15C12.96 13.66 11 16.5 11 21z"/></svg> Presupuesto en 24h</div>' . "\n";
        $hero_html .= '    <div class="am-feature-item"><svg style="width:16px;height:16px;display:inline-flex;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg> Toda Canarias</div>' . "\n";
        $hero_html .= '  </div>' . "\n";
        $hero_html .= '</div>' . "\n";
        $hero_html .= '</section>' . "\n";

        // === SECCIÓN: GRID DE SERVICIOS CON PRECIOS ===
        $services_content = '<section class="am-services-section">' . "\n";
        $services_content .= '<div class="am-container">' . "\n";

        // SEO Intro
        $services_content .= '<div class="am-services-intro">' . "\n";
        $services_content .= '  <h2>Servicios de Sonido, Iluminación y Animación en Tenerife</h2>' . "\n";
        $services_content .= '  <p class="am-services-intro-text">Audiomania Eventos ofrece servicios profesionales para bodas, fiestas privadas y eventos corporativos en Tenerife. Puedes contratar un servicio concreto o pedir un montaje completo adaptado al espacio, al número de invitados y al tipo de celebración. Respondemos en menos de 24 horas.</p>' . "\n";
        $services_content .= '</div>' . "\n";

        // Services Grid
        $services_content .= '<div class="am-services-grid">' . "\n";

        // Service 1: DJ para Eventos
        $services_content .= '  <div class="am-service-card" id="dj">' . "\n";
        $services_content .= '    <div class="am-service-card-inner">' . "\n";
        $services_content .= '      <div class="am-service-icon-wrap"><svg style="width:48px;height:48px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 1c-4.97 0-9 4.03-9 9v7c0 1.66 1.34 3 3 3h3v-8H5v-2c0-3.87 3.13-7 7-7s7 3.13 7 7v2h-4v8h3c1.66 0 3-1.34 3-3v-7c0-4.97-4.03-9-9-9z"/></svg></div>' . "\n";
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
        $services_content .= '      <div class="am-service-icon-wrap"><svg style="width:48px;height:48px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/></svg></div>' . "\n";
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
        $services_content .= '      <div class="am-service-icon-wrap"><svg style="width:48px;height:48px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M9 21c0 .55.45 1 1 1h4c.55 0 1-.45 1-1v-1H9v1zm3-19C8.14 2 5 5.14 5 9c0 2.38 1.19 4.47 3 5.74V17c0 .55.45 1 1 1h6c.55 0 1-.45 1-1v-2.26c1.81-1.27 3-3.36 3-5.74 0-3.86-3.14-7-7-7z"/></svg></div>' . "\n";
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
        $services_content .= '      <div class="am-service-icon-wrap"><svg style="width:48px;height:48px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21 3H3c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h5v2h8v-2h5c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 14H3V5h18v12z"/></svg></div>' . "\n";
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
        $services_content .= '      <div class="am-service-icon-wrap"><svg style="width:48px;height:48px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M9.4 4C7.5 4 5.95 5.07 5.12 6.6L4 6.6V18H20V6H18.88C18.05 5.07 16.5 4 14.6 4H9.4zM12 6C13.1 6 14 6.9 14 8S13.1 10 12 10 10 9.1 10 8s.9-2 2-2zM5 20H3V8h.17C2.07 9.59 1.5 11.24 1.5 13c0 3.31 2.69 6 6 6 .13 0 .26 0 .39-.01.37.09.75.01 1.11.01h7.19c.36 0 .74.08 1.11-.01.13.01.26.01.39.01 3.31 0 6-2.69 6-6 0-1.76-.57-3.41-1.67-4.89.07-.01.14-.01.17-.01H3v12z"/></svg></div>' . "\n";
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
        $services_content .= '      <div class="am-service-badge"><svg style="width:16px;height:16px;display:inline-block;vertical-align:middle;margin-right:4px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg> Más Popular</div>' . "\n";
        $services_content .= '      <div class="am-service-icon-wrap"><svg style="width:48px;height:48px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M18 7c0-1.1-.9-2-2-2h-1c-1.1 0-2 .9-2 2h-2c0-1.1-.9-2-2-2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7zm-1 2H7V5h10v4z"/></svg></div>' . "\n";
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
            array('icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>', 'title' => 'Escenarios y Tarimas', 'price' => '350€', 'desc' => 'Tarimas de abedul, estructuras metálicas, escenarios modulares. Montaje profesional incluido.'),
            array('icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3zm5.91-3c-.49 0-.9.36-.98.85C16.52 14.2 14.47 16 12 16s-4.52-1.8-4.93-4.15c-.08-.49-.49-.85-.98-.85-.61 0-1.09.54-1 1.14.49 3 2.89 5.39 5.91 5.91v3.2h-3v2h10v-3c3.02-.52 5.42-2.91 5.91-5.91.1-.6-.39-1.14-1-1.14z"/></svg>', 'title' => 'Karaoke', 'price' => '120€', 'desc' => 'Sistema completo con pantalla, micrófonos, base de canciones actualizada.'),
            array('icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9c.83 0 1.5-.67 1.5-1.5 0-.39-.15-.74-.39-1.01-.23-.26-.38-.61-.38-.99 0-.83.67-1.5 1.5-1.5H16c2.76 0 5-2.24 5-5 0-4.42-4.03-8-9-8zm-5.5 9c-.83 0-1.5-.67-1.5-1.5S5.67 9 6.5 9 8 9.67 8 10.5 7.33 12 6.5 12zm3-4C8.67 8 8 7.33 8 6.5S8.67 5 9.5 5s1.5.67 1.5 1.5S10.33 8 9.5 8zm5 0c-.83 0-1.5-.67-1.5-1.5S13.67 5 14.5 5s1.5.67 1.5 1.5S15.33 8 14.5 8zm3 4c-.83 0-1.5-.67-1.5-1.5S16.67 9 17.5 9s1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/></svg>', 'title' => 'Machine Humo / Nieve', 'price' => '80€', 'desc' => 'Máquina de humo, nieve sintética, confeti. Efectos especiales para momentos clave.'),
            array('icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M8 11h3v10h2V11h3l-4-4-4 4zM4 22h16v-2H4v2zm18-8H6l6-6 6 6z"/></svg>', 'title' => 'Mobiliario Premium', 'price' => '2€/pza', 'desc' => 'Sillas Tiffany, mesas redondas, mantelería, banquetes. Todo personalizable.'),
        );

        foreach ( $extras as $ex ) {
            $services_content .= '    <div class="am-extra-card">' . "\n";
            $services_content .= '      <div class="am-extra-icon">' . wp_kses( $ex['icon'], array( 'svg' => array( 'xmlns' => true, 'viewBox' => true ), 'path' => array( 'd' => true ) ) ) . '</div>' . "\n";
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
        // SEO: JSON-LD
        $seo_json = '<!-- SEO JSON-LD: Service (Reserva) -->' . "\n" .
        '<script type="application/ld+json">' . "\n" .
        '{' . "\n" .
        '  "@context": "https://schema.org",' . "\n" .
        '  "@type": "Service",' . "\n" .
        '  "serviceType": "Alquiler de Equipos para Eventos",' . "\n" .
        '  "provider": {' . "\n" .
        '    "@type": "LocalBusiness",' . "\n" .
        '    "name": "Audiomania Eventos",' . "\n" .
        '    "telephone": "+346****1139",' . "\n" .
        '    "email": "carlostoledodj@gmail.com",' . "\n" .
        '    "address": {' . "\n" .
        '      "@type": "PostalAddress",' . "\n" .
        '      "addressLocality": "Tenerife",' . "\n" .
        '      "addressRegion": "Canarias",' . "\n" .
        '      "addressCountry": "ES"' . "\n" .
        '    }' . "\n" .
        '  },' . "\n" .
        '  "areaServed": "Tenerife, Canarias",' . "\n" .
        '  "description": "Reserva tu equipo profesional para eventos: DJ, sonido, iluminación LED, photocall y coordinación integral en Tenerife y toda Canarias. Presupuesto sin compromiso."' . "\n" .
        '}' . "\n" .
        '</script>' . "\n";

        $hero_html = '<section class="am-hero am-hero-single" style="background-image:url(\'' . $base . 'Organizacion-Eventos-Tenerife.webp\');">' . "\n";
        $hero_html .= '<div class="am-hero-content">' . "\n";
        $hero_html .= '  <div class="am-hero-badge"><svg style="width:20px;height:20px;display:inline-flex;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/></svg> Reserva tu Fecha</div>' . "\n";
        $hero_html .= '  <h1>Pide presupuesto para tu <span>evento en Tenerife</span></h1>' . "\n";
        $hero_html .= '  <p class="hero-subtitle">Dinos la fecha, el lugar, el tipo de evento y el número aproximado de invitados. Te responderemos con una propuesta clara de DJ, sonido, iluminación y servicios adicionales en menos de 24 horas.</p>' . "\n";
        $hero_html .= '  <div class="am-hero-cta-group">' . "\n";
        $hero_html .= '    <a href="/audiomaniaeventos/contacto/" class="am-hero-cta am-hero-cta-primary">Solicitar Presupuesto Gratis</a>' . "\n";
        $hero_html .= '    <a href="https://wa.me/34669621139?text=Hola%2C%20quiero%20reservar%20equipo%20para%20mi%20evento" class="am-hero-cta am-hero-cta-whatsapp" target="_blank" rel="noopener">WhatsApp Directo</a>' . "\n";
        $hero_html .= '  </div>' . "\n";
        $hero_html .= '  <div class="am-hero-features">' . "\n";
        $hero_html .= '    <div class="am-feature-item"><svg style="width:16px;height:16px;display:inline-flex;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm7 18H5V8h14v11zM7 10h5v5H7z"/></svg> Fechas Limitadas</div>' . "\n";
        $hero_html .= '    <div class="am-feature-item"><svg style="width:16px;height:16px;display:inline-flex;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.3-2.3c.5-.4.5-1.1.1-1.4z"/></svg> Montaje Incluido</div>' . "\n";
        $hero_html .= '    <div class="am-feature-item"><svg style="width:16px;height:16px;display:inline-flex;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M11 21h-1l1-7H7.5c-.58 0-.57-.32-.38-.66.19-.34.05-.08.07-.12C8.48 10.94 10.42 7.54 13 3h1l-1 7h3.5c.49 0 .56.33.47.51l-.07.15C12.96 13.66 11 16.5 11 21z"/></svg> Reserva en 5 Min</div>' . "\n";
        $hero_html .= '  </div>' . "\n";
        $hero_html .= $seo_json;
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
        $gallery_html .= '  <div class="am-hero-badge"><svg style="width:20px;height:20px;display:inline-flex;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M9.4 4C7.5 4 5.95 5.07 5.12 6.6L4 6.6V18H20V6H18.88C18.05 5.07 16.5 4 14.6 4H9.4zM12 6C13.1 6 14 6.9 14 8S13.1 10 12 10 10 9.1 10 8s.9-2 2-2zM5 20H3V8h.17C2.07 9.59 1.5 11.24 1.5 13c0 3.31 2.69 6 6 6 .13 0 .26 0 .39-.01.37.09.75.01 1.11.01h7.19c.36 0 .74.08 1.11-.01.13.01.26.01.39.01 3.31 0 6-2.69 6-6 0-1.76-.57-3.41-1.67-4.89.07-.01.14-.01.17-.01H3v12z"/></svg> Nuestro Portfolio</div>' . "\n";
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
            $gallery_html .= '            <span class="am-gallery-zoom-icon"><svg style="width:20px;height:20px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19 19H5V5h7V3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-7h-2v7zM14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7z"/></svg></span>' . "\n";
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
        $gallery_html .= '        <span class="am-feature-icon"><svg style="width:32px;height:32px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55C7.79 13 6 14.79 6 17s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/></svg></span>' . "\n";
        $gallery_html .= '        <h4>Sonido Profesional</h4>' . "\n";
        $gallery_html .= '        <p>Altavoces line array, mezcladoras digitales y controladores de última generación. Sonido cristalino para bodas, conciertos y eventos corporativos en toda Tenerife.</p>' . "\n";
        $gallery_html .= '      </div>' . "\n";
        $gallery_html .= '      <div class="am-feature-card">' . "\n";
        $gallery_html .= '        <span class="am-feature-icon"><svg style="width:32px;height:32px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M9 21c0 .55.45 1 1 1h4c.55 0 1-.45 1-1v-1H9v1zm3-19C8.14 2 5 5.14 5 9c0 2.38 1.19 4.47 3 5.74V17c0 .55.45 1 1 1h6c.55 0 1-.45 1-1v-2.26c1.81-1.27 3-3.36 3-5.74 0-3.86-3.14-7-7-7z"/></svg></span>' . "\n";
        $gallery_html .= '        <h4>Iluminación LED</h4>' . "\n";
        $gallery_html .= '        <p>Pantallas LED, iluminación inteligente, luces robóticas, efectos de humo y láser. Creamos ambientes únicos para cada tipo de evento en Canarias.</p>' . "\n";
        $gallery_html .= '      </div>' . "\n";
        $gallery_html .= '      <div class="am-feature-card">' . "\n";
        $gallery_html .= '        <span class="am-feature-icon"><svg style="width:32px;height:32px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 1c-4.97 0-9 4.03-9 9v7c0 1.66 1.34 3 3 3h3v-8H5v-2c0-3.87 3.13-7 7-7s7 3.13 7 7v2h-4v8h3c1.66 0 3-1.34 3-3v-7c0-4.97-4.03-9-9-9z"/></svg></span>' . "\n";
        $gallery_html .= '        <h4>DJ Expertos</h4>' . "\n";
        $gallery_html .= '        <p>DJs profesionales con experiencia en bodas, fiestas privadas y eventos corporativos. Música a medida, animación y control total del evento.</p>' . "\n";
        $gallery_html .= '      </div>' . "\n";
        $gallery_html .= '      <div class="am-feature-card">' . "\n";
        $gallery_html .= '        <span class="am-feature-icon"><svg style="width:32px;height:32px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19 2h-4.18C14.4.84 13.3 0 12 0c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm7 18H5V8h10v12z"/></svg></span>' . "\n";
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
        $hero_html .= '  <div class="am-hero-badge"><svg style="width:20px;height:20px;display:inline-flex;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg> Contacto Directo</div>' . "\n";
        $hero_html .= '  <h1>Contacta con <span>Audiomania Eventos</span></h1>' . "\n";
        $hero_html .= '  <p class="hero-subtitle">Cuéntanos qué quieres organizar en Tenerife. Te responderemos con disponibilidad, opciones de montaje y un presupuesto personalizado en menos de 24 horas.</p>' . "\n";
        $hero_html .= '  <div class="am-hero-cta-group">' . "\n";
        $hero_html .= '    <a href="https://wa.me/34669621139?text=Hola%2C%20me%20interesa%20un%20presupuesto%20para%20mi%20evento" class="am-hero-cta am-hero-cta-whatsapp" target="_blank" rel="noopener">WhatsApp Directo</a>' . "\n";
        $hero_html .= '    <a href="tel:+34669621139" class="am-hero-cta am-hero-cta-primary">Llamar Ahora</a>' . "\n";
        $hero_html .= '  </div>' . "\n";
        $hero_html .= '  <div class="am-hero-features">' . "\n";
        $hero_html .= '    <div class="am-feature-item"><svg style="width:16px;height:16px;display:inline-flex;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M11 21h-1l1-7H7.5c-.58 0-.57-.32-.38-.66.19-.34.05-.08.07-.12C8.48 10.94 10.42 7.54 13 3h1l-1 7h3.5c.49 0 .56.33.47.51l-.07.15C12.96 13.66 11 16.5 11 21z"/></svg> Respuesta en 24h</div>' . "\n";
        $hero_html .= '    <div class="am-feature-item"><svg style="width:16px;height:16px;display:inline-flex;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19 2h-4.18C14.4.84 13.3 0 12 0c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm7 18H5V8h10v12z"/></svg> Presupuesto Gratis</div>' . "\n";
        $hero_html .= '    <div class="am-feature-item"><svg style="width:16px;height:16px;display:inline-flex;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg> Toda Canarias</div>' . "\n";
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
        $contact_html .= '    <p class="am-contact-form-desc">Cuéntanos la fecha, el lugar y el tipo de evento. Te responderemos en menos de 24 horas con disponibilidad y un presupuesto personalizado.</p>' . "\n";
        $contact_html .= '    <form class="am-contact-form" action="' . esc_url( home_url( '/audiomaniaeventos/contacto/' ) ) . '" method="POST" novalidate>' . "\n";
        $contact_html .= '      <div class="am-form-row">' . "\n";
        $contact_html .= '        <div class="am-form-group">' . "\n";
        $contact_html .= '          <label for="am-name">Nombre completo <span class="am-required">*</span></label>' . "\n";
        $contact_html .= '          <input type="text" id="am-name" name="am_name" required placeholder="Tu nombre completo">' . "\n";
        $contact_html .= '        </div>' . "\n";
        $contact_html .= '        <div class="am-form-group">' . "\n";
        $contact_html .= '          <label for="am-phone">Teléfono <span class="am-required">*</span></label>' . "\n";
        $contact_html .= '          <input type="tel" id="am-phone" name="am_phone" required placeholder="+34 6XX XXX XXX">' . "\n";
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
        $contact_html .= '          <option value="boda">Boda</option>' . "\n";
        $contact_html .= '          <option value="corporativo">Evento Corporativo</option>' . "\n";
        $contact_html .= '          <option value="fiesta">Fiesta Privada</option>' . "\n";
        $contact_html .= '          <option value="dj">DJ para Evento</option>' . "\n";
        $contact_html .= '          <option value="sonido">Alquiler de Sonido</option>' . "\n";
        $contact_html .= '          <option value="iluminacion">Iluminación LED</option>' . "\n";
        $contact_html .= '          <option value="photocall">Photocall</option>' . "\n";
        $contact_html .= '          <option value="completo">Paquete Completo</option>' . "\n";
        $contact_html .= '          <option value="otro">Otro</option>' . "\n";
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
        $contact_html .= '        <div class="am-contact-card-icon"><svg style="width:28px;height:28px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg></div>' . "\n";
        $contact_html .= '        <div class="am-contact-card-content">' . "\n";
        $contact_html .= '          <h3>Teléfono</h3>' . "\n";
        $contact_html .= '          <p><a href="tel:+34669621139">+34 669 621 139</a></p>' . "\n";
        $contact_html .= '          <a href="tel:+34669621139" class="am-contact-card-link">Llamar Ahora</a>' . "\n";
        $contact_html .= '        </div>' . "\n";
        $contact_html .= '      </div>' . "\n";

        // Email Card
        $contact_html .= '      <div class="am-contact-card am-contact-card-email">' . "\n";
        $contact_html .= '        <div class="am-contact-card-icon"><svg style="width:28px;height:28px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg></div>' . "\n";
        $contact_html .= '        <div class="am-contact-card-content">' . "\n";
        $contact_html .= '          <h3>Email</h3>' . "\n";
        $contact_html .= '          <p><a href="mailto:carlostoledodj@gmail.com">carlostoledodj@gmail.com</a></p>' . "\n";
        $contact_html .= '          <a href="mailto:carlostoledodj@gmail.com" class="am-contact-card-link">Enviar Email</a>' . "\n";
        $contact_html .= '        </div>' . "\n";
        $contact_html .= '      </div>' . "\n";

        // WhatsApp Card
        $contact_html .= '      <div class="am-contact-card am-contact-card-whatsapp">' . "\n";
        $contact_html .= '        <div class="am-contact-card-icon"><svg style="width:28px;height:28px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg></div>' . "\n";
        $contact_html .= '        <div class="am-contact-card-content">' . "\n";
        $contact_html .= '          <h3>WhatsApp</h3>' . "\n";
        $contact_html .= '          <p><a href="https://wa.me/34669621139" target="_blank" rel="noopener">+34 669 621 139</a></p>' . "\n";
        $contact_html .= '          <a href="https://wa.me/34669621139?text=Hola%2C%20me%20interesa%20un%20presupuesto" class="am-contact-card-link" target="_blank" rel="noopener">Escribir por WhatsApp</a>' . "\n";
        $contact_html .= '        </div>' . "\n";
        $contact_html .= '      </div>' . "\n";

        // Ubicación Card
        $contact_html .= '      <div class="am-contact-card am-contact-card-location">' . "\n";
        $contact_html .= '        <div class="am-contact-card-icon"><svg style="width:28px;height:28px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg></div>' . "\n";
        $contact_html .= '        <div class="am-contact-card-content">' . "\n";
        $contact_html .= '          <h3>Ubicación</h3>' . "\n";
        $contact_html .= '          <p>Tenerife, Canarias, España</p>' . "\n";
        $contact_html .= '          <span class="am-contact-card-link">Cubrimos toda la isla</span>' . "\n";
        $contact_html .= '        </div>' . "\n";
        $contact_html .= '      </div>' . "\n";

        // Horario Card
        $contact_html .= '      <div class="am-contact-card am-contact-card-hours">' . "\n";
        $contact_html .= '        <div class="am-contact-card-icon"><svg style="width:28px;height:28px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg></div>' . "\n";
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
        $about_html .= '  <h1>Más de 15 años creando eventos en <span>Tenerife</span></h1>' . "\n";
        $about_html .= '</div>' . "\n";
        $about_html .= '</section>' . "\n";

        // Historia
        $about_html .= '<section class="am-about-section">' . "\n";
        $about_html .= '<div class="am-container">' . "\n";

        $about_html .= '  <div class="am-about-intro">' . "\n";
        $about_html .= '    <h2>Nuestra Historia</h2>' . "\n";
        $about_html .= '    <p>Audiomania Eventos trabaja desde hace más de 15 años con bodas, fiestas privadas, eventos corporativos y celebraciones en todos los municipios de Tenerife.</p>' . "\n";
        $about_html .= '    <p>Hemos participado en muchos más de 500 eventos y ofrecemos DJ, sonido, iluminación, pantallas LED, photocall, mobiliario y efectos. Nuestro equipo técnico se encarga del diseño, montaje, operación y desmontaje.</p>' . "\n";
        $about_html .= '    <p>Te ayudamos a elegir la combinación adecuada para el espacio, el número de invitados y el tipo de celebración.</p>' . "\n";
        $about_html .= '  </div>' . "\n";

        // Cards — same style as contact page
        $about_html .= '  <div class="am-about-cards">' . "\n";

        // Card 1 — Equipos (blue)
        $about_html .= '    <div class="am-about-card am-about-card-equipos">' . "\n";
        $about_html .= '      <div class="am-about-card-icon"><svg style="width:28px;height:28px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55C7.79 13 6 14.79 6 17s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/></svg></div>' . "\n";
        $about_html .= '      <div class="am-about-card-content">' . "\n";
        $about_html .= '        <h3>Equipos de gama alta</h3>' . "\n";
        $about_html .= '        <p>Solo trabajamos con marcas profesionales: L-Acoustics, Pioneer, Linsn, Grand MA2.</p>' . "\n";
        $about_html .= '      </div>' . "\n";
        $about_html .= '    </div>' . "\n";

        // Card 2 — Operarios (green)
        $about_html .= '    <div class="am-about-card am-about-card-operarios">' . "\n";
        $about_html .= '      <div class="am-about-card-icon"><svg style="width:28px;height:28px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg></div>' . "\n";
        $about_html .= '      <div class="am-about-card-content">' . "\n";
        $about_html .= '        <h3>Operarios profesionales</h3>' . "\n";
        $about_html .= '        <p>Equipo técnico certificado con años de experiencia en directo.</p>' . "\n";
        $about_html .= '      </div>' . "\n";
        $about_html .= '    </div>' . "\n";

        // Card 3 — Servicio integral (purple)
        $about_html .= '    <div class="am-about-card am-about-card-servicio">' . "\n";
        $about_html .= '      <div class="am-about-card-icon"><svg style="width:28px;height:28px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.3-2.3c.5-.4.5-1.1.1-1.4z"/></svg></div>' . "\n";
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
        return $hero_html;
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
                    <div class="brand-top">
                        <span class="brand-main">Audiomania</span><span class="brand-dot">.</span><span class="brand-sub">eventos</span>
                    </div>
                    <div class="brand-subtitle">Eventos</div>
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
                <div class="footer-branding">
                    <span class="footer-brand-main">Audiomania</span><span class="footer-brand-dot">.</span><span class="footer-brand-sub">eventos</span>
                    <div class="footer-brand-tagline">Servicios profesionales de sonido, iluminación y animación para eventos en Tenerife y toda Canarias.</div>
                </div>
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
                    <li><svg style="width:18px;height:18px;display:inline-flex;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg> <a href="tel:+34669621139">+34 669 621 139</a></li>
                    <li><svg style="width:18px;height:18px;display:inline-flex;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg> <a href="mailto:carlostoledodj@gmail.com">carlostoledodj@gmail.com</a></li>
                    <li><svg style="width:18px;height:18px;display:inline-flex;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg> Tenerife, Canarias, España</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date( 'Y' ); ?> Audiomania Eventos.</p>
            <p class="footer-developed-by"><?php esc_html_e( 'desarrollado por ', 'audiomania-events-child' ); ?><a href="https://microtechai.es" rel="noopener noreferrer" target="_blank">microtechai.es</a> <?php esc_html_e( 'Todos los derechos reservados.', 'audiomania-events-child' ); ?></p>
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

/**
 * ------------------------------------------------------------------
 * Material Icons — SVG inline styles
 * ------------------------------------------------------------------
 */
add_action( 'wp_head', 'audiomania_svg_icons_css', 100 );
function audiomania_svg_icons_css() {
    if ( is_admin() ) return;
    ?>
    <style>
    /* Inline SVG contrast: solid icons inherit the surrounding color. */
    svg:not([fill="none"]),
    svg:not([fill="none"]) path {
        fill: currentColor;
    }
    svg[fill="none"] path[fill]:not([fill="none"]) {
        fill: currentColor;
    }
    .am-whatsapp-float svg,
    .am-whatsapp-float svg path {
        fill: #fff !important;
    }
    .am-hero-badge svg, .am-feature-item svg {
        display: inline-flex;
        vertical-align: middle;
        margin-right: 6px;
        color: rgba(255,255,255,0.9);
    }
    .am-hero-badge svg {
        color: rgba(255,255,255,0.85);
    }
    .am-service-icon-wrap svg {
        width: 48px !important;
        height: 48px !important;
        color: #4d7cff;
        text-shadow: 0 0 20px rgba(77,124,255,0.3);
    }
    .am-contact-card-icon svg {
        width: 28px !important;
        height: 28px !important;
    }
    .am-contact-card-phone .am-contact-card-icon svg { color: #4d7cff; }
    .am-contact-card-email .am-contact-card-icon svg { color: #a855f7; }
    .am-contact-card-whatsapp .am-contact-card-icon svg { color: #25D366; }
    .am-contact-card-location .am-contact-card-icon svg { color: #A75D42; }
    .am-contact-card-hours .am-contact-card-icon svg { color: #6F7653; }
    .am-about-card-icon svg {
        width: 28px !important;
        height: 28px !important;
    }
    .am-about-card-equipos .am-about-card-icon svg { color: #4d7cff; }
    .am-about-card-operarios .am-about-card-icon svg { color: #25D366; }
    .am-about-card-servicio .am-about-card-icon svg { color: #a855f7; }
    .am-feature-icon svg {
        width: 32px !important;
        height: 32px !important;
        color: #4d7cff;
        text-shadow: 0 0 15px rgba(77,124,255,0.2);
    }
    .am-extra-icon svg {
        width: 32px !important;
        height: 32px !important;
        color: #4d7cff;
    }
    .am-gallery-zoom-icon svg {
        width: 20px !important;
        height: 20px !important;
        color: #fff;
    }
    select option svg {
        width: 16px !important;
        height: 16px !important;
        margin-right: 4px;
    }
    .footer-links svg {
        width: 18px !important;
        height: 18px !important;
    }
    </style>
    <?php
}

/**
 * SEO/layout cleanup for the Spanish front page.
 */
add_filter( 'language_attributes', function( $output ) {
    return preg_replace( '/\blang=["\'][^"\']*["\']/', 'lang="es-ES"', $output, 1 );
}, 9999 );

add_filter( 'hello_elementor_page_title', function( $show ) {
    return ( is_front_page() || is_home() || is_page( array( 'servicios', 'reservar', 'galeria', 'sobre-nosotros', 'contacto', 12, 13, 14, 15, 16 ) ) ) ? false : $show;
}, 99 );
