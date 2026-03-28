<?php
global $wp_roles;

$global_priority_roles = [
    'um_super-user'   => 'Super User',
    'super_user'     => 'Super User',
    'administrator'   => 'Administrator',
    'field_employee'  => 'Field Employee',
    'inside_employee' => 'Inside Employee',
    'advanced_user'    => 'Advanced User',
    'normal_user'     => 'Normal User',
];


function global_roles($logedin_user_role){
    global $global_priority_roles;
    
    $role_permissions = [
        'um_super-user'   => [
            'um_super-user',
            'administrator' ,
            'field_employee' ,
            'inside_employee',
            'advanced_user',
            'normal_user',
        ],
        'super_user'     => [
            'super_user',
            'administrator' ,
            'field_employee' ,
            'inside_employee',
            'advanced_user',
            'normal_user',
        ],  
        'administrator'   => [
            'field_employee',
            'inside_employee',
            'advanced_user',
            'normal_user'
        ],
        'field_employee'  => [
            'advanced_user',
            'normal_user'
        ],
        'inside_employee' => [
            'advanced_user',
            'normal_user'
        ],
        'advanced_user'    => [],
        'normal_user'     => [],
    ];

    if (!isset($role_permissions[$logedin_user_role])) {
        return [];
    }
    return array_intersect_key($global_priority_roles, array_flip($role_permissions[$logedin_user_role]));
}

// add_action('init', function () {

//     $old_role = 'super_user';
//     $new_role = 'um_super-user';

//     // Get old role
//     $role = get_role($old_role);

//     if ($role && !get_role($new_role)) {

//         // 1️⃣ Create new role with same capabilities
//         add_role($new_role, 'UM Super User', $role->capabilities);

//         // 2️⃣ Get all users with old role
//         $users = get_users([
//             'role' => $old_role,
//             'fields' => 'all'
//         ]);

//         // 3️⃣ Assign new role to users
//         foreach ($users as $user) {
//             $user->remove_role($old_role);
//             $user->add_role($new_role);
//         }

//         // 4️⃣ Remove old role
//         remove_role($old_role);
//     }
// });

add_action('wp_ajax_update_user_role_ajax', 'update_user_role_ajax');

function update_user_role_ajax()
{

    check_ajax_referer('update_user_role_nonce');

    if (!current_user_can('edit_users')) {
        wp_send_json_error('Permission denied');
    }

    $user_id = intval($_POST['user_id']);
    $new_role = sanitize_text_field($_POST['role']);

    $user = new WP_User($user_id);

    if (!$user->exists()) {
        wp_send_json_error('User not found');
    }

    $user->set_role($new_role);

    wp_send_json_success('Role updated');
}

function current_user_can_edit($role_slug)
{
    $current_user = wp_get_current_user();
    $current_user_role = $current_user->roles[0] ?? '';

    $editable_roles = global_roles($current_user_role);

    $can_edit = array_key_exists($role_slug, $editable_roles);

    return $can_edit;
}

function get_user_group_details_by_user_id($user_id)
{
    global $wpdb;

    $group_id = get_user_meta($user_id, 'usergroup', true);

    $table_name = 'usergroup';

    $query = $wpdb->prepare(
        "SELECT * FROM {$table_name}
        WHERE id = %d",
        $group_id
    );

    return $wpdb->get_row($query);
}


function export_data()
{
    $users = get_users();

    $export_array = [];

    foreach ($users as $user) {
        $export_array[] = [
            'ID' => $user->ID,
            'Username' => $user->user_login,
            'Email' => $user->user_email,
            'First Name' => $user->first_name,
            'Last Name' => $user->last_name,
            'Role' => $user->roles[0] ?? '',
            'Address' => get_user_meta($user->ID, 'address', true),
            'City' => get_user_meta($user->ID, 'city', true),
            'State' => get_user_meta($user->ID, 'billing_state', true),
            'Zipcode' => get_user_meta($user->ID, 'postalcode', true),
            'Companyname' => get_user_meta($user->ID, 'companyname', true),
            'Dealernumber' => get_user_meta($user->ID, 'dealernumber', true),
            'phone' => get_user_meta($user->ID, 'phone', true),
            'usergroup' => get_user_meta($user->ID, 'usergroup', true),
            'language_preference' => get_user_meta($user->ID, 'language_preference', true),
            'dealer_access' => get_user_meta($user->ID, 'dealer_access', true),
            'esalespromo' => get_user_meta($user->ID, 'esalespromo', true),
            'etireinfo' => get_user_meta($user->ID, 'etireinfo', true),
            'esurveys' => get_user_meta($user->ID, 'esurveys', true),
            'wallet_balance' => get_user_meta($user->ID, 'wallet_balance', true),
        ];
    }
    return $export_array;
}

// Delete user
function enqueue_custom_scripts()
{
    wp_enqueue_script(
        'custom-js',
        get_template_directory_uri() . '/js/custom-script.js',
        [],
        null,
        true
    );

    wp_localize_script('custom-js', 'my_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('delete_user_nonce'),
    ]);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');

add_action('wp_ajax_delete_user_multisite', 'delete_user_multisite');
add_action('wp_ajax_nopriv_delete_user_multisite', 'delete_user_multisite'); // optional

function delete_user_multisite()
{

    check_ajax_referer('delete_user_nonce', 'nonce');

    if (!current_user_can('delete_users')) {
        wp_send_json_error('Permission denied');
    }

    $user_id = intval($_POST['user_id']);

    if (!$user_id) {
        wp_send_json_error('Invalid user ID');
    }

    if (is_multisite()) {
        require_once ABSPATH . 'wp-admin/includes/ms.php';
        wpmu_delete_user($user_id);
    } else {
        require_once ABSPATH . 'wp-admin/includes/user.php';
        wp_delete_user($user_id);
    }

    wp_send_json_success('User deleted');
}

function my_current_user_can($capability)
{
    $user = wp_get_current_user();

    if (!$user || empty($user->ID)) {
        return false;
    }

    // Check in allcaps
    if (!empty($user->allcaps[$capability])) {
        return true;
    }

    return false;
}