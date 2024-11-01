<?php
/**
 * Plugin Name: WP Opening Times Pro
 * Plugin URI: https://wearewoolf.com/wp-opening-times-pro/
 * Description: Opening Time Management Plugin for WordPress. Includes Shortcodes and a Widget.
 * Author: Team Woolf
 * Author URI: https://wearewoolf.com
 * Version: 1.2
 * Text Domain: wpotp
 * Domain Path: languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WP_Opening_Times_Pro' ) ) :

	final class WP_Opening_Times_Pro
	{
		private static $instance;

		private $checker;

		public static function instance()
		{
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof WP_Opening_Times_Pro ) )
			{
				self::$instance = new WP_Opening_Times_Pro;
				self::$instance->setup_constants();
				self::$instance->includes();

				self::$instance->checker = new WPOTP_Checker;
			}
			return self::$instance;
		}


		public function __clone()
		{
			_doing_it_wrong( __FUNCTION__, __( 'Ah Ah Ah.', 'denn' ), '0.1' );
		}


		public function __wakeup()
		{
			_doing_it_wrong( __FUNCTION__, __( 'Ah Ah Ah.', 'denn' ), '0.1' );
		}


		private function setup_constants()
		{
			if ( ! defined( 'WPOTP_VERSION' ) )
				define( 'WPOTP_VERSION', '0.1' );

			if ( ! defined( 'WPOTP_PLUGIN_DIR' ) )
				define( 'WPOTP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

			if ( ! defined( 'WPOTP_PLUGIN_URL' ) )
				define( 'WPOTP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

			if ( ! defined( 'WPOTP_PLUGIN_FILE' ) )
				define( 'WPOTP_PLUGIN_FILE', __FILE__ );
		}


		private function includes()
		{
			//* Settings
			global $wpotp_options;
			require_once WPOTP_PLUGIN_DIR . 'includes/class-wpotp-admin-settings.php';
			WPOTP_Settings::init();
			$wpotp_options = WPOTP_Settings::get_settings();

			//* Top Level Classes
			require_once WPOTP_PLUGIN_DIR . 'includes/class-wpotp-checker.php';
			require_once WPOTP_PLUGIN_DIR . 'includes/class-wpotp-shortcodes.php';
			require_once WPOTP_PLUGIN_DIR . 'includes/class-wpotp-widget-opening-times.php';

			//* Admin Only
			if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) )
			{
				require_once WPOTP_PLUGIN_DIR . 'includes/class-wpotp-admin-pages.php';
				require_once WPOTP_PLUGIN_DIR . 'includes/class-wpotp-admin-scripts.php';
			}
		}


		public function is_open()
		{
			return $this->checker->is_open();
		}

		public function is_closed()
		{
			return $this->checker->is_closed();
		}
	}

endif;


/**
 * The main function for that returns WP_Opening_Times_Pro 
 * 
 * The main function responsible for returning the one true WP_Opening_Times_Pro
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except
 * without needing to declare the global.
 *
 * Example: <?php $opening_times = WPOTP(); ?>
 */
function WPOTP() {
	return WP_Opening_Times_Pro::instance();
}

WPOTP();

