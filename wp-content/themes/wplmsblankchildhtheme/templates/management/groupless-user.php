<?php

/**
 * Template Name: Groupless Users
 * Template Post Type: page
 */

get_header('header.php');

require_once get_stylesheet_directory() . '/header-inner.php';

$roles = $wp_roles->roles;
$users = get_users([
    'meta_query' => [
        'relation' => 'OR',
        [
            'key'     => 'usergroup',
            'value'   => '0',
            'compare' => '='
        ],
        [
            'key'     => 'usergroup',
            'value'   => '',
            'compare' => '='
        ],
        [
            'key'     => 'usergroup',
            'compare' => 'NOT EXISTS'
        ]
    ]
]);

$groups = get_all_user_groups();
?>

<section class="contassiner">

    <div class="InnerContent">
        <div class="row">

            <div class="col-md-12 panel-warning">
                <h2>Groupless Users</h2>

                <p>&nbsp;</p>

                <div class="content-box-large box-with-header">
                    <div class="btngroup-al clearfix">
                        <a href="" class="btn-glu"><i class="fa fa-link" aria-hidden="true"></i> Export All</a>
                        <a href="" class="btn-glu"><i class="fa fa-link" aria-hidden="true"></i> Go to Group less User </a>
                        <a href="<?php echo home_url('/add-user'); ?>" class="btniu btnin">Add New</a>
                    </div>

                    <div class="table_div_area">
                        <div id="example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                            <div class="row" style="padding-left: 15px; padding-right: 15px;">
                                <select id="roleFilter" class="form-control">
                                    <option value="">All Groups</option>
                                    <?php foreach ($groups as $group) : ?>
                                        <option value="<?php echo esc_attr($group->ID); ?>"><?php echo esc_html($group->Groupname); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="example" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" width="100%" role="grid" aria-describedby="example_info" style="width: 100%;">
                                        <thead>
                                            <tr role="row">
                                                <th>
                                                    <input type="checkbox" id="selectAllUsers">
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="First Name: activate to sort column ascending">Name</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Email Address: activate to sort column ascending">Email Address</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="City of Users: activate to sort column ascending">Company Name</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="User Level: activate to sort column ascending">User Level</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php
                                            $all_roles = wp_roles()->roles;
                                            foreach ($users as $user) {
                                                $first_name = get_user_meta($user->ID, 'first_name', true);
                                                $last_name = get_user_meta($user->ID, 'last_name', true);
                                                $email = $user->user_email;
                                                $company_name = get_user_meta($user->ID, 'companyname', true);
                                                $city = get_user_meta($user->ID, 'city', true);
                                                $role_slug = $user->roles[0] ?? '';
                                                $reg_date = $user->user_registered;
                                                $regformatted = wp_date('d M Y, h:i A', strtotime($reg_date));
                                                $can_edit = current_user_can_edit($role_slug);

                                            ?>
                                                <tr role="row" class="odd <?php echo !$can_edit ? 'disabled-row' : ''; ?>">
                                                    <td>
                                                        <input
                                                            type="checkbox"
                                                            class="user-checkbox"
                                                            name="user_ids[]"
                                                            value="<?php echo esc_attr($user->ID); ?>">
                                                    </td>
                                                    <td><?php echo esc_html($first_name . ' ' . $last_name); ?></td>
                                                    <td><?php echo esc_html($email); ?></td>
                                                    <td><?php echo esc_html($company_name); ?></td>
                                                    <td><?php echo esc_html($all_roles[$role_slug]['name'] ?? ''); ?></td>

                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="dataTables_info" id="example_info" role="status" aria-live="polite">Showing 1 to 10 of 163 entries</div>
                                </div>
                                <div class="col-sm-7">
                                    <div class="dataTables_paginate paging_simple_numbers" id="example_paginate">
                                        <ul class="pagination">
                                            <li class="paginate_button previous disabled" id="example_previous"><a href="#" aria-controls="example" data-dt-idx="0" tabindex="0">Previous</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php
get_footer('footer.php');
