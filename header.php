<?php
/**
 * Audiomania Eventos Child Theme — header.php
 *
 * Custom header with logo image, favicons, and navigation menu.
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
        <div class="header-left">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-branding">
                <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/08/logo-trasparente-final.png' ) ); ?>" alt="Audiomania Eventos" class="site-logo" width="80" height="60">
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

<main id="primary" class="site-main">
