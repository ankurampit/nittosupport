<?php

/** 
 * Template Name: Dashboard Page 
 * Template Post Type: page 
 */

get_template_part('templates/parts/header');
?>

<section class="sticky-container innerSticker">
    <ul class="sticky">
        <li>
            <img src="./Welcome to Nitto Support Site_files/tw-white.png" alt="Twitter">
            <p><a href="https://twitter.com/nittotire" target="_blank">Follow Us on<br>Twitter</a></p>
        </li>
        <li>
            <img src="./Welcome to Nitto Support Site_files/fb-white.png" alt="Facebook">
            <p><a href="https://www.facebook.com/NittoTire/" target="_blank">Like Us on<br>Facebook</a></p>
        </li>
        <li>
            <img src="./Welcome to Nitto Support Site_files/yt-white.png" alt="Youtube">
            <p><a href="https://www.youtube.com/channel/UCacuBR0xB-Hay8pkx1JtDZg" target="_blank">Subscribe
                    on<br>YouYube</a></p>
        </li>
    </ul>
</section>

<div class="innerSlider">
    <!-- <section id="myCarousel" class="carousel"> -->
        <?php echo do_shortcode('[metaslider id="90"]'); ?>
    <!-- </section> -->
</div>


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
                            <li>
                                <a href="javascript:{}"
                                    onclick="document.getElementById(&#39;my_form_pos_frtd&#39;).submit(); return false;"><img
                                        src="./Welcome to Nitto Support Site_files/nitto-frame-full.jpg" alt=""></a>
                                <h5><a href="javascript:{}"
                                        onclick="document.getElementById(&#39;my_form_pos_frtd&#39;).submit(); return false;">Nitto
                                        Poser Frame</a></h5>
                                <p>Nitto quick change poster frame...</p>
                                <a href="javascript:{}"
                                    onclick="document.getElementById(&#39;my_form_pos_frtd&#39;).submit(); return false;"
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


                        <li>
                            <div class="dash-items"
                                style="background-image: url(&#39;https://nittosupport.ca/assets/uploads/dashboardmenustyle_images/1563815722_Nitto_Adv_material.jpg&#39;);">
                                <a href="https://nittosupport.ca/admaterials/index/1">
                                    <h5>Advertising Material</h5>
                                    <p>Download ads, logos and tire pictures.</p>
                                </a>
                                <a href="https://nittosupport.ca/admaterials/index/1" class="getstart">More info</a>
                            </div>
                        </li>




                        <li>
                            <div class="dash-items"
                                style="background-image: url(&#39;https://nittosupport.ca/assets/uploads/dashboardmenustyle_images/1547593506_Dealer_resources.jpg&#39;);">
                                <a href="https://nittosupport.ca/dealerresource/index">
                                    <h5>Dealer Resources</h5>
                                    <p>Download catalogues, general information.</p>
                                </a>
                                <a href="https://nittosupport.ca/dealerresource/index" class="getstart">More
                                    info</a>
                            </div>
                        </li>
                        <li>
                            <div class="dash-items"
                                style="background-image: url(&#39;https://nittosupport.ca/assets/uploads/dashboardmenustyle_images/1547594567_Management_icon2.jpg&#39;);">
                                <a href="https://nittosupport.ca/management/index">
                                    <h5>Management</h5>
                                    <p>Super user management tools</p>
                                </a>
                                <a href="https://nittosupport.ca/management/index" class="getstart">Get started</a>
                            </div>
                        </li>







                        <li>
                            <div class="dash-items"
                                style="background-image: url(&#39;https://nittosupport.ca/assets/uploads/dashboardmenustyle_images/1563815367_Nitto_POP.jpg&#39;);">
                                <a href="javascript:{}"
                                    onclick="document.getElementById(&#39;my_form_pos&#39;).submit(); return false;">

                                    <h5>Point of Purchase</h5>
                                    <p>Showroom point of sale material</p>
                                </a>
                                <a href="javascript:{}"
                                    onclick="document.getElementById(&#39;my_form_pos&#39;).submit(); return false;"
                                    class="getstart">More info</a>
                            </div>
                        </li>


                        <li>
                            <div class="dash-items"
                                style="background-image: url(&#39;https://nittosupport.ca/assets/uploads/dashboardmenustyle_images/1563815406_Nitto_Promo.jpg&#39;);">
                                <a href="javascript:{}"
                                    onclick="document.getElementById(&#39;my_form_promo&#39;).submit(); return false;">
                                    <h5>Promo Materials</h5>
                                    <p>Wearables and hard goods</p>
                                </a>
                                <a href="javascript:{}"
                                    onclick="document.getElementById(&#39;my_form_promo&#39;).submit(); return false;"
                                    class="getstart">More info</a>
                            </div>
                        </li>


                        <li>
                            <div class="dash-items"
                                style="background-image: url(&#39;https://nittosupport.ca/assets/uploads/dashboardmenustyle_images/1547574353_Training_icon.jpg&#39;);">

                                <a href="https://nittosupport.ca/training">
                                    <h5>Training program</h5>
                                    <p>Learn take the test gets the rewards!</p>
                                </a>




                                <a href="https://nittosupport.ca/training" class="getstart">
                                    Get Started </a>
                            </div>
                        </li>



                    </ul>
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
                <div class="col-md-4">
                    <!-- English Version-->

                    <div class="bulletinBox">

                        <!--Not for normal and Advance user-->

                        <h2>Bulletin Board <a class="bltnSeeMr"
                                href="https://nittosupport.ca/bulletinboard/bulletinlist">See More</a></h2>
                        <ul>

                            <li>
                                <a href="https://nittosupport.ca/bulletinboard/index/20"><img
                                        src="./Welcome to Nitto Support Site_files/1739308520_Nitto_Spring_AD_2025_EN.jpg"
                                        alt=""></a>
                                <h5><a href="https://nittosupport.ca/bulletinboard/index/20">Spring 2025 Ad
                                        material</a></h5>
                                <p>UNFORGETTABLE STYLE AND PERFORMANCE</p>
                                <a href="https://nittosupport.ca/bulletinboard/index/20" class="fdetail">Details</a>
                            </li>

                            <li>
                                <a href="https://nittosupport.ca/bulletinboard/index/19"><img
                                        src="./Welcome to Nitto Support Site_files/1708554926_Nitto_Spring_2024_Ads_rebate_Nomad_Terra_EN.jpg"
                                        alt=""></a>
                                <h5><a href="https://nittosupport.ca/bulletinboard/index/19">2024 Spring ad
                                        material</a></h5>
                                <p>2024 Spring ad material</p>
                                <a href="https://nittosupport.ca/bulletinboard/index/19" class="fdetail">Details</a>
                            </li>

                            <li>
                                <a href="https://nittosupport.ca/bulletinboard/index/18"><img
                                        src="./Welcome to Nitto Support Site_files/1690924880_Nitto_Fall_Winter_Rebate_2023_EN.jpg"
                                        alt=""></a>
                                <h5><a href="https://nittosupport.ca/bulletinboard/index/18">2023 Fall ad
                                        material</a></h5>
                                <p>Finding your road! Winter security with that Nitto edge!</p>
                                <a href="https://nittosupport.ca/bulletinboard/index/18" class="fdetail">Details</a>
                            </li>

                            <li>
                                <a href="https://nittosupport.ca/bulletinboard/index/17"><img
                                        src="./Welcome to Nitto Support Site_files/1674164670_Nitto_Spring_2023_Ads_ENG_V1.jpg"
                                        alt=""></a>
                                <h5><a href="https://nittosupport.ca/bulletinboard/index/17">2023 Spring ad
                                        material</a></h5>
                                <p>2023 Spring ad material</p>
                                <a href="https://nittosupport.ca/bulletinboard/index/17" class="fdetail">Details</a>
                            </li>

                            <li>
                                <a href="https://nittosupport.ca/bulletinboard/index/16"><img
                                        src="./Welcome to Nitto Support Site_files/1657829026_Nitto_Fall_2022_Winter_Rebate_EN.jpg"
                                        alt=""></a>
                                <h5><a href="https://nittosupport.ca/bulletinboard/index/16">2022 Fall rebate ad
                                        material</a></h5>
                                <p>2022 Fall rebate ad material</p>
                                <a href="https://nittosupport.ca/bulletinboard/index/16" class="fdetail">Details</a>
                            </li>



                            <li>
                                <a href="https://nittosupport.ca/bulletinboard/index/13"><img
                                        src="./Welcome to Nitto Support Site_files/1638995194_Recon_Grappler.JPG"
                                        alt=""></a>
                                <h5><a href="https://nittosupport.ca/bulletinboard/index/13">Recon Grappler Video is
                                        ready.</a></h5>
                                <p>Recon Grappler Video is ready.</p>
                                <a href="https://nittosupport.ca/bulletinboard/index/13" class="fdetail">Details</a>
                            </li>

                            <li>
                                <a href="https://nittosupport.ca/bulletinboard/index/12"><img
                                        src="./Welcome to Nitto Support Site_files/1629212688_Nitto_rebate_Fall_2021_Ad_EN.jpg"
                                        alt=""></a>
                                <h5><a href="https://nittosupport.ca/bulletinboard/index/12">Nitto Fall/Winter
                                        Rebate Ads are now ready.</a></h5>
                                <p>Nitto Fall/Winter Rebate Ads are not ready.</p>
                                <a href="https://nittosupport.ca/bulletinboard/index/12" class="fdetail">Details</a>
                            </li>

                            <li>
                                <a href="https://nittosupport.ca/bulletinboard/index/10"><img
                                        src="./Welcome to Nitto Support Site_files/1612815402_Nitto_2021Spring_Rebate_Save_EN_LT.jpg"
                                        alt=""></a>
                                <h5><a href="https://nittosupport.ca/bulletinboard/index/10">2021 Spring ad
                                        material</a></h5>
                                <p>2021 Spring ad material is now ready for download.</p>
                                <a href="https://nittosupport.ca/bulletinboard/index/10" class="fdetail">Details</a>
                            </li>



                        </ul>
                        <a style="float:right;" href="https://nittosupport.ca/bulletinboard/bulletinlist">See
                            More</a>


                    </div>
                    <!-- English Version End-->

                </div>
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
                You must have a valid Nitto account number in your user profile. </div>

        </div>
    </div>
</div>



<?php
get_template_part('templates/parts/footer');
