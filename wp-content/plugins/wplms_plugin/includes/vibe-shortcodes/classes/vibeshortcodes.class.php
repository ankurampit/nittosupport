<?php
if ( ! defined( 'ABSPATH' ) ) exit;


class VibeShortcodes {

	 public static $instance;
    var $schedule;
    public static function init(){

      if ( is_null( self::$instance ) )
          self::$instance = new VibeShortcodes();

      return self::$instance;
    }

    private function __construct(){
    
        add_action('admin_enqueue_scripts', array($this, 'admin_icons'),10,1);
        add_action('template_redirect',array($this,'enqueue_frontEnd'));
        add_action('wp_enqueue_scripts', array($this, 'frontend'));
	}
	

	function enqueue_frontEnd(){

			if(function_exists('vibe_get_option') && vibe_get_option('offload_scripts'))
				return;

			$enable_shortcodes = apply_filters('wplms_vibe_shortcodes',1);
			if($enable_shortcodes){
				if(is_user_logged_in() && current_user_can('edit_posts') && function_exists('vibe_get_option')){
					$create_course = vibe_get_option('create_course');
					if(function_exists('icl_object_id')){
						$create_course = icl_object_id($create_course);
					}
					if(!empty($create_course) && is_page($create_course) ){
						add_action('wp_enqueue_scripts', array($this, 'shortcodes_front_end'));
					}	
				}
			}
		}

    function frontend(){
    	

    	if(function_exists('vibe_get_option') && vibe_get_option('offload_scripts')){
    	
			return;
    	}
		
       	wp_enqueue_script( 'shortcodes-js', WPLMS_PLUGIN_INCLUDES_URL . '/vibe-shortcodes/js/shortcodes.js',array('jquery','mediaelement','thickbox'),WPLMS_PLUGIN_VERSION,true);
       	$translation_array = array( 
							'sending_mail' => __( 'Sending mail','wplms' ), 
							'error_string' => __( 'Error :','wplms' ),
							'invalid_string' => __( 'Invalid ','wplms' ),
							'captcha_mismatch' => __( 'Captcha Mismatch','wplms' ), 
							);
       	wp_localize_script( 'shortcodes-js', 'vibe_shortcode_strings', $translation_array );
    }

    function admin_icons($hook){
    	if(is_admin() && ( in_array( $hook, array( 'post-new.php', 'post.php' ) ) || ($hook == 'lms_page_lms-settings' && !empty($_GET['page']) &&$_GET['page'] == 'lms-settings' && isset($_GET['tab']) && $_GET['tab'] == 'general' && !empty($_GET['sub']) && $_GET['sub'] == 'loggedin_menu') ) ){
            wp_enqueue_style( 'icons-css', WPLMS_PLUGIN_INCLUDES_URL.'/vibe-shortcodes/css/fonticons.css');
        }
    }
	
}
VibeShortcodes::init();

?>