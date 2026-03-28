<?php

/**
 * Template Name: Store Page (Copy)
 */

get_header();
get_template_part('header-inner');
?>
<style id="pm-store-single-scroll-fix">
    html,
    body {
        height: auto !important;
        min-height: 100%;
        overflow-y: auto !important;
    }

    .pm-store-page,
    .pm-wrap,
    .pm-store-content,
    .pm-store-main,
    .pm-store-sidebar {
        height: auto !important;
        max-height: none !important;
        overflow: visible !important;
    }
</style>
<?php
$current_user = wp_get_current_user();
$welcome_name = $current_user && $current_user->exists() ? $current_user->display_name : 'Dealer';

$feature_links = array(
    array('label' => 'Advertising Material', 'url' => '#'),
    array('label' => 'Dealer Resources', 'url' => '#'),
    array('label' => 'Training Site', 'url' => '#'),
    array('label' => 'Promo Materials', 'url' => '#'),
    array('label' => 'Point of Purchase', 'url' => '#'),
    array('label' => 'Management', 'url' => '#'),
);

$products = array();

if (function_exists('wc_get_products')) {
    $products = wc_get_products(
        array(
            'status'   => 'publish',
            'featured' => true,
            'limit'    => 9,
            'orderby'  => 'date',
            'order'    => 'DESC',
        )
    );

    if (empty($products)) {
        $products = wc_get_products(
            array(
                'status'  => 'publish',
                'limit'   => 9,
                'orderby' => 'date',
                'order'   => 'DESC',
            )
        );
    }
}

$sort = isset($_GET['sort']) ? sanitize_text_field(wp_unslash($_GET['sort'])) : 'default';

if (!empty($products) && in_array($sort, array('price_low', 'price_high', 'name'), true)) {
    usort(
        $products,
        static function ($a, $b) use ($sort) {
            if ('name' === $sort) {
                return strcmp($a->get_name(), $b->get_name());
            }

            $price_a = (float) wc_get_price_to_display($a);
            $price_b = (float) wc_get_price_to_display($b);

            if ('price_low' === $sort) {
                return $price_a <=> $price_b;
            }

            return $price_b <=> $price_a;
        }
    );
}

$categories = get_terms(
    array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'number'     => 12,
    )
);
?>

<main class="pm-store-page">
    <section class="pm-wrap pm-store-content">
        <div class="pm-store-main">
            <header class="pm-store-heading">
                <h1>Welcome: <span><?php echo esc_html($welcome_name); ?></span> to the Toyo Promotional Items</h1>
                <h2>Featured Products</h2>
            </header>

            <form class="pm-sort-form" action="" method="get">
                <label for="pm-sort" class="screen-reader-text">Sort products</label>
                <select id="pm-sort" name="sort" onchange="this.form.submit()">
                    <option value="default" <?php selected($sort, 'default'); ?>>Default sorting</option>
                    <option value="price_low" <?php selected($sort, 'price_low'); ?>>Price: low to high</option>
                    <option value="price_high" <?php selected($sort, 'price_high'); ?>>Price: high to low</option>
                    <option value="name" <?php selected($sort, 'name'); ?>>Name</option>
                </select>
            </form>

            <?php if (!empty($products)) : ?>
                <div class="pm-product-grid">
                    <?php foreach ($products as $product) : ?>
                        <article class="pm-product-card">
                            <a class="pm-product-image-wrap" href="<?php echo esc_url(get_permalink($product->get_id())); ?>">
                                <?php if ($product->is_on_sale()) : ?>
                                    <span class="pm-sale-badge">Sale</span>
                                <?php endif; ?>

                                <?php if ($product->get_image_id()) : ?>
                                    <?php echo wp_kses_post($product->get_image('woocommerce_thumbnail', array('class' => 'pm-product-image'))); ?>
                                <?php else : ?>
                                    <span class="pm-product-placeholder">No image</span>
                                <?php endif; ?>
                            </a>
                            <h3><a href="<?php echo esc_url(get_permalink($product->get_id())); ?>"><?php echo esc_html($product->get_name()); ?></a></h3>
                            <div class="pm-product-price"><?php echo wp_kses_post($product->get_price_html()); ?></div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p class="pm-empty-state">No products available right now. Add WooCommerce products to populate this page.</p>
            <?php endif; ?>
        </div>

        <aside class="pm-store-sidebar" aria-label="Product categories">
            <div class="pm-side-box">
                <h3>Categories</h3>
                <ul>
                    <?php if (!empty($categories) && !is_wp_error($categories)) : ?>
                        <?php foreach ($categories as $category) : ?>
                            <li>
                                <a href="<?php echo esc_url(get_term_link($category)); ?>">
                                    <?php echo esc_html($category->name); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <li><a href="#">New for Fall</a></li>
                        <li><a href="#">View Entire Catalogue</a></li>
                        <li><a href="#">Stock Items</a></li>
                        <li><a href="#">Headwear</a></li>
                        <li><a href="#">Men's Apparel</a></li>
                        <li><a href="#">Ladies Apparel</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="pm-side-box pm-special-box">
                <h3>Specials</h3>
                <p>Seasonal items and limited offers appear here automatically when marked as featured or on sale.</p>
            </div>
        </aside>
    </section>
</main>

<?php get_footer();
