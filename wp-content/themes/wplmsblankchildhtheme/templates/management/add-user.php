<?php

/**
 * Template Name: Add User
 * Template Post Type: page
 */

get_header('header.php');

require_once get_stylesheet_directory() . '/header-inner.php';

$roles = $wp_roles->roles;

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
$user = get_user_by('ID', $user_id);
$groups = get_all_user_groups();
?>

<section class="contassiner">

    <div class="InnerContent">
        <div class="row">

            <div class="col-md-12 panel-warning">
                <h2>Add New User</h2>

                <p>&nbsp;</p>

                <div class="content-box-large box-with-header">
                    <!-- <div class="btngroup-al clearfix">
                        <a href="" class="btn-glu"><i class="fa fa-link" aria-hidden="true"></i> Export All</a>
                        <a href="" class="btn-glu"><i class="fa fa-link" aria-hidden="true"></i> Go to Group less User </a>
                        <a href="" class="btniu btnin">Add New</a>
                    </div> -->

                    <div class="table_div_area">
                        <form class="regdForm"
                            action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
                            method="post"
                            enctype="multipart/form-data"
                            id="frmCms"
                            name="frmCms"
                            novalidate="novalidate">

                            <input type="hidden" name="action" value="custom_user_registration">

                            <div class="regdFormBase">
                                <div class="input-group">
                                    <label class="redgLabel">First Name</label><span class="errStar">*</span>
                                    <input type="text" name="firstname" id="firstname" tabindex="1" class="form-control" placeholder="" value="">
                                </div>
                                <div class="input-group">
                                    <label class="redgLabel">Last Name</label><span class="errStar">*</span>
                                    <input type="text" name="lastname" id="lastname" tabindex="1" class="form-control" placeholder="" value="">
                                </div>
                            </div>
                            <div class="regdFormBase">
                                <div class="input-group">
                                    <label class="redgLabel">Password</label><span class="errStar">*</span>
                                    <input id="password" type="password" name="password" class="form-control" placeholder="">
                                </div>
                                <div class="input-group">
                                    <label class="redgLabel">Confirm Password</label><span class="errStar">*</span>
                                    <input id="confirm_password" type="password" name="confirm_password" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="regdFormBase">
                                <div class="input-group">
                                    <label class="redgLabel">Email</label><span class="errStar">*</span>
                                    <input type="email" name="email" id="email" tabindex="1" class="form-control" placeholder="" value="">

                                </div>
                                <div class="input-group">
                                    <label class="redgLabel">Company Name</label><span class="errStar">*</span>
                                    <input type="text" name="companyname" id="companyname" tabindex="1" class="form-control" placeholder="" value="">
                                </div>
                            </div>
                            <div class="regdFormBase">
                                <div class="input-group">
                                    <label class="redgLabel">Dealer Number</label>
                                    <input type="text" name="dealernumber" id="dealernumber" tabindex="1" class="form-control" placeholder="" value="">
                                </div>
                                <div class="input-group">
                                    <label class="redgLabel">Address</label><span class="errStar">*</span>
                                    <input type="text" name="address" id="address" tabindex="1" class="form-control" placeholder="" value="">
                                </div>
                            </div>
                            <div class="regdFormBase">
                                <div class="input-group">
                                    <label class="redgLabel">City</label><span class="errStar">*</span>
                                    <input type="text" name="city" id="city" tabindex="1" class="form-control" placeholder="" value="">
                                </div>
                                <div class="input-group">
                                    <label class="redgLabel">Province</label><span class="errStar">*</span>
                                    <select class="form-control" name="province" id="province">
                                        <option value="">Select Province</option>
                                        <option value="AB">Alberta</option>
                                        <option value="BC">British Columbia</option>
                                        <option value="MB">Manitoba</option>
                                        <option value="NB">New Brunswick</option>
                                        <option value="NL">Newfoundland and Labrador</option>
                                        <option value="NT">Northwest Territories</option>
                                        <option value="NS">Nova Scotia</option>
                                        <option value="NU">Nunavut</option>
                                        <option value="ON">Ontario</option>
                                        <option value="PE">Prince Edward Island</option>
                                        <option value="QC">Quebec</option>
                                        <option value="SK">Saskatchewan</option>
                                        <option value="YT">Yukon</option>

                                    </select>
                                </div>
                            </div>
                            <div class="regdFormBase">
                                <div class="input-group">
                                    <label class="redgLabel">Postal Code</label><span class="errStar">*</span>
                                    <input type="text" name="postalcode" id="postalcode" tabindex="1" class="form-control" placeholder="" value="">
                                </div>
                                <div class="input-group">
                                    <label class="redgLabel">Phone</label><span class="errStar">*</span>
                                    <input type="text" name="phone" id="phone" tabindex="1" class="form-control" placeholder="" value="">
                                </div>
                            </div>
                            <div class="regdFormBase">
                                <div class="input-group">
                                    <label class="redgLabel required">Fax</label>
                                    <input type="text" name="fax" id="fax" tabindex="1" class="form-control" placeholder="" value="">
                                </div>
                                <div class="input-group">
                                    <label class="redgLabel">Nitto rep</label>
                                    <select class="form-control" name="NittoRepid" id="NittoRepid">
                                        <option value="">Select Nitto rep</option>
                                        <option value="132">JP Marion</option>
                                        <option value="327">Cliff Culbert</option>
                                        <option value="570">James Richardson</option>
                                        <option value="633">Steve Gilbert</option>
                                        <option value="636">Tim O'shaughnessy</option>
                                        <option value="654">Doug Martin</option>
                                        <option value="127663">Jean-Patrick Flynn</option>
                                        <option value="127893">Dustin Hora</option>
                                        <option value="127907">Marc-André Charette</option>
                                        <option value="127913">Nicolas Tremblay-Bujold</option>
                                        <option value="127956">Caroline Brousseau</option>
                                    </select>
                                </div>
                            </div>

                            <div class="regdFormBase2">
                                <div class="input-group">
                                    <p>Language Preference</p>
                                    <select class="form-control" name="lngprefer" id="lngprefer">
                                        <option value="en">English</option>
                                        <option value="fr">Français</option>
                                    </select>
                                </div>
                            </div>

                            <div class="regdFormBase2">
                                <div class="input-group">
                                    <p>Group</p>
                                    <select class="form-control" name="Usergroup" id="Usergroup">
                                        <option value="">Group</option>
                                        <?php foreach ($groups as $group) : ?>
                                            <option value="<?php echo esc_attr($group->ID); ?>"><?php echo esc_html($group->Groupname); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="regdFormBase2">
                                <div class="input-group">
                                    <p>Do you require ADVANCED support site access? Note must be approved by your Nitto Tire Representative.</p>
                                    <select class="form-control" name="delaraccess" id="delaraccess">
                                        <option value="0">No I don't need advanced access</option>
                                        <option value="1">Yes, please give me access</option>
                                    </select>
                                </div>
                            </div>
                            <div class="regBordr"></div>
                            <div class="regdBtm">
                                <div class="regdFormBase2">
                                    <div class="input-group">
                                        <h5>Would you wish to receive information on:</h5>
                                        <p>This would include upcoming sales events material, new advertising initiatives, promotional items such as clothing, point of purchase displays, coop changes or updates, and how to participate.</p>
                                    </div>
                                </div>
                                <div class="regdFormBase2">
                                    <div class="input-group">
                                        <p>Advertising and Sales Promotion<span class="errStar">*</span></p>
                                        <label class="checkbox-inline">
                                            <input type="radio" name="Esalespromo" id="Esalespromo1" value="1" checked=""> <span>Yes</span>
                                        </label>
                                        <label class="checkbox-inline">
                                            <input type="radio" name="Esalespromo" id="Esalespromo2" value="0"> <span>No</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="regdFormBase2">
                                    <div class="input-group">
                                        <p>New product launches, increased size options, pricing changes, etc.</p>
                                    </div>
                                </div>
                                <div class="regdFormBase2">
                                    <div class="input-group">
                                        <p>New product information<span class="errStar">*</span></p>
                                        <label class="checkbox-inline">
                                            <input type="radio" name="Etireinfo" id="Etireinfo1" value="1" checked=""> <span>Yes</span>
                                        </label>
                                        <label class="checkbox-inline">
                                            <input type="radio" name="Etireinfo" id="Etireinfo2" value="0"> <span>No</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="regdFormBase2">
                                    <div class="input-group">
                                        <p>Sometimes we like to get your opinion on how we are doing. If you would like to participate please let us know. We promise we won’t be sending you too many emails and you’ll always have the option to opt out at any time.</p>
                                    </div>
                                </div>
                                <div class="regdFormBase2">
                                    <div class="input-group">
                                        <p>Accept Surveys<span class="errStar">*</span></p>
                                        <label class="checkbox-inline">
                                            <input type="radio" name="Esurveys" id="Esurveys1" value="1" checked=""> <span>Yes</span>
                                        </label>
                                        <label class="checkbox-inline">
                                            <input type="radio" name="Esurveys" id="Esurveys2" value="0"> <span>No</span>
                                        </label>
                                    </div>
                                </div>
                                <button type="submit" class="mng-button">Register</button>
                        </form>
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
