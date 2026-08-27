<?php
/**
 * Audiomania Eventos Child Theme — header.php
 *
 * Custom header with logo image + text, navigation menu, and CTA buttons.
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <!-- Favicons -->
    <link rel="icon" href="<?php echo esc_url( home_url( '/wp-content/uploads/2026/08/logo-trasparente-final-32x32.png' ) ); ?>" sizes="32x32">
    <link rel="icon" href="<?php echo esc_url( home_url( '/wp-content/uploads/2026/08/logo-trasparente-final-16x16.png' ) ); ?>" sizes="16x16">
    <link rel="apple-touch-icon" href="<?php echo esc_url( home_url( '/wp-content/uploads/2026/08/logo-trasparente-final-180x180.png' ) ); ?>">
    <link rel="shortcut icon" href="<?php echo esc_url( home_url( '/wp-content/uploads/2026/08/logo-trasparente-final.png' ) ); ?>">
    
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header am-header" role="banner">
    <div class="header-inner">
        <!-- Logo: imagen + texto -->
        <div class="header-left">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-branding">
                <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/08/logo-trasparente-final.png' ) ); ?>" class="brand-logo-img" alt="Audiomania Eventos Logo" width="50" height="auto">
                <span class="brand-text">AUDIOMANIA<span class="brand-dot">.</span><span class="brand-sub">eventos</span></span>
            </a>
        </div>

        <!-- Navigation Menu -->
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
            <!-- Mobile CTA Buttons (shown only on mobile via CSS) -->
            <div class="mobile-cta-group">
                <a href="<?php echo esc_url( home_url( '/reservar/' ) ); ?>" class="cta-btn cta-btn-primary">
                    Reservar
                </a>
                <a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>" class="cta-btn cta-btn-outline">
                    Contactar
                </a>
            </div>
        </nav>

        <!-- CTA Buttons -->
        <div class="header-cta-group">
            <a href="<?php echo esc_url( home_url( '/reservar/' ) ); ?>" class="cta-btn cta-btn-primary">
                Reservar
            </a>
            <a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>" class="cta-btn cta-btn-outline">
                Contactar
            </a>
        </div>

        <!-- Mobile Menu Toggle -->
        <button class="mobile-menu-toggle" aria-label="Abrir menú" aria-expanded="false">
            <span class="hamburger-icon">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </span>
        </button>
    </div>
</header>

<main id="primary" class="site-main">
