<?php

function get_all_user_groups()
{
    global $wpdb;
    $table_name = 'usergroup';

    $results = $wpdb->get_results("SELECT * FROM $table_name");
    return $results;
}

add_action('admin_post_custom_user_update', 'handle_custom_user_update');

function handle_custom_user_update_old()
{
    if (!is_user_logged_in()) {
        wp_die('You must be logged in.');
    }

    if (
        !isset($_POST['custom_user_update_nonce']) ||
        !wp_verify_nonce($_POST['custom_user_update_nonce'], 'custom_user_update_action')
    ) {
        wp_die('Security check failed.');
    }

    $user_id = intval($_POST['user_id'] ?? 0);

    // Sanitize fields
    $firstname     = sanitize_text_field($_POST['firstname'] ?? '');
    $lastname      = sanitize_text_field($_POST['lastname'] ?? '');
    $email         = sanitize_email($_POST['email'] ?? '');
    $companyname   = sanitize_text_field($_POST['companyname'] ?? '');
    $dealernumber = sanitize_text_field($_POST['dealernumber'] ?? '');
    $address       = sanitize_text_field($_POST['address'] ?? '');
    $city          = sanitize_text_field($_POST['city'] ?? '');
    $province      = sanitize_text_field($_POST['province'] ?? '');
    $postalcode    = sanitize_text_field($_POST['postalcode'] ?? '');
    $phone         = sanitize_text_field($_POST['phone'] ?? '');
    $fax           = sanitize_text_field($_POST['fax'] ?? '');
    $usergroup     = sanitize_text_field($_POST['Usergroup'] ?? '');
    $language      = sanitize_text_field($_POST['lngprefer'] ?? 'en');
    $dealeraccess  = sanitize_text_field($_POST['delaraccess'] ?? 0);
    $salespromo    = sanitize_text_field($_POST['Esalespromo'] ?? 0);
    $tireinfo      = sanitize_text_field($_POST['Etireinfo'] ?? 0);
    $surveys       = sanitize_text_field($_POST['Esurveys'] ?? 0);
    $user_permissions = $_POST['user_permissions'] ?? [];
    print_r($user_permissions);
    die;



    // Update core user data
    wp_update_user(array(
        'ID'         => $user_id,
        'user_email' => $email,
        'first_name' => $firstname,
        'last_name'  => $lastname,
    ));

    // Update meta fields
    update_user_meta($user_id, 'companyname', $companyname);
    update_user_meta($user_id, 'dealernumber', $dealernumber);
    update_user_meta($user_id, 'address', $address);
    update_user_meta($user_id, 'city', $city);
    update_user_meta($user_id, 'province', $province);
    update_user_meta($user_id, 'postalcode', $postalcode);
    update_user_meta($user_id, 'phone', $phone);
    update_user_meta($user_id, 'fax', $fax);
    update_user_meta($user_id, 'usergroup', $usergroup);
    update_user_meta($user_id, 'language_preference', $language);
    update_user_meta($user_id, 'dealer_access', $dealeraccess);
    update_user_meta($user_id, 'esalespromo', $salespromo);
    update_user_meta($user_id, 'etireinfo', $tireinfo);
    update_user_meta($user_id, 'esurveys', $surveys);

    /* Remove old permissions first */
    foreach ($user->allcaps as $cap => $value) {
        if (strpos($cap, 'edit_') === 0) {
            $user->remove_cap($cap);
        }
    }

    wp_safe_redirect(wp_get_referer());
    exit;
}

function handle_custom_user_update()
{
    if (!is_user_logged_in()) {
        wp_die('You must be logged in.');
    }

    if (
        !isset($_POST['custom_user_update_nonce']) ||
        !wp_verify_nonce($_POST['custom_user_update_nonce'], 'custom_user_update_action')
    ) {
        wp_die('Security check failed.');
    }

    $user_id = intval($_POST['user_id'] ?? 0);

    if (!$user_id) {
        wp_die('Invalid user.');
    }

    $user = new WP_User($user_id);

    if (!$user->exists()) {
        wp_die('User not found.');
    }

    // Sanitize fields
    $firstname     = sanitize_text_field($_POST['firstname'] ?? '');
    $lastname      = sanitize_text_field($_POST['lastname'] ?? '');
    $email         = sanitize_email($_POST['email'] ?? '');
    $companyname   = sanitize_text_field($_POST['companyname'] ?? '');
    $dealernumber  = sanitize_text_field($_POST['dealernumber'] ?? '');
    $address       = sanitize_text_field($_POST['address'] ?? '');
    $city          = sanitize_text_field($_POST['city'] ?? '');
    $province      = sanitize_text_field($_POST['province'] ?? '');
    $postalcode    = sanitize_text_field($_POST['postalcode'] ?? '');
    $phone         = sanitize_text_field($_POST['phone'] ?? '');
    $fax           = sanitize_text_field($_POST['fax'] ?? '');
    $usergroup     = sanitize_text_field($_POST['Usergroup'] ?? '');
    $language      = sanitize_text_field($_POST['lngprefer'] ?? 'en');
    $dealeraccess  = sanitize_text_field($_POST['delaraccess'] ?? 0);
    $salespromo    = sanitize_text_field($_POST['Esalespromo'] ?? 0);
    $tireinfo      = sanitize_text_field($_POST['Etireinfo'] ?? 0);
    $surveys       = sanitize_text_field($_POST['Esurveys'] ?? 0);

    $user_permissions = array_map('sanitize_text_field', $_POST['user_permissions'] ?? []);

    // Update core user data
    wp_update_user(array(
        'ID'         => $user_id,
        'user_email' => $email,
        'first_name' => $firstname,
        'last_name'  => $lastname,
    ));

    // Update meta fields
    update_user_meta($user_id, 'companyname', $companyname);
    update_user_meta($user_id, 'dealernumber', $dealernumber);
    update_user_meta($user_id, 'address', $address);
    update_user_meta($user_id, 'city', $city);
    update_user_meta($user_id, 'province', $province);
    update_user_meta($user_id, 'postalcode', $postalcode);
    update_user_meta($user_id, 'phone', $phone);
    update_user_meta($user_id, 'fax', $fax);
    update_user_meta($user_id, 'usergroup', $usergroup);
    update_user_meta($user_id, 'language_preference', $language);
    update_user_meta($user_id, 'dealer_access', $dealeraccess);
    update_user_meta($user_id, 'esalespromo', $salespromo);
    update_user_meta($user_id, 'etireinfo', $tireinfo);
    update_user_meta($user_id, 'esurveys', $surveys);

    $form_permissions = [
        'edit_users',
        'edit_admaterials',
        'edit_featured_items',
        'edit_page_banners',
        'edit_video_of_the_week',
        'edit_bulletin_board_posts',
        'edit_current_events',
        'edit_dealer_resources',
        'edit_point_of_purchase'
    ];

    foreach ($form_permissions as $permission) {

        if (in_array($permission, $user_permissions)) {
            $user->add_cap($permission);
        } else {
            $user->remove_cap($permission);
        }
    }

    wp_safe_redirect(wp_get_referer());
    exit;
}

