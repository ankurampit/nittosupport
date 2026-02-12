<?php

if (!defined('VIBE_URL'))
    define('VIBE_URL', get_template_directory_uri());

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


// Manage Dashboard Menu
add_action('admin_menu', 'my_custom_menu');

function my_custom_menu()
{
    add_menu_page(
        'Manage Dashboard',
        'Manage Dashboad',
        'manage_options',
        'manage-dashboard',
        'user_permission_page_html',
        'dashicons-admin-generic',
        6
    );
}

function user_permission_page_html()
{
    $roles = [
        'advanced_user'   => 'Advance User',
        'field_employee'  => 'Field Employee',
        'inside_employee' => 'Inside Employee',
        'normal_user'     => 'Normal User',
        'super_user'      => 'Super User',
        'administrator'   => 'Administrator'
    ];

    $permissions = [
        'training_site'         => 'Training Site',
        'advertising_materials' => 'Advertising Materials',
        'dealer_resources'      => 'Dealer Resources',
        'management'            => 'Management',
        'coop_submission'       => 'Coop Submission',
        'promo_materials'       => 'Promo Materials',
        'point_of_purchase'     => 'Point of Purchase'
    ];

    $saved_matrix = get_option('user_permission_matrix', []);
?>
    <div class="wrap">
        <h1>User Permissions</h1>

        <form method="post">
            <?php wp_nonce_field('save_permission_matrix'); ?>

            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>User Level</th>
                        <?php foreach ($permissions as $key => $label): ?>
                            <th><?php echo $label; ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($roles as $slug => $label): ?>
                        <tr>
                            <td><strong><?php echo $label; ?></strong></td>

                            <?php foreach ($permissions as $perm_key => $perm_label):
                                $checked = isset($saved_matrix[$slug][$perm_key]) && $saved_matrix[$slug][$perm_key] == 1 ? 'checked' : '';
                            ?>
                                <td>
                                    <input type="checkbox"
                                        name="matrix[<?php echo $slug; ?>][<?php echo $perm_key; ?>]"
                                        value="1"
                                        <?php echo $checked; ?>>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <button type="submit" name="save_permissions" class="button button-primary" style="margin-top:20px;">
                Save Permissions
            </button>
        </form>
    </div>

    <?php
    if (isset($_POST['save_permissions'])) {

        if (!wp_verify_nonce($_POST['_wpnonce'], 'save_permission_matrix')) {
            die("Security check failed");
        }

        $matrix = $_POST['matrix'] ?? [];

        foreach ($roles as $slug => $label) {
            foreach ($permissions as $perm_key => $label2) {
                if (!isset($matrix[$slug][$perm_key])) {
                    $matrix[$slug][$perm_key] = 0;
                }
            }
        }

        update_option('user_permission_matrix', $matrix);
        echo "<div class='updated notice'><p>Permissions saved successfully!</p></div>";
        echo "<script>location.reload();</script>";
    }
}

function can_user_access($role_slug, $permissions, $permission_key)
{
    return isset($permissions[$role_slug][$permission_key])
        && $permissions[$role_slug][$permission_key] == 1;
}



// Register "Add Materials" Post Type

function add_materials_post_type()
{

    $labels = array(
        'name'               => 'Materials',
        'singular_name'      => 'Material',
        'menu_name'          => 'Materials',
        'name_admin_bar'     => 'Material',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Material',
        'edit_item'          => 'Edit Material',
        'new_item'           => 'New Material',
        'view_item'          => 'View Material',
        'all_items'          => 'All Materials',
        'search_items'       => 'Search Materials',
        'not_found'          => 'No materials found',
        'not_found_in_trash' => 'No materials found in Trash'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'menu_icon'          => 'dashicons-media-document',
        'supports'           => array('title', 'editor', 'thumbnail'),
        'has_archive'        => true,
        'rewrite'            => array('slug' => 'materials'),
        'show_in_rest'       => true,   // Enables Gutenberg + API
    );

    register_post_type('materials', $args);
}
add_action('init', 'add_materials_post_type');


// material category in ACF

function materials_category_taxonomy()
{

    $labels = array(
        'name'              => 'Material Categories',
        'singular_name'     => 'Material Category',
        'search_items'      => 'Search Categories',
        'all_items'         => 'All Categories',
        'parent_item'       => 'Parent Category',
        'parent_item_colon' => 'Parent Category:',
        'edit_item'         => 'Edit Category',
        'update_item'       => 'Update Category',
        'add_new_item'      => 'Add New Category',
        'new_item_name'     => 'New Category Name',
        'menu_name'         => 'Material Categories',
    );

    $args = array(
        'hierarchical'      => true,   // works like categories
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'material-category'),
        'show_in_rest'      => true,  // enable for Gutenberg
    );

    register_taxonomy('material_category', array('materials'), $args);
}
add_action('init', 'materials_category_taxonomy');



add_action('admin_footer', 'single_category_selection_for_materials');
function single_category_selection_for_materials()
{
    $screen = get_current_screen();
    if ($screen->post_type == 'materials') {
    ?>
        <script>
            jQuery(function($) {
                $('.categorychecklist input').on('click', function() {
                    $('.categorychecklist input').not(this).prop('checked', false);
                });
            });
        </script>
<?php
    }
}


function advertising_tabs_shortcode()
{
    ob_start();
    include get_stylesheet_directory() . '/templates/materials.php';
    return ob_get_clean();
}
add_shortcode('advertising_tabs', 'advertising_tabs_shortcode');


function acf_form_print_ads_shortcode()
{
    ob_start();
    include get_stylesheet_directory() . '/templates/form-print-ads.php';
    return ob_get_clean();
}
add_shortcode('print_ads_form', 'acf_form_print_ads_shortcode');
