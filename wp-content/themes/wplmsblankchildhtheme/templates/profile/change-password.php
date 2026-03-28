<?php

/**
 * Template Name: Change Password
 * Template Post Type: page
 */

get_header('header.php');
$user_id = get_current_user_id();
$last_pass_change = get_user_meta($user_id, 'password_last_changed', true);
?>
<div class="account-dashboard">
    <aside class="dashboard-sidebar">
        <div class="sidebar-logo">
            <i class="fas fa-user-shield"></i>
            <span>My Account</span>
        </div>
        <nav class="sidebar-menu">
            <a href="#"
                class="menu-item sidebar-menu-item active"
                onclick="changeMenuOption('update-profile', this)">
                <i class="fas fa-user-circle"></i> Profile
            </a>

            <a href="#"
                class="menu-item sidebar-menu-item"
                onclick="changeMenuOption('change-password', this)">
                <i class="fas fa-key"></i> Security & Password
            </a>
            <!-- <a href="#" class="menu-item sidebar-menu-item"><i class="fas fa-bell"></i> Toyo Dollar</a>
            <a href="#" class="menu-item sidebar-menu-item"><i class="fas fa-credit-card"></i> Wallet transaction</a> -->
            <div class="menu-divider"></div>
            <a href="#" class="menu-item logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </aside>

    <main class="dashboard-content" id="change-password" style="display: none;">
        <div class="content-header">
            <div class="header-text">
                <h1>Security Settings</h1>
                <p>Manage your password and account recovery options to stay protected.</p>
            </div>
            <div class="last-updated">Last changed: <?php echo human_time_diff(strtotime($last_pass_change), current_time('timestamp')) . ' ago'; ?></div>
        </div>

        <section class="form-wrapper">
            <form action="" method="POST" class="password-form-wide" id="password_form">
                <div class="field-grid">
                    <div id="pass-check-msg"></div>
                    <div class="field-group full">
                        <label>Current Password</label>
                        <input type="password" name="current_pass" id="current_pass" placeholder="••••••••" required>
                    </div>

                    <div class="field-group">
                        <label>New Password</label>
                        <input type="password" name="new_pass" id="new_pass" placeholder="Enter new password" required disabled>
                        <div class="strength-indicator">
                            <div class="bar" id="strength_bar"></div>
                        </div>

                        <small id="strength_text"></small>
                    </div>

                    <div class="field-group">
                        <label>Confirm New Password</label>
                        <input type="password" name="confirm_pass" id="confirm_pass" placeholder="Repeat new password" required disabled>
                        <small id="confirm_msg"></small>
                    </div>

                </div>

                <div class="form-actions-wide">
                    <button type="submit" class="btn-save">Update Password</button>
                    <!-- <button type="button" class="btn-cancel">Discard Changes</button> -->
                </div>
            </form>
        </section>
    </main>

    <main class="dashboard-content" id="update-profile" style="display: block;">
        <div class="content-header">
            <div class="header-text">
                <h1>Profile Settings</h1>
                <p>Update your personal information and account details.</p>
            </div>
            <div class="last-updated">
                Last updated: <?php echo human_time_diff(strtotime($profile_updated), current_time('timestamp')) . ' ago'; ?>
            </div>
        </div>

        <section class="form-wrapper">
            <div id="profile-msg"></div>
            <form method="POST" class="password-form-wide" id="profile_form">

                <?php wp_nonce_field('custom_user_update_action', 'custom_user_update_nonce'); ?>
                <input type="hidden" name="user_id" value="<?php echo esc_attr($current_user->ID); ?>">

                <div class="field-grid">

                    <div class="field-group">
                        <label>First Name</label>
                        <input type="text" name="firstname" value="<?php echo esc_attr($current_user->first_name); ?>">
                    </div>

                    <div class="field-group">
                        <label>Last Name</label>
                        <input type="text" name="lastname" value="<?php echo esc_attr($current_user->last_name); ?>">
                    </div>

                    <div class="field-group full">
                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo esc_attr($current_user->user_email); ?>" >
                    </div>

                    <div class="field-group">
                        <label>Company Name</label>
                        <input type="text" name="companyname" value="<?php echo esc_attr(get_user_meta($current_user->ID, 'companyname', true)); ?>">
                    </div>

                    <div class="field-group">
                        <label>Dealer Number</label>
                        <input type="text" name="dealernumber" value="<?php echo esc_attr(get_user_meta($current_user->ID, 'dealernumber', true)); ?>">
                    </div>

                    <div class="field-group">
                        <label>Address</label>
                        <input type="text" name="address" value="<?php echo esc_attr(get_user_meta($current_user->ID, 'address', true)); ?>">
                    </div>

                    <div class="field-group">
                        <label>City</label>
                        <input type="text" name="city" value="<?php echo esc_attr(get_user_meta($current_user->ID, 'city', true)); ?>">
                    </div>

                    <div class="field-group">
                        <label>Province</label>
                        <input type="text" name="province" value="<?php echo esc_attr(get_user_meta($current_user->ID, 'province', true)); ?>">
                    </div>

                    <div class="field-group">
                        <label>Postal Code</label>
                        <input type="text" name="postalcode" value="<?php echo esc_attr(get_user_meta($current_user->ID, 'postalcode', true)); ?>">
                    </div>

                    <div class="field-group full">
                        <label>Phone</label>
                        <input type="text" name="phone" value="<?php echo esc_attr(get_user_meta($current_user->ID, 'phone', true)); ?>">
                    </div>

                    <div class="field-group full">
                        <label>Fax</label>
                        <input type="text" name="fax" value="<?php echo esc_attr(get_user_meta($current_user->ID, 'fax', true)); ?>">
                    </div>

                </div>
                <div class="form-loader" hidden>
                    <div class="loader-spinner"></div>
                </div>
                <div class="form-actions-wide">
                    <button type="submit" class="btn-save">Update Profile</button>
                    <!-- <button type="button" class="btn-cancel">Discard Changes</button> -->
                </div>

            </form>
        </section>
    </main>


</div>
<?php
get_footer('footer.php');
