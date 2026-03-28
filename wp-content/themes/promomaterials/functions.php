<?php

/* =========================================
   REGISTER MENU
========================================= */
function promomaterials_register_menus() {
    register_nav_menus(
        array(
            'header_menu' => __('Header Menu', 'nittosupport'),
        )
    );
}
add_action('init', 'promomaterials_register_menus');


/* =========================================
   CUSTOM WALKER
========================================= */
if (!class_exists('Custom_Walker_Nav_Menu')) {
    class Custom_Walker_Nav_Menu extends Walker_Nav_Menu {
        public function start_lvl(&$output, $depth = 0, $args = null) {
            $output .= '<ul class="dropdown-menu">';
        }
    }
}


/* =========================================
   REGISTER STORE TEMPLATE
========================================= */
add_filter('theme_page_templates', function ($templates) {
    $templates['templates/store.php'] = __('Store Page', 'nittosupport');
    return $templates;
});

add_filter('template_include', function ($template) {
    $requested_template = get_page_template_slug();

    if ('templates/store.php' === $requested_template) {
        $store_template = get_stylesheet_directory() . '/templates/store.php';

        if (file_exists($store_template)) {
            return $store_template;
        }
    }

    return $template;
});


/* =========================================
   LOAD FONT AWESOME
========================================= */
function promomaterials_load_fontawesome() {
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
        array(),
        '6.5.1'
    );
}
add_action('wp_enqueue_scripts', 'promomaterials_load_fontawesome');


/* =========================================
   WOOCOMMERCE SUPPORT
========================================= */
function promomaterials_setup_woocommerce_support() {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'promomaterials_setup_woocommerce_support');


/* =========================================
   LOAD STORE PAGE CSS
========================================= */
function promomaterials_enqueue_store_assets() {

    if (
        is_page_template('templates/store.php') ||
        is_product_category() ||
        is_shop()
    ) {

        $store_css = get_stylesheet_directory() . '/assets/css/store-page.css';

        if (file_exists($store_css)) {

            wp_enqueue_style(
                'promomaterials-store-page',
                get_stylesheet_directory_uri() . '/assets/css/store-page.css',
                array(),
                filemtime($store_css)
            );

        }

    }

}
add_action('wp_enqueue_scripts', 'promomaterials_enqueue_store_assets');

/* =========================================
   LOAD WOOCOMMERCE CSS
========================================= */
function promomaterials_enqueue_woocommerce_assets() {

    if (!function_exists('is_woocommerce')) {
        return;
    }

    // Product Page
    if (is_product()) {

        $single_product_css = get_stylesheet_directory() . '/assets/css/single-product.css';

        if (file_exists($single_product_css)) {
            wp_enqueue_style(
                'promomaterials-single-product',
                get_stylesheet_directory_uri() . '/assets/css/single-product.css',
                array(),
                filemtime($single_product_css)
            );
        }
    }

    // Cart Page
    if (is_cart()) {

        $cart_css = get_stylesheet_directory() . '/assets/css/cart.css';

        if (file_exists($cart_css)) {
            wp_enqueue_style(
                'promomaterials-cart-style',
                get_stylesheet_directory_uri() . '/assets/css/cart.css',
                array(),
                filemtime($cart_css)
            );
        }
    }

    // Checkout Page
    if (is_checkout()) {

        $checkout_css = get_stylesheet_directory() . '/assets/css/checkout.css';

        if (file_exists($checkout_css)) {
            wp_enqueue_style(
                'promomaterials-checkout-style',
                get_stylesheet_directory_uri() . '/assets/css/checkout.css',
                array(),
                filemtime($checkout_css)
            );
        }
    }
}
add_action('wp_enqueue_scripts', 'promomaterials_enqueue_woocommerce_assets');


/* Auto hide WooCommerce notices after 5 seconds */
add_action('wp_footer', function() {
    if (is_cart()) :
?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    setTimeout(function() {
        const notices = document.querySelectorAll('.woocommerce-message, .woocommerce-error, .woocommerce-info');
        notices.forEach(function(el) {
            el.style.transition = "opacity 0.6s ease, transform 0.6s ease";
            el.style.opacity = "0";
            el.style.transform = "translateY(-10px)";
            setTimeout(() => el.remove(), 600);
        });
    }, 3000);
});
</script>
<?php
    endif;
});



function promomaterials_checkout_styles() {

    if ( is_checkout() ) {

        wp_enqueue_style(
            'pm-checkout-style',
            get_template_directory_uri() . '/css/checkout.css',
            array(),
            '1.0'
        );

    }

}

add_action('wp_enqueue_scripts', 'promomaterials_checkout_styles'); 

function promomaterials_enqueue_assets() {

    wp_enqueue_style(
        'promomaterials-style',
        get_stylesheet_uri(),
        array(),
        '1.0'
    );

}

add_action('wp_enqueue_scripts', 'promomaterials_enqueue_assets');

/* ===================================
PRODUCT CATEGORY SORTING
=================================== */

function pm_custom_product_sorting($query) {

    if (is_admin() || !$query->is_main_query()) {
        return;
    }

    if (is_product_category() && isset($_GET['sort'])) {

        $sort = sanitize_text_field(wp_unslash($_GET['sort']));

        switch ($sort) {

            case 'price_low':

                $query->set('meta_key', '_price');
                $query->set('orderby', 'meta_value_num');
                $query->set('order', 'ASC');

            break;


            case 'price_high':

                $query->set('meta_key', '_price');
                $query->set('orderby', 'meta_value_num');
                $query->set('order', 'DESC');

            break;


            case 'latest':

                $query->set('orderby', 'date');
                $query->set('order', 'DESC');

            break;


            case 'popularity':

                $query->set('meta_key', 'total_sales');
                $query->set('orderby', 'meta_value_num');
                $query->set('order', 'DESC');

            break;

        }

    }

}

add_action('pre_get_posts', 'pm_custom_product_sorting');


// Auto-load all PHP files inside /includes folder
foreach (glob(get_template_directory() . '/includes/*.php') as $file) {
    require_once $file;
}