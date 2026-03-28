<?php
/**
 * Proxy header for promomaterials theme.
 *
 * Uses the shared multisite header from wplmsblankchildhtheme.
 */

$shared_header = WP_CONTENT_DIR . '/themes/wplmsblankchildhtheme/header.php';

if ( file_exists( $shared_header ) ) {
    require $shared_header;
    return;
}

// Fallback so the site remains usable if shared file is missing.
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

