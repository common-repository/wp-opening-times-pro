<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WPOTP_Admin_Scripts' ) )
{
	class WPOTP_Admin_Scripts
	{
		public function __construct()
		{
			add_action( 'admin_enqueue_scripts', array($this, 'enqueue_scripts') );
			add_action( 'admin_enqueue_scripts', array($this, 'enqueue_styles') );
		}

		public function enqueue_scripts()
		{
			wp_register_script( 'handlebars-js', WPOTP_PLUGIN_URL . '/assets/vendor/handlebars/handlebars-v4.0.5.js', array('jquery'), WPOTP_VERSION, false );
			wp_enqueue_script( 'handlebars-js' );

			wp_register_script( 'timepicker_js', WPOTP_PLUGIN_URL . '/assets/vendor/jquery-timepicker/jquery.timepicker.min.js', array('jquery'), WPOTP_VERSION, false );
			wp_enqueue_script( 'timepicker_js' );

			wp_register_script( 'wpotp_admin_js', WPOTP_PLUGIN_URL . '/assets/js/wpotp-admin.js', array('jquery', 'jquery-ui-core', 'jquery-ui-tabs', 'handlebars-js'), WPOTP_VERSION, false );
			wp_enqueue_script( 'wpotp_admin_js' );
		}

		public function enqueue_styles()
		{
			wp_register_style( 'timepicker_css', WPOTP_PLUGIN_URL . '/assets/vendor/jquery-timepicker/jquery.timepicker.css', false, WPOTP_VERSION, 'all' );
        	wp_enqueue_style( 'timepicker_css' );

			wp_register_style( 'wpotp_admin_css', WPOTP_PLUGIN_URL . '/assets/css/wpotp-admin.css', false, WPOTP_VERSION, 'all' );
        	wp_enqueue_style( 'wpotp_admin_css' );
		}
	}
}

new WPOTP_Admin_Scripts;