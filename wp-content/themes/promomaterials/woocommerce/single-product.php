<?php
defined('ABSPATH') || exit;

get_header();
get_template_part('header-inner');
?>

<main class="pm-store-page">

    <section class="pm-store">

        <div class="pm-store-body">
            <?php woocommerce_breadcrumb(); ?>

            <?php while (have_posts()) : the_post(); ?>

                <div class="pm-product-layout">

                    <div class="pm-product-gallery">
                        <?php woocommerce_show_product_images(); ?>
                    </div>

                    <div class="pm-product-summary">

                        <h1 class="pm-product-title"><?php the_title(); ?></h1>

                        <div class="pm-product-price">
                            <?php woocommerce_template_single_price(); ?>
                        </div>

                        <div class="pm-product-excerpt">
                            <?php woocommerce_template_single_excerpt(); ?>
                        </div>

                        <div class="pm-product-cart">
                            <?php woocommerce_template_single_add_to_cart(); ?>
                        </div>

                        <div class="pm-product-meta">
                            <?php woocommerce_template_single_meta(); ?>
                        </div>

                    </div>

                </div>

                <div class="pm-product-tabs">
                    <?php woocommerce_output_product_data_tabs(); ?>
                </div>

            <?php endwhile; ?>

        </div>

    </section>
</main>

<?php get_footer();
