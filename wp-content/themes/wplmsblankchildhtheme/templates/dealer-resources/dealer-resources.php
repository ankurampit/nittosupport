<?php

/**
 * Template Name: Dealer Resources
 * Template Post Type: page
 */

get_header();
require_once get_stylesheet_directory() . '/header-inner.php';

?>

<div class="dealer-resources-wrapper">
    <div class="dealer-container main-layout">

        <!-- LEFT CONTENT -->
        <div class="main-content">


            <p class="page-title">Dealer Resources</p>

            <div class="resources-grid">


                <div class="resource-item">
                    <a href="<?php echo get_permalink(get_page_by_path('catalogues-download')); ?>" class="resource-link">
                        <div class="icon-circle">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/dealer1.png" alt="">
                        </div>
                        <span>Catalogue Download</span>
                    </a>
                </div>

                <div class="resource-item">
                    <div class="icon-circle">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/dealer2.png" alt="">
                    </div>
                    <span>Regional Manager</span>
                </div>

                <div class="resource-item">
                    <div class="icon-circle">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/brochure.png" alt="">
                    </div>
                    <span>Brochures and Sell sheets</span>
                </div>

                <div class="resource-item">
                    <div class="icon-circle">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/dealer3.png" alt="">
                    </div>
                    <span>MISC Documents</span>
                </div>
            </div>

            <p class="resource-description">
                Dealer resources are intended to provide Nitto Dealers with quick up to date information without having to wait for it.
                Please keep all this information private as per the Nitto Dealer agreement.
            </p>

            <div class="bottom-section">

                <div class="video-section">
                    <h3 class="section-title">Video of the week</h3>
                    <div class="video-wrapper">
                        <iframe width="100%" height="400"
                            src="https://www.youtube.com/embed/YOUR_VIDEO_ID"
                            frameborder="0"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>

                <div class="instagram-section">
                    <h3 class="section-title">Instagram</h3>
                    <div class="instagram-embed">
                        <iframe src="https://www.instagram.com/p/YOUR_POST/embed"
                            width="100%"
                            height="600"
                            frameborder="0"
                            scrolling="no">
                        </iframe>
                    </div>
                    
                </div>

            </div>

        </div>

        <?php require_once get_stylesheet_directory() . '/templates/admaterials/features-menu.php'; ?>


    </div>

</div>

</div>

<?php get_footer();