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

/* Includes */
require_once CAR_PLUGIN_DIR . 'includes/helpers.php';
require_once CAR_PLUGIN_DIR . 'includes/admin-page.php';
require_once CAR_PLUGIN_DIR . 'includes/user-courses-page.php';
include_once plugin_dir_path(__FILE__) . 'includes/ajax-course-stats.php';



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
});

/* Enqueue admin assets */
add_action( 'admin_enqueue_scripts', function( $hook ) {
    if ( $hook !== 'toplevel_page_course-admin-report' ) return;
    wp_enqueue_style( 'car-admin-css', CAR_PLUGIN_URL . 'assets/admin.css' );
    wp_enqueue_script( 'car-admin-js', CAR_PLUGIN_URL . 'assets/admin.js', ['jquery'], false, true );
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


/**
 * Add hidden submenu for course details page
 */
add_action('admin_menu', function () {
    add_submenu_page(
        null,                                 // No menu visible
        'User Courses',
        'User Courses',
        'manage_options',
        'car_view_user_courses',
        'car_render_user_course_page'
    );
});

add_action('admin_enqueue_scripts', 'car_load_admin_styles');
function car_load_admin_styles() {
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css',
        [],
        '5.15.4'
    );
}

