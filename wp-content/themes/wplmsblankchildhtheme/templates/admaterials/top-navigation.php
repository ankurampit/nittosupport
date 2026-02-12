<div class="container">
    <div class="tabs">
        <ul class="material-tabs">
            <li class=" tab-one <?php echo $page == 'print-ads' ? 'active-tab' : '' ; ?>">
                <a href="<?php echo home_url('/print-ads/'); ?>">
                    <i><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/link.png">
                    </i>
                    <span class="title">Print Ads</span>
                </a>
            </li>
            <li class=" tab-two <?php echo $page == 'radio' ? 'active-tab' : '' ;  ?>">
                <a href="<?php echo home_url() . '/radio/'; ?>">
                    <i>
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/dealer-resources.png">
                    </i>
                    <span class="title">Radio</span>
                </a>
            </li>

            <li class="tab-three <?php echo $page == 'nitto-logo' ? 'active-tab' : '' ; ?> ">
                <a href="<?php echo home_url('/nitto-logo/'); ?>">
                    <i><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/point-of-purchase.png">
                    </i>
                    <span class="title">Nitto Logo</span>
                </a>
            </li>

            <li class="tab-four <?php echo $page == 'tire-photo' ? 'active-tab' : '' ; ?>">
                <a href="<?php echo home_url('/tire-photo-page/'); ?>">
                    <i><img src="<?php echo get_stylesheet_directory_uri(); ?>\assets\images\point-of-purchase.png">
                    </i>
                    <span class="title">Tire Photo</span>
                </a>
            </li>

            <li class="tab-five <?php echo $page == 'television-and-video' ? 'active-tab' : '' ; ?>">
         <a href="<?php echo home_url('/television-and-video-page/'); ?>">
                    <i><img src="<?php echo get_stylesheet_directory_uri(); ?>\assets\images\point-of-purchase.png">
                    </i>
                    <span class="title">Television & Video</span>
                </a></li>
 
            <li class="tab-six <?php echo $page == 'web-and-online' ? 'active-tab' : '' ; ?>">
                <a href="<?php echo home_url('/web-online-page/'); ?>">
                    <i><img src="<?php echo get_stylesheet_directory_uri(); ?>\assets\images\point-of-purchase.png">
                    </i>
                    <span class="title">Web & Online</span>
                </a>
            </li>

            <li class="tab-seven <?php echo $page == 'vehicle-picture' ? 'active-tab' : '' ; ?>"> <a href="<?php echo home_url('/vehicle-picture/'); ?>">
                    <i><img src="<?php echo get_stylesheet_directory_uri(); ?>\assets\images\point-of-purchase.png">
                    </i>
                    <span class="title">Vehicle Picture</span>
                </a></li>

        </ul>


    </div>