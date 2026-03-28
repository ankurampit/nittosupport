<?php
get_header();
get_template_part('header-inner');
?>

<main class="pm-site-main">

    <div class="pm-container">

        <?php
        if (function_exists('is_cart') && is_cart()) :
        ?>

            <!-- CART PAGE -->

            <section class="pm-cart-main">
                <?php woocommerce_breadcrumb(); ?>

                <div class="pm-page-header">
                    <h1 class="pm-page-title">Your Shopping Cart</h1>
                    <p class="pm-page-subtitle">
                        Review your items before proceeding to checkout
                    </p>
                </div>

                <?php do_action('woocommerce_before_cart'); ?>

                <div class="pm-cart-wrapper">
                    <?php echo do_shortcode('[woocommerce_cart]'); ?>
                </div>

                <?php do_action('woocommerce_after_cart'); ?>

            </section>

        <?php
        elseif (function_exists('is_checkout') && is_checkout() && !is_order_received_page()) :
        ?>

            <!-- CHECKOUT PAGE -->

            <section class="pm-checkout-main">

                <div class="pm-page-header">
                    <h1 class="pm-page-title">Secure Checkout</h1>
                    <p class="pm-page-subtitle">
                        Complete your purchase safely
                    </p>
                </div>

                <div class="pm-checkout-wrapper">
                    <?php echo do_shortcode('[woocommerce_checkout]'); ?>
                </div>

            </section>

        <?php
        else :
        ?>

            <!-- NORMAL PAGE CONTENT -->

            <?php if (have_posts()) : ?>

                <?php while (have_posts()) : the_post(); ?>

                    <article class="pm-page-content">

                        <h1 class="pm-page-title">
                            <?php the_title(); ?>
                        </h1>

                        <div class="pm-page-body">
                            <?php the_content(); ?>
                        </div>

                    </article>

                <?php endwhile; ?>

            <?php else : ?>

                <p>No content found.</p>

            <?php endif; ?>

        <?php
        endif;
        ?>

    </div>

</main>

<?php get_footer();
