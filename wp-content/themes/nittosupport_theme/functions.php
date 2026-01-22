<?php

function mycustomtheme_enqueue_scripts()
{
    wp_enqueue_style('main-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'mycustomtheme_enqueue_scripts');
require_once get_template_directory() . '/core/Config/theme-config.php';
require_once get_template_directory() . '/core/constants/theme-constants.php';
require_once get_template_directory() . '/core/functions/helpers.php';
add_theme_support('menus');
function mytheme_setup()
{
    add_theme_support('menus');
}
add_action('after_setup_theme', 'mytheme_setup');


//Templates
add_filter('theme_page_templates', function ($post_templates, $theme, $post, $post_type) {
    $template_dir = get_template_directory() . '/templates/pages/';

    if (is_dir($template_dir)) {
        $files = glob($template_dir . '*.php');
        foreach ($files as $file) {
            $basename = basename($file);
            $template_name = ucwords(str_replace(['-', '_', '.php'], [' ', ' ', ''], $basename));
            $post_templates['templates/pages/' . $basename] = $template_name;
        }
    }

    return $post_templates;
}, 10, 4);


add_filter('template_include', function ($template) {
    $page_template = get_page_template_slug();

    if ($page_template && strpos($page_template, 'templates/pages/') === 0) {
        $custom_template = get_template_directory() . '/' . $page_template;
        if (file_exists($custom_template)) {
            return $custom_template;
        }
    }

    return $template;
});

function nittosupport_register_menus()
{
    register_nav_menus([
        'header_menu' => __('Header Menu', 'nittosupport'),
    ]);
}
add_action('init', 'nittosupport_register_menus');

class Custom_Walker_Nav_Menu extends Walker_Nav_Menu
{
    function start_lvl(&$output, $depth = 0, $args = null)
    {
        // Replace 'sub-menu' with 'dropdown-menu' class
        $output .= '<ul class="dropdown-menu">';
    }
}

function custom_add_user_roles()
{
    add_role(
        'super_user',
        'Super User',
        get_role('administrator')->capabilities
    );

    add_role(
        'field_employee',
        'Field Employee',
        array(
            'read' => true,
        )
    );

    add_role(
        'inside_employee',
        'Inside Employee',
        array(
            'read' => true,
            'edit_posts' => true,
        )
    );

    add_role(
        'advanced_user',
        'Advanced User',
        array(
            'read' => true,
            'edit_posts' => true,
            'publish_posts' => true,
        )
    );

    add_role(
        'normal_user',
        'Normal User',
        array(
            'read' => true,
        )
    );
}
// add_action('init', 'custom_add_user_roles');


// Register User
/**
 * Handle custom front-end registration form submission
 */
add_action('admin_post_nopriv_custom_user_registration', 'handle_custom_user_registration');
add_action('admin_post_custom_user_registration', 'handle_custom_user_registration');

function handle_custom_user_registration()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        wp_die('Invalid request method.');
    }

    $firstname     = sanitize_text_field($_POST['firstname'] ?? '');
    $lastname      = sanitize_text_field($_POST['lastname'] ?? '');
    $email         = sanitize_email($_POST['email'] ?? '');
    $password      = sanitize_text_field($_POST['password'] ?? '');
    $confirm_pass  = sanitize_text_field($_POST['confirm_password'] ?? '');
    $companyname   = sanitize_text_field($_POST['companyname'] ?? '');
    $address       = sanitize_text_field($_POST['address'] ?? '');
    $city          = sanitize_text_field($_POST['city'] ?? '');
    $province      = sanitize_text_field($_POST['province'] ?? '');
    $postalcode    = sanitize_text_field($_POST['postalcode'] ?? '');
    $phone         = sanitize_text_field($_POST['phone'] ?? '');
    $fax           = sanitize_text_field($_POST['fax'] ?? '');
    $usergroup     = sanitize_text_field($_POST['Usergroup'] ?? '');
    $language      = sanitize_text_field($_POST['lngprefer'] ?? 'en');

    if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
        wp_die('Please fill all required fields.');
    }

    if (!is_email($email)) {
        wp_die('Invalid email address.');
    }

    if ($password !== $confirm_pass) {
        wp_die('Passwords do not match.');
    }

    if (email_exists($email)) {
        wp_die('This email is already registered.');
    }

    $userdata = array(
        'user_login' => $email,
        'user_pass'  => $password,
        'user_email' => $email,
        'first_name' => $firstname,
        'last_name'  => $lastname,
        'role'       => 'normal_user',
    );

    $user_id = wp_insert_user($userdata);

    if (is_wp_error($user_id)) {
        wp_die('User creation failed: ' . $user_id->get_error_message());
    }

    update_user_meta($user_id, 'companyname', $companyname);
    update_user_meta($user_id, 'address', $address);
    update_user_meta($user_id, 'city', $city);
    update_user_meta($user_id, 'province', $province);
    update_user_meta($user_id, 'postalcode', $postalcode);
    update_user_meta($user_id, 'phone', $phone);
    update_user_meta($user_id, 'fax', $fax);
    update_user_meta($user_id, 'usergroup', $usergroup);
    update_user_meta($user_id, 'language_preference', $language);

    wp_safe_redirect(home_url());
    exit;
}

/**
 * Handle custom user login form
 */
add_action('admin_post_nopriv_custom_user_login', 'handle_custom_user_login');
add_action('admin_post_custom_user_login', 'handle_custom_user_login');

function handle_custom_user_login()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        wp_die('Invalid request.');
    }

    $email    = sanitize_email($_POST['EmailAddress'] ?? '');
    $password = sanitize_text_field($_POST['Password'] ?? '');

    if (empty($email) || empty($password)) {
        wp_die('Please enter both email and password.');
    }

    $user = get_user_by('email', $email);

    if (!$user) {
        wp_die('Invalid email address.');
    }

    $creds = array(
        'user_login'    => $user->user_login,
        'user_password' => $password,
        'remember'      => true,
    );

    $login = wp_signon($creds, false);

    if (is_wp_error($login)) {
        wp_die('Login failed: ' . $login->get_error_message());
    }

    wp_set_current_user($login->ID);
    wp_set_auth_cookie($login->ID);

    wp_safe_redirect(home_url('/dashboard/'));
    exit;
}

function custom_login_redirect_logic()
{
    // Get current URL path
    $current_url = esc_url(home_url(add_query_arg(NULL, NULL)));
    $home_url    = home_url('/');
    $dashboard_url = home_url('/dashboard/');

    if (is_user_logged_in() && (is_front_page() || is_home())) {
        wp_redirect($dashboard_url);
        exit;
    }

    if (!is_user_logged_in() && is_page('dashboard')) {
        wp_redirect($home_url);
        exit;
    }
}
add_action('template_redirect', 'custom_login_redirect_logic');
