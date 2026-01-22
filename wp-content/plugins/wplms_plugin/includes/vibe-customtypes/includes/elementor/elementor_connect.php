<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function wplms_add_elementor_widget_categories( $elements_manager ) {

		$elements_manager->add_category(
			'wplms',
			[
				'title' => __( 'WPLMS', 'wplms' ),
				'icon' => 'vicon vicon-plug',
			]
		);
		
}
add_action( 'elementor/elements/categories_registered', 'wplms_add_elementor_widget_categories' );


/**
 * Main Elementor Test Extension Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class Elementor_WPLMS_Extension {

	
	const VERSION = '1.0.0';

	
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	const MINIMUM_PHP_VERSION = '5.6';

	private static $_instance = null;

	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

	public function __construct() {

		add_action( 'init', [ $this, 'i18n' ] );
		add_action( 'plugins_loaded', [ $this, 'init' ] );
		//add_action('template_redirect',array($this,'record_page'));
	}

	public function i18n() {

		load_plugin_textdomain( 'wplms' );

	}

	public function init() {

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			//add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return;
		}

		// Add Plugin actions
		add_action( 'elementor/widgets/register', [ $this, 'init_widgets' ] );
		//add_action( 'elementor/controls/register', [ $this, 'init_controls' ] );

		
	}

	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'wplms' ),
			'<strong>' . esc_html__( 'Elementor Test Extension', 'wplms' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'wplms' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'wplms' ),
			'<strong>' . esc_html__( 'Elementor Test Extension', 'wplms' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'wplms' ) . '</strong>',
			 self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'wplms' ),
			'<strong>' . esc_html__( 'Elementor Test Extension', 'wplms' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'wplms' ) . '</strong>',
			 self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	
	public function init_widgets($widgets_manager) {


		global $post;
		if(!is_admin() || ($post && $post->post_type === 'page' )){
			require_once( __DIR__ . '/widgets/directory.php' );
			$widgets_manager->register( new \WPLMS_Course_Directory() );
		}
		

		if((!empty($_REQUEST['action']) && $_REQUEST['action']==='elementor_ajax') || !is_admin() || ($post && ($post->post_type === 'course-layout' || $post->post_type === 'course-card' || $post->post_type === 'course'))){
			require_once( __DIR__ . '/widgets/course/featured.php' );
			$widgets_manager->register( new \Wplms_Course_featured() );
			require_once( __DIR__ . '/widgets/course/button.php' );
			 $widgets_manager->register( new \Wplms_Course_Button() );
			 
			 require_once( __DIR__ . '/widgets/course/courselink.php' );
			 $widgets_manager->register( new \Wplms_Course_Link() );
			 require_once( __DIR__ . '/widgets/course/info.php' );
			 $widgets_manager->register( new \Wplms_Course_Info() );

			 require_once( __DIR__ . '/widgets/course/curriculum.php' );
			 $widgets_manager->register( new \Wplms_Course_Curriculum() );
			 require_once( __DIR__ . '/widgets/course/reviews.php' );
			 $widgets_manager->register( new \Wplms_Elementor_Course_Reviews() );

			 require_once( __DIR__ . '/widgets/course/pricing.php' );
			 $widgets_manager->register( new \Wplms_Course_Pricing() );

			 require_once( __DIR__ . '/widgets/course/instructor.php' );
			 $widgets_manager->register( new \Wplms_Course_Instructor_Field() );

			 require_once( __DIR__ . '/widgets/course/instructor_card.php' );
			 $widgets_manager->register( new \Wplms_Course_Instructor_Card() );

			require_once( __DIR__ . '/widgets/course/instructor_data.php' );
			$widgets_manager->register( new \Wplms_Course_Instructor_Data() ); 


			require_once( __DIR__ . '/widgets/course/coursecodes.php' );
			$widgets_manager->register( new \Wplms_Course_Codess() );
			do_action('wplms_elementor_course_widgets_add',$post);
		
		}

		//
		if(!is_admin() || ($post && ($post->post_type == 'member-profile' || $post->post_type == 'member-card'))){

			require_once( __DIR__ . '/widgets/instructor_data.php' );
			$widgets_manager->register( new \Wplms_Instructor_Data() ); 

			require_once( __DIR__ . '/widgets/course/achievements.php' );
			$widgets_manager->register( new \Wplms_Achievements());

			require_once( __DIR__ . '/widgets/course/user_reviews.php' );
			$widgets_manager->register( new \Wplms_User_Reviews());

		}

		// Include Widget files
		require_once( __DIR__ . '/widgets/vibe-carousel.php' );
		$widgets_manager->register( new \Wplms_Vibe_Carousel() );


		require_once( __DIR__ . '/widgets/vibe-grid.php' );
		$widgets_manager->register( new \Wplms_Vibe_Grid() );

		require_once( __DIR__ . '/widgets/vibe-filterable.php' );
		$widgets_manager->register( new \Wplms_Vibe_Filterable() );

		require_once( __DIR__ . '/widgets/vibe-courseCarousel.php' );
		$widgets_manager->register( new \Wplms_Vibe_CourseCarousel() );

		// Register widget vibe pullquote
		require_once( __DIR__ . '/widgets/vibe-pullquote.php' );
		$widgets_manager->register( new \Wplms_Vibe_Pullquote() );
		require_once( __DIR__ . '/widgets/vibe-button.php' );
		$widgets_manager->register( new \Wplms_Vibe_Button() );

		// Register widget vibe countdown
		require_once( __DIR__ . '/widgets/vibe-countdown.php' );
		$widgets_manager->register( new \Wplms_Vibe_Countdown() );
		require_once( __DIR__ . '/widgets/vibe-show-certificates.php' );
		$widgets_manager->register( new \Wplms_Vibe_Show_Certificates() );

		// Register widget vibe iframe
		require_once( __DIR__ . '/widgets/vibe-iframe.php' );
		$widgets_manager->register( new \Wplms_Vibe_Iframe() );
		require_once( __DIR__ . '/widgets/vibe-note.php' );
		$widgets_manager->register( new \Wplms_Vibe_Note() );

		// Register widget vibe popup
		require_once( __DIR__ . '/widgets/vibe-popup.php' );
		$widgets_manager->register( new \Wplms_Vibe_Popup() );
		require_once( __DIR__ . '/widgets/vibe-testimonial.php' );
		$widgets_manager->register( new \Wplms_Vibe_Testimonial() );
		require_once( __DIR__ . '/widgets/vibe-team.php' );
		$widgets_manager->register( new \Wplms_Vibe_Team() );
		require_once( __DIR__ . '/widgets/vibe-course.php' );
		$widgets_manager->register( new \Wplms_Vibe_Course() );
		require_once( __DIR__ . '/widgets/vibe-form.php' );
		$widgets_manager->register( new \Wplms_Vibe_Form() );
		require_once( __DIR__ . '/widgets/vibe-sell-content.php' );
		$widgets_manager->register( new \Wplms_Vibe_Sell_Content() );
		require_once( __DIR__ . '/widgets/vibe-registration-form.php' );


		$widgets_manager->register( new \wplms_Vibe_registration_form() );
		require_once( __DIR__ . '/widgets/member-grid.php' );
		$widgets_manager->register( new \wplms_member_grid() );
		require_once( __DIR__ . '/widgets/member-carousel.php' );
		$widgets_manager->register( new \wplms_member_carousel() );
	}


	/**
	 * Init Controls
	 *
	 * Include controls files and register them
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init_controls() {

		// Include Control files
		//require_once( __DIR__ . '/controls/test-control.php' );

		// Register control
		//\Elementor\Plugin::$instance->controls_manager->register_control( 'control-type-', new \Test_Control() );

	}

}

Elementor_WPLMS_Extension::instance();

    
       
