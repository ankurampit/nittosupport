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
        <header class="mooc <?php if(isset($fix) && $fix){echo 'fix';} ?>">
            <div class="<?php echo vibe_get_container(); ?>">
                <div class="mooc_header_wrapper">
                    <?php
                        if(is_front_page()){
                            echo '<h1 id="logo">';
                        }else{
                            echo '<h2 id="logo">';
                        }
                        $url = apply_filters('wplms_logo_url',VIBE_URL.'/assets/images/logo.png','header');
                        if(!empty($url)){
                    ?>
                        <a href="<?php echo vibe_site_url(); ?>"><img src="<?php  echo vibe_sanitizer($url,'url'); ?>" alt="<?php echo get_bloginfo('name'); ?>" /></a>
                    <?php
                        }
                        if(is_front_page()){
                            echo '</h1>';
                        }else{
                            echo '</h2>';
                        }
                            
                    ?>
                    <?php 

                    $course_search = vibe_get_option('course_search');
                    if(empty($course_search) || $course_search == 1 || $course_search == 2){ ?>
                    <div id="mooc_menu"> 
                          <?php
                            $args = apply_filters('wplms-main-menu',array(
                                 'theme_location'  => 'main-menu',
                                 'container'       => 'nav',
                                 'menu_class'      => 'menu',
                                 'walker'          => new vibe_walker,
                                 'fallback_cb'     => 'vibe_set_menu'
                             ));
                            wp_nav_menu( $args ); 
                        ?>
                    </div>
                    <?php } ?>

                    <div class="mooc_search">
                        <div class="search_wrapper">
                                <form method="GET" action="<?php echo home_url(); ?>">
                            <?php
                            
                             if($course_search ==2 || $course_search ==3){

                                $args = apply_filters('wplms_course_nav_cats',array(
                                    'taxonomy'=>'course-cat',
                                    'hide_empty'=>false,
                                    'hierarchial'=>1,
                                  ));

                                $terms = get_terms($args);
                                

                                if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
                                    echo '<select name="'.$args['taxonomy'].'" style="max-width:100px;"><option value="">'._x('All','all courses in course nav search in categories','vibe').'</option>';
                                    foreach ( $terms as $term ) {
                                        echo '<option value="'.$term->slug.'" '.((!empty($_GET[$args['taxonomy']]) && $_GET[$args['taxonomy']] == $term->slug)?'selected':'').'>' . $term->name . '</li>';
                                    }
                                    echo '</select>';
                                }
                            }
                            if($course_search)
                                echo '<input type="hidden" name="post_type" value="course" />';
                            ?>
                            <input type="text" name="s" placeholder="<?php _ex('Search courses..','search placeholder','vibe'); ?>" value="<?php echo isset($_GET['s'])?$_GET['s']:''; ?>" />
                                    
                                </form>
                          
                        </div>
                    </div>
                    <?php if(!empty($course_search) && $course_search == 3){ ?>
                    <div id="mooc_menu"> 
                          <?php
                            $args = apply_filters('wplms-main-menu',array(
                                 'theme_location'  => 'main-menu',
                                 'container'       => 'nav',
                                 'menu_class'      => 'menu',
                                 'walker'          => new vibe_walker,
                                 'fallback_cb'     => 'vibe_set_menu'
                             ));
                            wp_nav_menu( $args ); 
                        ?>
                    </div>
                    <?php } ?>
                    <ul class="topmenu">
                        <?php do_action('wplms_header_top_login'); ?>
                        <?php
                        if ( apply_filters('wplms_show_mini_cart',function_exists('woocommerce_mini_cart'))) { global $woocommerce;
                        ?>
                            <li><a class=" vbpcart"><span class="vicon vicon-shopping-cart"><?php echo (($woocommerce->cart->cart_contents_count)?'<em>'.$woocommerce->cart->cart_contents_count.'</em>':''); ?></span></a>
                            <div class="woocart"><div class="widget_shopping_cart_content"><?php woocommerce_mini_cart(); ?></div></div>
                            </li>
                        <?php
                        }

                            
                            echo '<li>'.apply_filters('wplms_login_trigger','<a href="#login" rel="nofollow" class=" vibebp-login"><span>'.__('LOGIN','vibe').'</span></a>').'</li>';
                            do_action('wp_head_wplms_login');
                        
                        ?>
                    </ul>
                <a id="trigger">
                    <span class="lines"></span>
                </a>
            </div>
        </div>
    </header>
