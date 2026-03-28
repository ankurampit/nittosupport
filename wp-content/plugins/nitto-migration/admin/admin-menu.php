<?php

if (!defined('ABSPATH')) {
    exit;
}

function nitto_migration_admin_menu()
{

    add_menu_page(
        'Nitto Migration',
        'Nitto Migration',
        'manage_options',
        'nitto-migration-dashboard',
        'nitto_migration_dashboard_page',
        'dashicons-database-import',
        26
    );

    add_submenu_page(
        'nitto-migration-dashboard',
        'Dashboard',
        'Dashboard',
        'manage_options',
        'nitto-migration-dashboard',
        'nitto_migration_dashboard_page'
    );

    add_submenu_page(
        'nitto-migration-dashboard',
        'Migrate Products',
        'Products',
        'manage_options',
        'nitto-migration-products',
        'nitto_migration_products_page'
    );

    add_submenu_page(
        'nitto-migration-dashboard',
        'Migrate Products',
        'Products Of Point Of Purchase',
        'manage_options',
        'nitto-migration-products-pos',
        'nitto_migration_products_pos_page'
    );

    add_submenu_page(
        'nitto-migration-dashboard',
        'Migrate POS Orders',
        'Orders Of Point Of Purchase',
        'manage_options',
        'nitto-migration-pos-orders',
        'nitto_migration_pos_orders_page'
    );
    add_submenu_page(
        'nitto-migration-dashboard',
        'Migrate Promo Orders',
        'Orders Of Promomaterials',
        'manage_options',
        'nitto-migration-orders',
        'nitto_migration_orders_page'
    );

    add_submenu_page(
        'nitto-migration-dashboard',
        'Users Migration',
        'Users',
        'manage_options',
        'nitto-migration-users',
        'nitto_migration_users_page'
    );
}

add_action('admin_menu', 'nitto_migration_admin_menu');
