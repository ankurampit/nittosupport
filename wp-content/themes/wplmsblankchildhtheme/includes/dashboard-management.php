<?php
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
        _e('Permissions saved successfully!', TEXT_DOMAIN);
        echo "<div class='updated notice'><p>Permissions saved successfully!</p></div>";
        echo "<script>location.reload();</script>";
    }
}

function can_user_access($role_slug, $permissions, $permission_key)
{
    return isset($permissions[$role_slug][$permission_key])
        && $permissions[$role_slug][$permission_key] == 1;
}