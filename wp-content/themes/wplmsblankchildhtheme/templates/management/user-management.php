<?php

/**
 * Template Name: User Management
 * Template Post Type: page
 */

get_header('header.php');

require_once get_stylesheet_directory() . '/header-inner.php';

$users = get_users();
?>

<section class="contassiner">

    <div class="InnerContent">
        <div class="row">

            <div class="col-md-12 panel-warning">
                <h2>User Management</h2>

                <p>&nbsp;</p>

                <div class="content-box-large box-with-header">
                    <div class="btngroup-al clearfix">
                        <a href="" class="btn-glu"><i class="fa fa-link" aria-hidden="true"></i> Export All</a>
                        <a href="" class="btn-glu"><i class="fa fa-link" aria-hidden="true"></i> Go to Group less User </a>
                        <a href="" class="btniu btnin">Add New</a>
                    </div>

                    <div class="table_div_area">
                        <div id="example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="dataTables_length" id="example_length">
                                        <label>Show 
                                        <select name="example_length" aria-controls="example" class="form-control input-sm">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select> entries</label>
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
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="First Name: activate to sort column ascending" style="width: 40px;">Name</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Email Address: activate to sort column ascending" style="width: 246px;">Email Address</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="City of Users: activate to sort column ascending" style="width: 45px;">City of Users</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Group: activate to sort column ascending" style="width: 148px;">Group</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Dealer No: activate to sort column ascending" style="width: 53px;">Dealer No</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="User Level: activate to sort column ascending" style="width: 46px;">User Level</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Reg Date: activate to sort column ascending" style="width: 57px;">Reg Date</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Last Login: activate to sort column ascending" style="width: 55px;">Last Login</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Action: activate to sort column ascending" style="width: 63px;">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php
                                            foreach($users as $user) {
                                                $first_name = get_user_meta($user->ID, 'first_name', true);
                                                $last_name = get_user_meta($user->ID, 'last_name', true);
                                                $email = $user->user_email;
                                                $city = get_user_meta($user->ID, 'city', true);
                                                $reg_date = $user->user_registered;
                                                $regformatted = wp_date('d M Y, h:i A', strtotime($reg_date));
                                            ?>
                                            <tr role="row" class="odd">
                                                <td><?php echo esc_html($first_name . ' ' . $last_name); ?></td>
                                                <td><?php echo esc_html($email); ?></td>
                                                <td><?php echo esc_html($city); ?></td>
                                                <td>Kal Tire</td>
                                                <td>0</td>
                                                <td>Normal User </td>
                                                <td><?php echo esc_html($regformatted); ?></td>
                                                <td><?php echo esc_html($regformatted); ?></td>
                                                <td>
                                                    <a href="" class="btn btn-default btn-sm">
                                                        <span class="glyphicon glyphicon-pencil"></span> Edit
                                                    </a>
                                                    <a href="" class="btn btn-default btn-sm" onclick="return confirm('Are you sure to delete this user ?');">
                                                        <span class="glyphicon glyphicon-trash"></span> Delete
                                                    </a>

                                                    <a href="" class="btn btn-default btn-sm" style="margin-top: 5px;" onclick="return confirm('Are you sure to inactive  this user ?');">
                                                        <span class="glyphicon glyphicon-ok"></span> Active
                                                    </a>
                                                </td>
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