// Change Password
add_action('wp_ajax_check_current_password', 'check_current_password_callback');

function check_current_password_callback() {

    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'security_nonce')) {
        wp_send_json_error(['message' => 'Invalid request']);
    }

    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => 'User not logged in']);
    }

    $user = wp_get_current_user();
    $entered_pass = $_POST['current_pass'];

    if (wp_check_password($entered_pass, $user->user_pass, $user->ID)) {
        wp_send_json_success(['message' => 'Correct password']);
    } else {
        wp_send_json_error(['message' => 'Incorrect password']);
    }

    wp_die();
}

add_action('wp_ajax_update_user_password', 'update_user_password_callback');

function update_user_password_callback() {

    // Security check
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'security_nonce')) {
        wp_send_json_error(['message' => 'Invalid request']);
    }

    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => 'User not logged in']);
    }

    $user = wp_get_current_user();

    $current_pass = $_POST['current_pass'];
    $new_pass     = $_POST['new_pass'];

    // Verify current password again (VERY IMPORTANT)
    if (!wp_check_password($current_pass, $user->user_pass, $user->ID)) {
        wp_send_json_error(['message' => 'Current password is incorrect']);
    }

    // Update password
    wp_set_password($new_pass, $user->ID);

    // Save current datetime in user meta
    update_user_meta($user->ID, 'password_last_changed', current_time('mysql'));

    wp_send_json_success(['message' => 'Password updated successfully']);

    wp_die();
}


add_action('wp_ajax_update_user_profile', 'handle_ajax_user_update');

function handle_ajax_user_update()
{
    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => 'Not logged in']);
    }

    // Verify nonce
    if (
        !isset($_POST['custom_user_update_nonce']) ||
        !wp_verify_nonce($_POST['custom_user_update_nonce'], 'custom_user_update_action')
    ) {
        wp_send_json_error(['message' => 'Security check failed']);
    }

    $user_id = get_current_user_id();

    // Sanitize
    $firstname    = sanitize_text_field($_POST['firstname'] ?? '');
    $lastname     = sanitize_text_field($_POST['lastname'] ?? '');
    $email        = sanitize_email($_POST['email'] ?? '');
    $companyname  = sanitize_text_field($_POST['companyname'] ?? '');
    $dealernumber = sanitize_text_field($_POST['dealernumber'] ?? '');
    $address      = sanitize_text_field($_POST['address'] ?? '');
    $city         = sanitize_text_field($_POST['city'] ?? '');
    $province     = sanitize_text_field($_POST['province'] ?? '');
    $postalcode   = sanitize_text_field($_POST['postalcode'] ?? '');
    $phone        = sanitize_text_field($_POST['phone'] ?? '');
    $fax          = sanitize_text_field($_POST['fax'] ?? '');

    // Email validation
    if (!is_email($email)) {
        wp_send_json_error(['message' => 'Invalid email']);
    }

    if (email_exists($email) && email_exists($email) != $user_id) {
        wp_send_json_error(['message' => 'Email already in use']);
    }

    // Update core user
    wp_update_user([
        'ID'         => $user_id,
        'user_email' => $email,
        'first_name' => $firstname,
        'last_name'  => $lastname,
    ]);

    // Update meta
    update_user_meta($user_id, 'companyname', $companyname);
    update_user_meta($user_id, 'dealernumber', $dealernumber);
    update_user_meta($user_id, 'address', $address);
    update_user_meta($user_id, 'city', $city);
    update_user_meta($user_id, 'province', $province);
    update_user_meta($user_id, 'postalcode', $postalcode);
    update_user_meta($user_id, 'phone', $phone);
    update_user_meta($user_id, 'fax', $fax);

    wp_send_json_success(['message' => 'Profile updated successfully']);
}

add_action('wp_enqueue_scripts', function () {

    wp_enqueue_script(
        'security-js', 
        get_stylesheet_directory_uri() . '/assets/js/security.js',
        [],
        '1.0',
        true
    );

    wp_localize_script(
        'security-js', 
        'profile_ajax_obj',
        [
            'ajax_url' => admin_url('admin-ajax.php'),
        ]
    );

});

