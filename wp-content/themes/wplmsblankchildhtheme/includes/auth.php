<?php
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

function handle_custom_user_login_old()
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

function handle_custom_user_login()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        wp_redirect(home_url('/login/?login=invalid_request'));
        exit;
    }

    $email    = sanitize_email($_POST['EmailAddress'] ?? '');
    $password = sanitize_text_field($_POST['Password'] ?? '');

    if (empty($email) || empty($password)) {
        wp_redirect(home_url('/login/?login=empty'));
        exit;
    }

    $user = get_user_by('email', $email);

    if (!$user) {
        // Redirect if email not found
        wp_redirect(home_url('/login/?login=email_not_found'));
        exit;
    }

    $creds = array(
        'user_login'    => $user->user_login,
        'user_password' => $password,
        'remember'      => true,
    );

    $login = wp_signon($creds, false);

    if (is_wp_error($login)) {
        // Redirect if password is wrong
        wp_redirect(home_url('/login/?login=failed'));
        exit;
    }

    // Login successful
    wp_set_current_user($login->ID);
    wp_set_auth_cookie($login->ID);

    wp_safe_redirect(home_url('/dashboard/'));
    exit;
}

// function custom_login_redirect_logic()
// {
//     // Get current URL path
//     $current_url = esc_url(home_url(add_query_arg(NULL, NULL)));
//     $home_url    = home_url('/');
//     $dashboard_url = home_url('/dashboard/');

//     if (is_user_logged_in() && (is_front_page() || is_home())) {
//         wp_redirect($dashboard_url);
//         exit;
//     }

//     if (!is_user_logged_in() && is_page('dashboard')) {
//         wp_redirect($home_url);
//         exit;
//     }
// }
// add_action('template_redirect', 'custom_login_redirect_logic');