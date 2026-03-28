<?php

/**
 * Template Name: Management
 * Template Post Type: page
 */

$current_user_id = get_current_user_id();
get_header('header.php');

require_once get_stylesheet_directory() . '/header-inner.php';

$roles = $wp_roles->roles;
$users = get_users();
$export_data = export_data();
?>

<section class="management-section">
    <div class="container">

        <h2>Management</h2>

        <div class="management-grid">
            <?php
            
            $tools = [
                ['label' => 'User Management', 'icon' => 'fa-user', 'url'=> home_url('user-management'), 'cap'=> 'edit_users'],
                ['label' => 'Ad Material', 'icon' => 'fa-bullhorn', 'url'=> home_url('print-ads'), 'cap'=> 'edit_admaterials'],
                ['label' => 'Dealer Resources — Admin', 'icon' => 'fa-briefcase', 'cap'=> 'edit_dealer_resources'],
                ['label' => 'Courses — Admin', 'icon' => 'fa-book', 'cap'=> 'edit_featured_items'],
                ['label' => 'Pages — Admin', 'icon' => 'fa-file-alt', 'cap'=> 'edit_featured_items'],
                ['label' => 'Users — Admin', 'icon' => 'fa-user', 'cap'=> 'edit_users'],
                ['label' => 'Units — Admin', 'icon' => 'fa-columns', 'cap'=> 'edit_featured_items'],
                ['label' => 'Point Of Purchase — Admin', 'icon' => 'fa-shopping-cart', 'cap'=> 'edit_point_of_purchase'],
                ['label' => 'Promo Materials — Admin', 'icon' => 'fa-shopping-cart', 'cap'=> 'edit_featured_items'],
                // ['label' => 'Toyo Dollars — Admin', 'icon' => 'fa-wallet', 'cap'=> 'edit_featured_items'],

                // Add the rest here...
            ];

            foreach ($tools as $tool) : ?>
                <a href="<?php echo $tool['url'] ?>" class="admin-card <?php echo is_user_can_edit($current_user_id, $tool['cap']) ? '' : 'disabled-row'; ?> ">
                    <i class="fas <?php echo $tool['icon']; ?>"></i>
                    <span><?php echo $tool['label']; ?></span>
                </a>
            <?php endforeach; ?>

            
        </div>

        <p>The intention of this site is to provide easy access to Toyo Tires advertising material... [truncated]</p>
    </div>
</section>
<?php
get_footer('footer.php');
