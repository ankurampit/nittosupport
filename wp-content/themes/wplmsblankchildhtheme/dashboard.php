<?php

/** 
 * Template Name: Dashboard 
 * Template Post Type: page 
 */

get_header('header.php');

$current_user = wp_get_current_user();
$user_role = $current_user->roles[0] ?? '';

$permissions = get_option('user_permission_matrix', []);

if (!isset($permissions[$user_role])) {
    $permissions[$user_role] = [];
}
?>

<!-- Content start -->
<section class="container">

    <div class="InnerContent cnngDesign">
        <div class="row">
            <div class="col-md-12">

            </div>
            <div class="mobileFeaturedView">
                <div class="col-md-3">
                    <div class="featuredBox">
                        <h2>Featured</h2>

                        <ul>
                            <li>
                                <a href="javascript:{}"
                                    onclick="document.getElementById(&#39;my_form_promo_frtd&#39;).submit(); return false;"><img
                                        src="./Welcome to Nitto Support Site_files/mug5301_-_black.jpg" alt=""></a>
                                <h5><a href="javascript:{}"
                                        onclick="document.getElementById(&#39;my_form_promo_frtd&#39;).submit(); return false;">The
                                        Plymouth 16 oz. Mug</a></h5>
                                <p>The Plymouth 16 Oz. Mug with Nitto Ti..</p>
                                <a href="javascript:{}"
                                    onclick="document.getElementById(&#39;my_form_promo_frtd&#39;).submit(); return false;"
                                    class="fdetail">Details</a>
                            </li>
                        </ul>
                    </div>



                    <form method="POST" action="https://promo.nittosupport.ca/webservices/login.php"
                        accept-charset="UTF-8" id="my_form_promo_frtd">

                        <input name="username" type="hidden" value="provat.das@brainiuminfotech.com">
                        <input name="password" type="hidden" value="l94O7%5MDt@Ie0QE92QHWa2n2">
                        <input name="Language" type="hidden" value="en">
                        <input name="url" type="hidden"
                            value="https://promo.nittosupport.ca/english/the-plymouth-16-oz-mug.html?SID=pg4tmafdp8fqkt86bjumu4q622">
                        <input name="productId" type="hidden" value="6">
                        <input name="Level" type="hidden" value="4">
                    </form>

                    <form method="POST" action="https://pos.nittosupport.ca/webservices/login.php"
                        accept-charset="UTF-8" id="my_form_pos_frtd">

                        <input name="username" type="hidden" value="provat.das@brainiuminfotech.com">
                        <input name="password" type="hidden" value="l94O7%5MDt@Ie0QE92QHWa2n2">
                        <input name="Language" type="hidden" value="en">
                        <input name="url" type="hidden"
                            value="https://pos.nittosupport.ca/english/nitto-poser-frame.html?SID=b7v1pfrc2jrvtt7s3gvm4un7s6">
                        <input name="productId" type="hidden" value="33">
                        <input name="Level" type="hidden" value="4">
                    </form>
                    <div class="contentFoot">
                        <div class="row">

                            <div class="col-md-5 curreventNew">
                                <div class="currentEvnt">

                                    <h3>Current Events</h3>
                                    <a href="https://nittosupport.ca/currentevent/20"><img
                                            src="./Welcome to Nitto Support Site_files/1739308520_Nitto_Spring_AD_2025_EN.jpg"
                                            alt=""></a>

                                    <p class="evntTxt">Spring 2025 Ad material</p>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-5 advLeft">
                    <h2>Welcome <span>Provat</span> To the Nitto Support Site</h2>

                    <ul class="dash-thumb-blg clearfix">

                        <?php if (can_user_access($user_role, $permissions, 'advertising_materials')) : ?>
                            <li>
                                <div class="dash-items"
                                    style="background-image: url('https://nittosupport.ca/assets/uploads/dashboardmenustyle_images/1563815722_Nitto_Adv_material.jpg');">
                                    <a href="<?php echo home_url('print-ads/') ?>">
                                        <h5>Advertising Material</h5>
                                        <p>Download ads, logos and tire pictures.</p>
                                    </a>
                                    <a href="<?php echo home_url('print-ads/') ?>" class="getstart">More info</a>
                                </div>
                            </li>
                        <?php endif; ?>


                        <?php if (can_user_access($user_role, $permissions, 'dealer_resources')) : ?>
                            <li>
                                <div class="dash-items"
                                    style="background-image: url('https://nittosupport.ca/assets/uploads/dashboardmenustyle_images/1547593506_Dealer_resources.jpg');">
                                    <a href="https://nittosupport.ca/dealerresource/index">
                                        <h5>Dealer Resources</h5>
                                        <p>Download catalogues, general information.</p>
                                    </a>
                                    <a href="https://nittosupport.ca/dealerresource/index" class="getstart">More info</a>
                                </div>
                            </li>
                        <?php endif; ?>


                        <?php if (can_user_access($user_role, $permissions, 'management')) : ?>
                            <li>
                                <div class="dash-items"
                                    style="background-image: url('https://nittosupport.ca/assets/uploads/dashboardmenustyle_images/1547594567_Management_icon2.jpg');">
                                    <a href="https://nittosupport.ca/management/index">
                                        <h5>Management</h5>
                                        <p>Super user management tools</p>
                                    </a>
                                    <a href="https://nittosupport.ca/management/index" class="getstart">Get started</a>
                                </div>
                            </li>
                        <?php endif; ?>


                        <?php if (can_user_access($user_role, $permissions, 'point_of_purchase')) : ?>
                            <li>
                                <div class="dash-items"
                                    style="background-image: url('https://nittosupport.ca/assets/uploads/dashboardmenustyle_images/1563815367_Nitto_POP.jpg');">
                                    <a href="javascript:{}" onclick="document.getElementById('my_form_pos').submit(); return false;">
                                        <h5>Point of Purchase</h5>
                                        <p>Showroom point of sale material</p>
                                    </a>
                                    <a href="javascript:{}" onclick="document.getElementById('my_form_pos').submit(); return false;" class="getstart">More info</a>
                                </div>
                            </li>
                        <?php endif; ?>


                        <?php if (can_user_access($user_role, $permissions, 'promo_materials')) : ?>
                            <li>
                                <div class="dash-items"
                                    style="background-image: url('https://nittosupport.ca/assets/uploads/dashboardmenustyle_images/1563815406_Nitto_Promo.jpg');">
                                    <a href="javascript:{}" onclick="document.getElementById('my_form_promo').submit(); return false;">
                                        <h5>Promo Materials</h5>
                                        <p>Wearables and hard goods</p>
                                    </a>
                                    <a href="javascript:{}" onclick="document.getElementById('my_form_promo').submit(); return false;" class="getstart">More info</a>
                                </div>
                            </li>
                        <?php endif; ?>


                        <?php if (can_user_access($user_role, $permissions, 'training_site')) : ?>
                            <li>
                                <div class="dash-items"
                                    style="background-image: url('https://nittosupport.ca/assets/uploads/dashboardmenustyle_images/1547574353_Training_icon.jpg');">
                                    <a href="<?php echo home_url() . '/training-program' ?>">
                                        <h5>Training program</h5>
                                        <p>Learn take the test gets the rewards!</p>
                                    </a>
                                    <a href="<?php echo home_url() . '/training-program' ?>" class="getstart">Get Started</a>
                                </div>
                            </li>
                        <?php endif; ?>
                        <div class="supporTxt">
                            <p>The intention of this site it to provide easy access to Nitto Tires advertising material
                                and the guidelines on how to use this material. Should you have any questions regarding
                                this site and its content please feel free to speak to your Nitto Tire representative or
                                contact us for more information.</p>
                        </div>
                        <div class="videoweek">
                            <h3>Video of the week</h3>
                            <iframe width="560" height="213" src="./Welcome to Nitto Support Site_files/518796625.html"
                                frameborder="0" allow="autoplay; encrypted-media" allowfullscreen=""></iframe>
                        </div>


                </div>
                <!-- Bulleting  -->
                <?php echo get_template_part('templates/template-part/bulletin-box'); ?>
                <!-- Bulleting end -->
            </div>
        </div>
    </div>
</section>
<!-- Content end -->

<!-- Creates the bootstrap modal where the valid dealer no -->
<style>
    .modal-dialog {
        width: 50%
    }

    ;
</style>
<div class="modal fade" id="validdealermodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span
                        class="sr-only">Close</span></button>
            </div>
            <div class="modal-body">
                You must have a valid Nitto account number in your user profile.
            </div>

        </div>
    </div>
</div>



<?php
get_footer('footer.php');
