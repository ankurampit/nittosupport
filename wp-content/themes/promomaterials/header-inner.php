<?php
/**
 * Proxy inner header for promomaterials theme.
 *
 * Uses the shared multisite inner header from wplmsblankchildhtheme.
 */

$shared_inner_header = WP_CONTENT_DIR . '/themes/wplmsblankchildhtheme/header-inner.php';

if ( file_exists( $shared_inner_header ) ) {
    require $shared_inner_header;
    return;
}

// Fallback markup if the shared file is unavailable.
?>
<section class="cl-wrapper">
    <div class="container"></div>
</section>
