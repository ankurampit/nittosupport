<?php
/**
 * Proxy footer for promomaterials theme.
 *
 * Uses the shared multisite footer from wplmsblankchildhtheme.
 */

$shared_footer = WP_CONTENT_DIR . '/themes/wplmsblankchildhtheme/footer.php';

if ( file_exists( $shared_footer ) ) {
    require $shared_footer;
    return;
}
?>
<?php wp_footer(); ?>
</body>
</html>
