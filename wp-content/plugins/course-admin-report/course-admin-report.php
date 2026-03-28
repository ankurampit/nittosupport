<?php
/**
 * Plugin Name: Course Admin Report
 * Description: Admin page to see which user has taken which courses and completion percentage. Extendable for any LMS via filter hooks.
 * Version: 0.1
 * Author: Your Name
 * Text Domain: course-admin-report
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* Basic constants */
define( 'CAR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CAR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

$user_id = 0;

if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);
}

/* Includes */
require_once CAR_PLUGIN_DIR . 'includes/helpers.php';
require_once CAR_PLUGIN_DIR . 'includes/admin-page.php';
require_once CAR_PLUGIN_DIR . 'includes/user-courses-page.php';
include_once CAR_PLUGIN_DIR . 'includes/ajax-course-stats.php';


/* Admin menu */
add_action( 'admin_menu', function(){

    add_menu_page(
        __( 'Course Report', 'course-admin-report' ),
        __( 'Course Report', 'course-admin-report' ),
        'manage_options',
        'course-admin-report',
        'car_render_admin_page',
        'dashicons-clipboard',
        60
    );

    /* Hidden submenu for user courses page */
    add_submenu_page(
        null,
        'User Courses',
        'User Courses',
        'manage_options',
        'car_view_user_courses',
        'car_render_user_course_page'
    );
});

add_action('wp_ajax_car_update_dollar', 'car_update_dollar');

function car_update_dollar()
{
    global $wpdb;

    $table = $wpdb->prefix . 'wallet_transactions';
    $user_id = intval($_POST['user_id']);
    $amount  = floatval($_POST['amount']);
    $type    = sanitize_text_field($_POST['type']);


    if (!$user_id || !$amount) {
        wp_send_json_error();
    }

    $data = [
        'user_id'            => $user_id,
        'date'               => current_time('mysql'),
        'description' => $type === 'add' ? 'addition' : 'deduction',
        'amount'             => $amount,
        'amount_real'        => 0.00,
        'grand_total_amount' => 0.00,
        'transaction_id'     => '',
        'is_deleted'         => 0
    ];

    $wpdb->insert(
        $table,
        $data,
        [
            '%d',
            '%s',
            '%s',
            '%f',
            '%f',
            '%f',
            '%s',
            '%d'
        ]
    );

    wp_send_json_success([
        'id' => $wpdb->insert_id,
        'date' => current_time('mysql'),
        'description' => $type,
        'amount' => $amount
    ]);
}

add_action('wp_ajax_delete_wallet_transactions', 'delete_wallet_transactions');

function delete_wallet_transactions()
{
    global $wpdb;

    $table = $wpdb->prefix . 'wallet_transactions';

    if (!isset($_POST['ids'])) {
        wp_send_json_error();
    }

    $user_id = intval($_POST['user_id']);
    $total_earned = isset($_POST['total_earned']) ? floatval($_POST['total_earned']) : 0;

    $ids = array_map('intval', $_POST['ids']);

    // Example soft delete
    foreach ($ids as $id) {
        $wpdb->update(
            $table,
            ['is_deleted' => 1],
            ['id' => $id],
            ['%d'],
            ['%d']
        );
    }

    $available_balance = get_available_balance($user_id, $total_earned);

    wp_send_json_success([
        'available_balance' => number_format((float)$available_balance, 2),
        'total_earned' => number_format((float)$total_earned, 2),
        'total_earned_get_by_post' => $total_earned,
        'user_id_get_by_post' => $user_id
    ]);
}


/* Enqueue admin assets */
add_action( 'admin_enqueue_scripts', function( $hook ) {

    if (
        $hook !== 'toplevel_page_course-admin-report' &&
        $hook !== 'users_page_car_view_user_courses'
    ) {
        return;
    }

    wp_enqueue_style(
        'car-admin-css',
        CAR_PLUGIN_URL . 'assets/admin.css',
        [],
        '1.0'
    );

    wp_enqueue_script(
        'car-admin-js',
        CAR_PLUGIN_URL . 'assets/admin.js',
        ['jquery'],
        '1.0',
        true
    );

    /* Font Awesome */
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css',
        [],
        '5.15.4'
    );
});


/**
 * Add "Courses" column in Users table
 */
add_filter('manage_users_columns', function ($columns) {

    $columns['car_user_courses'] = __('Courses', 'course-admin-report');
    return $columns;
});


/**
 * Add button inside the Courses column
 */
add_filter('manage_users_custom_column', function ($output, $column_name, $user_id) {

    if ($column_name === 'car_user_courses') {

        $url = admin_url('users.php?page=car_view_user_courses&user_id=' . $user_id);

        return '<a href="' . esc_url($url) . '" class="button button-primary">View Courses</a>';
    }

    return $output;

}, 10, 3);
