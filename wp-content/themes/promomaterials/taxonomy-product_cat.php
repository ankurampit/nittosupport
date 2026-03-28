<?php

$sort = isset($_GET['sort']) ? sanitize_text_field(wp_unslash($_GET['sort'])) : 'default';

if (have_posts()) {
    global $wp_query;

    $products = [];

    while (have_posts()) {
        the_post();
        $products[] = wc_get_product(get_the_ID());
    }

    wp_reset_postdata();

    if ($sort !== 'default') {

        usort($products, function ($a, $b) use ($sort) {

            /* LATEST */
            if ($sort === 'latest') {
                return $b->get_date_created()->getTimestamp() <=> $a->get_date_created()->getTimestamp();
            }

            /* PRICE LOW */
            if ($sort === 'price_low') {
                return (float)$a->get_price() <=> (float)$b->get_price();
            }

            /* PRICE HIGH */
            if ($sort === 'price_high') {
                return (float)$b->get_price() <=> (float)$a->get_price();
            }

            /* POPULARITY (sales + sale products first) */
            if ($sort === 'popularity') {

                $salesA = $a->get_total_sales();
                $salesB = $b->get_total_sales();

                if ($salesA === $salesB) {

                    /* prioritize sale products */
                    if ($a->is_on_sale() && !$b->is_on_sale()) {
                        return -1;
                    }

                    if ($b->is_on_sale() && !$a->is_on_sale()) {
                        return 1;
                    }

                    return 0;
                }

                return $salesB <=> $salesA;
            }

            return 0;
        });
    }
} else {
    $products = [];
}

get_header();
get_template_part('header-inner');
?>

<section class="pm-store-page">

    <div class="pm-store-shell">
        <?php woocommerce_breadcrumb(); ?>

        <div class="pm-store-hero">

            <h1><?php single_term_title(); ?></h1>
            <p class="pm-hero-subtitle">
                Browse products available in this category.
            </p>
        </div>

        <div class="pm-store-content">

            <!-- MAIN PRODUCTS -->
            <main class="pm-store-main">

                <div class="pm-store-toolbar">

                    <h2>Products</h2>

                    <form class="pm-sort-form" method="get">

                        <label for="pm-sort">Sort by</label>

                        <select id="pm-sort" name="sort" onchange="this.form.submit()">

                            <option value="default" <?php selected($sort, 'default'); ?>>
                                Default
                            </option>

                            <option value="latest" <?php selected($sort, 'latest'); ?>>
                                Latest
                            </option>

                            <option value="price_low" <?php selected($sort, 'price_low'); ?>>
                                Price: low to high
                            </option>

                            <option value="price_high" <?php selected($sort, 'price_high'); ?>>
                                Price: high to low
                            </option>

                            <option value="popularity" <?php selected($sort, 'popularity'); ?>>
                                Popularity
                            </option>

                        </select>

                    </form>

                </div>


                <div class="pm-product-grid">

                    <?php if (have_posts()) : ?>

                        <?php while (have_posts()) : the_post();

                            $product = wc_get_product(get_the_ID());

                        ?>

                            <div class="pm-product-card">

                                <a href="<?php the_permalink(); ?>">

                                    <div class="pm-product-image-wrap">

                                        <?php if ($product->is_on_sale()) : ?>
                                            <span class="pm-sale-badge">Sale</span>
                                        <?php endif; ?>

                                        <?php echo $product->get_image('medium', array('class' => 'pm-product-image')); ?>

                                    </div>

                                </a>

                                <div class="pm-product-card-body">

                                    <h3>
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?>
                                        </a>
                                    </h3>

                                    <div class="pm-product-price">
                                        <?php echo $product->get_price_html(); ?>
                                    </div>

                                </div>

                            </div>

                        <?php endwhile; ?>

                    <?php else : ?>

                        <p class="pm-empty-state">No products found in this category.</p>

                    <?php endif; ?>

                </div>

            </main>


            <!-- SIDEBAR -->
            <aside class="pm-store-sidebar">

                <div class="pm-side-box">

                    <h3>Categories</h3>

                    <ul>

                        <?php
                        $categories = get_terms(array(
                            'taxonomy' => 'product_cat',
                            'hide_empty' => true
                        ));

                        foreach ($categories as $category) :
                        ?>

                            <li>
                                <a href="<?php echo esc_url(get_term_link($category)); ?>">
                                    <?php echo esc_html($category->name); ?>
                                </a>
                            </li>

                        <?php endforeach; ?>

                    </ul>

                </div>


                <div class="pm-side-box pm-special-box">

                    <h3>Special</h3>

                    <p>
                        Discover premium promotional materials and branded merchandise designed to elevate your marketing campaigns.
                    </p>

                </div>

            </aside>

        </div>

    </div>

</section>

<?php get_footer();
