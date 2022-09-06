<?php

/**
 * Plugin Name: Indiano Travel
 * Description: We building Elementor Extention for our official project. This extention based on indiano travel blog.
 * Plugin URI:  https://inovate.digital/
 * Version:     1.0.0
 * Author:      Inovate Degital
 * Author URI:  https://www.inovate.digital/
 * Text Domain: inovate-elementor
 * Domain Path: /languages
 */



if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */

final class Inovate_Elementor_Extension {

	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 *
	 * @var string The plugin version.
	 */
	const VERSION = '1.0.0';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '7.0';

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var Inovate_Elementor_Extension The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return Inovate_Elementor_Extension An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {

		add_action( 'init', [ $this, 'i18n' ] );
		add_action( 'plugins_loaded', [ $this, 'init' ] );

	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function i18n() {

		load_plugin_textdomain( 'inovate-elementor', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );

	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init() {

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
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

		// Register Widget Styles
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'widget_styles' ] );

		add_action('elementor/frontend/after_enqueue_scripts', [ $this, 'widget_scripts' ] );

		// Add Plugin actions
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
		add_action( 'elementor/controls/controls_registered', [ $this, 'init_controls' ] );

        // Category Init
		add_action( 'elementor/init', [ $this, 'elementor_common_category' ] );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'inovate-elementor' ),
			'<strong>' . esc_html__( 'Inovate Elementor', 'inovate-elementor' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'inovate-elementor' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'inovate-elementor' ),
			'<strong>' . esc_html__( 'Inovate Elementor', 'inovate-elementor' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'inovate-elementor' ) . '</strong>',
			 self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'inovate-elementor' ),
			'<strong>' . esc_html__( 'Inovate Elementor', 'inovate-elementor' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'inovate-elementor' ) . '</strong>',
			 self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init_widgets() {

		 require_once( __DIR__ . '/widgets/it-city-hero.php' );
		 require_once( __DIR__ . '/widgets/it-place-hero.php' );
		 require_once( __DIR__ . '/widgets/it-accordion.php' );
		 require_once( __DIR__ . '/widgets/indiano-author.php' );
		 require_once( __DIR__ . '/widgets/it-posts.php' );
		 require_once( __DIR__ . '/widgets/it-posts2.php' );
		
		 require_once( __DIR__ . '/widgets/it-city.php' );
		 require_once( __DIR__ . '/widgets/it-place.php' );



		 \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new City_Hero_Widget() );
		 \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Place_Hero_Widget() );
		 \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new IT_Accordion_Widget() );
		 \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Indiano_Author() );
		 \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new IT_Posts_Widget() );
		 \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new IT_Posts_Widget_Two() );
		 \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new IT_City_Widget() );
		 \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new IT_Place_Widget() );


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

		/*
		* Todo: this block needs to be commented out when the custom control is ready
		*
		*
		// Include Control files
		require_once( __DIR__ . '/controls/test-control.php' );
		// Register control
		\Elementor\Plugin::$instance->controls_manager->register_control( 'control-type-', new \Test_Control() );
		*/

	}

	// Custom CSS
	public function widget_styles() {

		wp_register_style( 'inovate-main', plugins_url( 'assets/css/inovate-main.css', __FILE__ ), array(), time( ) );
		wp_enqueue_style( 'inovate-main' );
		
	}	

    // Custom JS
	public function widget_scripts() {

		wp_register_script( 'inovate-main', plugins_url( 'assets/js/inovate-main.js', __FILE__ ), array( ), time( ) );
		wp_enqueue_script( 'inovate-main' );
	}

    // Custom Category
    public function elementor_common_category () {

	   \Elementor\Plugin::$instance->elements_manager->add_category( 
	   	'inovate-category',
	   	[
	   		'title' => __( 'Indiano Travel', 'inovate-elementor' ),
	   		'icon' => 'fa fa-plug', //default icon
	   	]
	   );

	}


}

Inovate_Elementor_Extension::instance();


// Change wordpress default logo
function wpb_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
        background-image: url(https://indiano.travel/wp-content/uploads/2022/01/indiano-travel.png);
        background-repeat: no-repeat;
        padding-bottom: 10px;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'wpb_login_logo' );

/* 
		Load More using Ajax For city
*/

add_action( 'wp_footer', 'my_action_javascript' ); 

function my_action_javascript() { ?>
	<script type="text/javascript" >
	jQuery(document).ready(function($) {

		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        var page = 4;
		var page_count = '<?php echo  ceil(wp_count_posts('city')->publish/4); ?>';

		jQuery('#load_more').on('click',function(){ 
		
			

		var data = {
			'action': 'my_action',
			'page': page
		};

		
		jQuery.post(ajaxurl, data, function(response) {
			jQuery('.it-post-card').append(response);

			if(page_count == page){
                    jQuery('#load_more').hide();
                }
             page = page + 1; 

		});

	});

	});
	</script> <?php
}

add_action( 'wp_ajax_my_action', 'my_action' );
add_action( 'wp_ajax_nopriv_my_action', 'my_action' );

function my_action() {
	global $wpdb; // this is how you get access to the database

	$args = array(
		'post_type' => 'city',
		//'posts_per_page' => $it_city_count,
		'post_status' => 'publish',
		'post__not_in' => array(get_the_ID()),
		'tag' => $it_city_tag,
		'tax_query' => $tax_query,
		'paged' => $_POST['page']

	);
	$city_query = new WP_Query( $args );
	if( $city_query->have_posts() ) :
		while( $city_query->have_posts() ) : $city_query->the_post(); ?>
   <div class="it-card-item">
	<div class="it-card-img">
		<a href="<?php the_permalink(); ?>">
			<?php if ( has_post_thumbnail() ) { the_post_thumbnail();}
				else { echo '<img src="https://source.unsplash.com/300x200" />';} 
			?>
		</a>
	</div>
	<div class="it-card-content">
		<a href="<?php the_permalink(); ?>">
			<h3><?php the_title(); ?></h3>
		</a>

		 <span class="it-city state-tag"> 
			 <?php
				$state_names = get_the_terms($post->ID, 'state_name');
				if ($state_names) {
					foreach ($state_names as $state_name) {
						echo $state_name->name;
					}
				}
			 ?>
		</span>

	</div>
</div> <!-- single item  -->
<?php endwhile;
	else : _e( 'Sorry, no city matched your criteria.', 'inovate-elementor' );
	endif; 

	wp_die(); 
}







