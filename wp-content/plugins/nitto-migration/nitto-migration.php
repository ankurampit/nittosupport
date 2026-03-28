<?php
/*
Plugin Name: Nitto Migration
Description: Migrate data from Magento and Laravel into WordPress.
Version: 1.0
Author: Your Name
*/

if (!defined('ABSPATH')) {
    exit;
}

/*
|--------------------------------------------------------------------------
| Constants
|--------------------------------------------------------------------------
*/

define('NITTO_MIGRATION_PATH', plugin_dir_path(__FILE__));
define('NITTO_MIGRATION_URL', plugin_dir_url(__FILE__));

/*
|--------------------------------------------------------------------------
| Load Files
|--------------------------------------------------------------------------
*/

require_once NITTO_MIGRATION_PATH . 'includes/loader.php';
require_once NITTO_MIGRATION_PATH . 'admin/admin-menu.php';

/*
|--------------------------------------------------------------------------
| Load Admin Assets
|--------------------------------------------------------------------------
*/

function nitto_migration_admin_assets($hook)
{

    if (strpos($hook, 'nitto-migration') === false) {
        return;
    }

    wp_enqueue_style(
        'nitto-migration-admin',
        NITTO_MIGRATION_URL . 'assets/css/admin.css'
    );

    wp_enqueue_script(
        'nitto-migration-admin',
        NITTO_MIGRATION_URL . 'assets/js/admin.js',
        ['jquery'],
        false,
        true
    );

    wp_enqueue_script(
        'nitto-migration-products',
        NITTO_MIGRATION_URL . 'assets/js/products.js',
        ['jquery'],
        false,
        true
    );

    wp_enqueue_script(
        'nitto-migration-orders',
        NITTO_MIGRATION_URL . 'assets/js/orders.js',
        ['jquery'],
        false,
        true
    );

    wp_localize_script(
        'nitto-migration-products',
        'nitto_ajax',
        array(
            'ajax_url' => admin_url('admin-ajax.php')
        )
    );
}

add_action('admin_enqueue_scripts', 'nitto_migration_admin_assets');
