<?php

/**
 * Template Name: User Management
 * Template Post Type: page
 */

get_header('header.php');

require_once get_stylesheet_directory() . '/header-inner.php';

$roles = $wp_roles->roles;
$users = get_users();
$export_data = export_data();
?>

<section class="contassiner">

    <div class="InnerContent">
        <div class="row">

            <div class="col-md-12 panel-warning">
                <h2>User Management</h2>

                <p>&nbsp;</p>

                <div class="content-box-large box-with-header">
                    <div class="btngroup-al clearfix mng-btn-grp">
                        <a href="javaScript:void(0)" id="exportTable" class="btn-glu" onclick='exportUser(<?php echo json_encode($export_data, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)'>
                            <i class="fa fa-link"></i> Export All
                        </a>
                        <a href="" class="mng-button"><i class="fa fa-link" aria-hidden="true"></i> Go to Group less User </a>
                        <a href="<?php echo home_url('/add-user'); ?>" class="mng-button">Add New</a>
                    </div>

                    <div class="table_div_area">
                        <div id="example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="dataTables_length" id="example_length">
                                        <label>Show
                                            <select id="table-length" class="form-control input-sm">
                                                <option value="1">1</option>
                                                <option value="3">3</option>
                                                <option value="5">5</option>
                                                <option value="10" selected>10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select>
                                            entries
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div id="example_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control input-sm" placeholder="" aria-controls="example"></label></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="example" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" width="100%" role="grid" aria-describedby="example_info" style="width: 100%;">
                                        <thead>
                                            <tr role="row">
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="First Name: activate to sort column ascending">Name</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Email Address: activate to sort column ascending">Email Address</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="City of Users: activate to sort column ascending">City of Users</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Group: activate to sort column ascending">Group</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Dealer No: activate to sort column ascending">Dealer No</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="User Level: activate to sort column ascending">User Level</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="User Level: activate to sort column ascending">Change Role</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="User Level: activate to sort column ascending">Coop Access</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Reg Date: activate to sort column ascending">Reg Date</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Last Login: activate to sort column ascending">Last Login</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Action: activate to sort column ascending">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php
                                            $all_roles = wp_roles()->roles;
                                            foreach ($users as $user) {
                                                $first_name = get_user_meta($user->ID, 'first_name', true);
                                                $last_name = get_user_meta($user->ID, 'last_name', true);
                                                $email = $user->user_email;
                                                $city = get_user_meta($user->ID, 'city', true);
                                                $role_slug = $user->roles[0] ?? '';
                                                $reg_date = $user->user_registered;
                                                $regformatted = wp_date('d M Y, h:i A', strtotime($reg_date));
                                                $can_edit = current_user_can_edit($role_slug);
                                                $user_group_details_by_user_id = get_user_group_details_by_user_id($user->ID);
                                                $dealernumber = get_user_meta($user->ID, 'dealernumber', true);
                                                $user_coop_access = get_user_meta($user->ID, 'accesstocopsubmsion', true);

                                            ?>
                                                <tr id="user-row-<?php echo $user->ID; ?>" role="row" class="odd <?php echo !$can_edit ? 'disabled-row' : ''; ?>">
                                                    <td><?php echo esc_html($first_name . ' ' . $last_name); ?></td>
                                                    <td><?php echo esc_html($email); ?></td>
                                                    <td><?php echo esc_html($city); ?></td>
                                                    <td><?php echo $user_group_details_by_user_id->Groupname ?></td>
                                                    <td><?php echo $dealernumber ?></td>
                                                    <td><?php echo esc_html($all_roles[$role_slug]['name'] ?? ''); ?></td>
                                                    <td style=" align-items: center; gap: 10px; vertical-align: middle; border:none;" class="role-action-column">
                                                        <div style="display: flex; align-items: center; gap: 10px;">
                                                            <select class="change-user-role"
                                                                data-userid="<?php echo esc_attr($user->ID); ?>">
                                                                <?php
                                                                $exclude_roles = ['editor', 'subscriber', 'author', 'contributor', 'shop_manager', 'student', 'instructor', 'customer'];

                                                                foreach ($roles as $role_key => $role_info) :
                                                                    if (in_array($role_key, $exclude_roles)) continue;
                                                                ?>
                                                                    <option value="<?php echo esc_attr($role_key); ?>"
                                                                        <?php selected($user->roles[0] ?? '', $role_key); ?>>
                                                                        <?php echo esc_html($role_info['name']); ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>

                                                            <button type="button"
                                                                class="save-role btn btn-primary btn-sm"
                                                                data-userid="<?php echo esc_attr($user->ID); ?>">
                                                                Save
                                                            </button>
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <label class="switch">

                                                            <input type="checkbox" name="coop_access" value="1"
                                                                <?php checked($user_coop_access ?? '', 1); ?>>
                                                            <span class="slider"></span>
                                                        </label>
                                                    </td>

                                                    <td><?php echo esc_html($regformatted); ?></td>
                                                    <td><?php echo esc_html($regformatted); ?></td>
                                                    <td class="management-action-button">
                                                        <a href="<?php echo home_url('/edit-user/?user_id=' . $user->ID); ?>" class="btn btn-primary">
                                                            <i class='fa fa-edit' style="color: white"></i> &nbsp; Edit &nbsp;
                                                        </a>
                                                        <a href="#" class="btn btn-primary delete-user-btn" data-user-id="<?php echo $user->ID; ?>" onclick="openDeleteModal(<?php echo $user->ID; ?>)">
                                                            <i class='fas fa-trash-alt' style="color: white"></i> Delete
                                                        </a>

                                                        <a href="" class="btn btn-primary" style="margin-top: 5px;" onclick="return confirm('Are you sure to inactive  this user ?');">
                                                            <i class="fa fa-toggle-on" aria-hidden="true"></i> Active
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <!-- User Delete Popup -->
                                    <div id="delete-modal" class="delete-modal">
                                        <div class="delete-modal-content">
                                            <div class="delete-icon">
                                                <i class="fa fa-trash"></i>
                                            </div>

                                            <h3>Delete User?</h3>
                                            <p>This action cannot be undone.</p>

                                            <!-- Loader -->
                                            <div id="delete-loader" style="display:none; text-align:center; margin:10px 0;">
                                                <span class="spinner"></span>
                                                <p>Deleting...</p>
                                            </div>

                                            <div class="delete-actions">
                                                <button
                                                    id="cancel-delete"
                                                    class="btn-secondary"
                                                    onclick="closeDeleteModal()">
                                                    Cancel
                                                </button>

                                                <button
                                                    id="confirm-delete-btn"
                                                    class="btn-danger"
                                                    onclick="confirmDeleteUser()">
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="dataTables_info" id="example_info" role="status" aria-live="polite">Showing 1 to 10 of 163 entries</div>
                                </div>
                                <!-- <div class="col-sm-7">
                                    <div class="dataTables_paginate paging_simple_numbers" id="example_paginate">
                                        <ul class="pagination">
                                            <li class="paginate_button previous disabled" id="example_previous"><a href="#" aria-controls="example" data-dt-idx="0" tabindex="0">Previous</a></li>
                                        </ul>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        document.querySelectorAll(".save-role").forEach(function(button) {

            button.addEventListener("click", function() {

                const userID = this.dataset.userid;
                const select = document.querySelector('.change-user-role[data-userid="' + userID + '"]');
                const role = select.value;

                const formData = new FormData();
                formData.append("action", "update_user_role_ajax");
                formData.append("user_id", userID);
                formData.append("role", role);
                formData.append("_ajax_nonce", "<?php echo wp_create_nonce('update_user_role_nonce'); ?>");

                fetch("<?php echo admin_url('admin-ajax.php'); ?>", {
                        method: "POST",
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Role updated successfully");
                        } else {
                            alert(data.data);
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                    });

            });

        });

    });
</script>

<?php
get_footer('footer.php');
