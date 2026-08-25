<?php
/**
 * Audiomania Eventos Child Theme — header.php
 *
 * Custom header with navigation menu. Overrides hello-elementor default header.
 * Uses the custom audiomania_child_header() function defined in functions.php.
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php audiomania_child_header(); ?>

<main id="primary" class="site-main">
