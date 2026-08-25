<?php
/**
 * Audiomania Eventos Child Theme — functions.php
 *
 * Child theme de hello-elementor. Aplica el sistema de diseño completo:
 * modo oscuro, animación 3D disco, tipografías, paleta de colores, overrides
 * de WooCommerce, header/footer personalizados, botón WhatsApp flotante,
 * y CSS adicional inyectado dinámicamente.
 *
 * @package AudiomaniaEventsChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Prevent direct access
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
    // Parent theme style
    wp_enqueue_style(
        'hello-elementor',
        get_template_directory_uri() . '/style.css',
        array(),
        wp_get_theme()->get( 'Version' )
    );

    // Child theme style (dark mode design system)
    wp_enqueue_style(
        'audiomania-child',
        get_stylesheet_uri(),
        array( 'hello-elementor' ),
        wp_get_theme()->get( 'Version' )
    );

    // Google Fonts
    wp_enqueue_style(
        'audiomania-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Noto+Serif:wght@400;700&display=swap',
        array(),
        null
    );

    // Child theme JS (disco 3D + mobile menu)
    wp_enqueue_script(
        'audiomania-child-js',
        get_stylesheet_directory_uri() . '/js/main.js',
        array(),
        wp_get_theme()->get( 'Version' ),
        true
    );

    // Config para JS
    wp_localize_script( 'audiomania-child-js', 'audiomaniaConfig', array(
        'whatsappNumber' => get_option( '«redacted:am_…»', '34600000000' ),
        'siteUrl'        => esc_url_raw( home_url() ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'audiomania_child_enqueue_scripts', 20 );

/**
 * ------------------------------------------------------------------
 * 3. INJECT ADDITIONAL CUSTOM CSS
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
 * 3.5 INSERT DISCO CANVAS (after body open)
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
 * 4. CUSTOM HEADER WITH NAVIGATION
 * ------------------------------------------------------------------
 */
/**
 * ------------------------------------------------------------------
 * 4. CUSTOM HEADER WITH NAVIGATION
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
 * 5. CUSTOM FOOTER
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
                    <li>📞 <a href="tel:+346****0000">+34 600 000 000</a></li>
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
 * 6. WHATSAPP FLOATING BUTTON
 * ------------------------------------------------------------------
 */
function audiomania_child_whatsapp_button() {
    if ( is_admin() ) return;
    $number = get_option( '«redacted:am_…»', '34600000000' );
    $number = preg_replace( '/[^0-9]/', '', $number );
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
 * 7. OVERRIDES FOR WOOCOMMERCE
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

add_filter( 'loop_shop_columns', function() {
    return 3;
} );

add_filter( 'loop_shop_per_page', function( $cols ) { return 12; }, 20 );

add_action( 'wp_enqueue_scripts', function() {
    wp_dequeue_style( 'woocommerce-smallscreen' );
    wp_dequeue_style( 'woocommerce-general' );
}, 100 );

/**
 * ------------------------------------------------------------------
 * 8. MOBILE MENU JS INJECTION
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
                if (window.innerWidth > 768) {
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
 * 9. ADMIN: CUSTOMIZER ENHANCEMENTS
 * ------------------------------------------------------------------
 */
function audiomania_child_customize_register( $wp_customize ) {
    // WhatsApp number
    $wp_customize->add_setting( '«redacted:am_…»', array(
        'default'           => '34600000000',
        'sanitize_callback' => 'esc_telephone',
    ) );
    $wp_customize->add_control( '«redacted:am_…»', array(
        'label'   => __( 'Número de WhatsApp', 'audiomania-events-child' ),
        'section' => 'title_tagline',
        'type'    => 'text',
    ) );

    // Hero section settings
    $wp_customize->add_section( 'am_hero', array(
        'title'    => __( 'Sección Hero', 'audiomania-events-child' ),
        'priority' => 30,
    ) );

    $wp_customize->add_setting( '«redacted:am_…»', array(
        'default' => 'Sonido e Iluminación Profesional para tu Evento',
    ) );
    $wp_customize->add_control( '«redacted:am_…»', array(
        'label'   => __( 'Título del Hero', 'audiomania-events-child' ),
        'section' => 'am_hero',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( '«redacted:am_…»', array(
        'default' => 'DJ, sonido, iluminación, photocall y más.',
    ) );
    $wp_customize->add_control( '«redacted:am_…»', array(
        'label'   => __( 'Subtítulo del Hero', 'audiomania-events-child' ),
        'section' => 'am_hero',
        'type'    => 'textarea',
    ) );

    $wp_customize->add_setting( '«redacted:am_…»', array(
        'default' => 'Solicitar Presupuesto',
    ) );
    $wp_customize->add_control( '«redacted:am_…»', array(
        'label'   => __( 'Texto del Botón CTA', 'audiomania-events-child' ),
        'section' => 'am_hero',
        'type'    => 'text',
    ) );
}
add_action( 'customize_register', 'audiomania_child_customize_register' );

/**
 * ------------------------------------------------------------------
 * 10. SHORTCODES
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
 * 11. PERFORMANCE
 * ------------------------------------------------------------------
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'wp_lazy_loading_enabled', '__return_true' );

/**
 * ------------------------------------------------------------------
 * 12. SECURITY
 * ------------------------------------------------------------------
 */
add_filter( 'login_errors', function() { return null; } );
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'template_redirect', 'rest_output_link_header', 11 );
