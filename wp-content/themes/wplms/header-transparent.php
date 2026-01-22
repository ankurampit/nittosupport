<?php
//Header File
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<?php
wp_head();
?>
</head>
<body <?php body_class(); ?>>
<div id="global" class="global">
    <?php
        get_template_part('mobile','sidebar');
    ?>  
    <div class="pusher">
        <?php
            $fix=vibe_get_option('header_fix');
        ?>
        <header class="sleek transparent <?php if(isset($fix) && $fix){echo 'fix';} ?>">
            <div class="<?php echo vibe_get_container(); ?>">
                <div class="row">
                    <div class="col-md-9 col-sm-6 col-xs-4 col-4">
                        <?php

                            if(is_home()){
                                echo '<h1 id="logo">';
                            }else{
                                echo '<h2 id="logo">';
                            }

                            $url = apply_filters('wplms_logo_url',VIBE_URL.'/assets/images/logo.png','header');
                            if(!empty($url)){
                        ?>
                        
                            <a href="<?php echo vibe_site_url('','logo'); ?>"><img src="<?php  echo vibe_sanitizer($url,'url'); ?>" alt="<?php echo get_bloginfo('name'); ?>" /></a>
                        <?php

                            }
                            if(is_home()){
                                echo '</h1>';
                            }else{
                                echo '</h2>';
                            }

                            $args = apply_filters('wplms-main-menu',array(
                                 'theme_location'  => 'main-menu',
                                 'container'       => 'nav',
                                 'menu_class'      => 'menu',
                                 'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s<li><a id="new_searchicon"><i class="vicon vicon-search"></i></a></li></ul>',
                                 'walker'          => new vibe_walker,
                                 'fallback_cb'     => 'vibe_set_menu'
                             ));
                            wp_nav_menu( $args ); 
                        ?>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-8 col-8">
                        <?php
                            $show_cart = apply_filters('wplms_header_show_cart',1);
                            
                                ?>
                                <ul class="topmenu">
                                    <?php
                                       
                                        
                                        echo '<li>'.apply_filters('wplms_login_trigger','<a href="#login" rel="nofollow" class=" vibebp-login"><span>'.__('LOGIN','vibe').'</span></a>').'</li>';
                                        do_action('wp_head_wplms_login');
                                    
                                ?>
                                <?php
                                if ( apply_filters('wplms_show_mini_cart',function_exists('woocommerce_mini_cart')) && $show_cart) { global $woocommerce;
                                ?>
                                <li><a class=" vbpcart"><span class="vicon vicon-shopping-cart"><?php echo (($woocommerce->cart->cart_contents_count)?'<em>'.$woocommerce->cart->cart_contents_count.'</em>':''); ?></span></a>
                                <div class="woocart"><?php woocommerce_mini_cart(); ?></div>
                                </li>
                                <?php
                                }
                                ?>
                            </ul>
                    </div>
                    <a id="trigger">
                        <span class="lines"></span>
                    </a>
                </div>
            </div>
        </header>
