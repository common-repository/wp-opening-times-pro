<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WPOTP_Settings' ) )
{
	class WPOTP_Settings
	{
		public static function init()
		{
			add_filter( 'option_page_capability_wpotp_settings', array(__CLASS__,'set_settings_cap') );
			add_filter( 'wpotp_settings_sanitize_text', array(__CLASS__,'sanitize_text_field') );
			add_action( 'admin_init', array(__CLASS__,'register_settings') );
		}


		public static function get_option( $key = '', $default = false )
		{
			global $wpotp_options;
			$value = ! empty( $wpotp_options[ $key ] ) ? $wpotp_options[ $key ] : $default;
			$value = apply_filters( 'wpotp_get_option', $value, $key, $default );
			return apply_filters( 'wpotp_get_option_' . $key, $value, $key, $default );
		}


		public static function update_option( $key = '', $value = false )
		{
			if ( empty( $key ) )
				return false;

			if ( empty( $value ) )
			{
				$remove_option = self::delete_option( $key );
				return $remove_option;
			}

			$options = get_option( 'wpotp_settings' );

			$value = apply_filters( 'wpotp_update_option', $value, $key );

			$options[ $key ] = $value;
			$did_update = update_option( 'wpotp_settings', $options );

			if ( $did_update )
			{
				global $wpotp_options;
				$wpotp_options[ $key ] = $value;
			}

			return $did_update;
		}


		public static function delete_option( $key = '' )
		{
			if ( empty( $key ) )
				return false;

			$options = get_option( 'wpotp_settings' );

			if( isset( $options[ $key ] ) )
				unset( $options[ $key ] );

			$did_update = update_option( 'wpotp_settings', $options );

			if ( $did_update )
			{
				global $wpotp_options;
				$wpotp_options = $options;
			}

			return $did_update;
		}


		public static function get_settings()
		{
			$settings = get_option( 'wpotp_settings' );

			if( !$settings )
				$settings = array();

			return apply_filters( 'wpotp_get_settings', $settings );
		}


		public static function register_settings()
		{
			if ( false == get_option( 'wpotp_settings' ) )
				add_option( 'wpotp_settings' );

			add_settings_section( 'wpotp_settings_times', __return_null(), '__return_false', 'wpotp_settings_times' );
			//add_settings_section( 'wpotp_settings_holidays', __return_null(), '__return_false', 'wpotp_settings_holidays' );
			add_settings_section( 'wpotp_settings_settings', __return_null(), '__return_false', 'wpotp_settings_settings' );

			add_settings_field( 'wpotp_settings[monday]', 'Monday', 'WPOTP_Settings::times_field_callback', 'wpotp_settings_times', 'wpotp_settings_times', array('name' => 'Monday', 'id' => 'monday'));
			add_settings_field( 'wpotp_settings[tuesday]', 'Tuesday', 'WPOTP_Settings::times_field_callback', 'wpotp_settings_times', 'wpotp_settings_times', array('name' => 'Tuesday', 'id' => 'tuesday'));
			add_settings_field( 'wpotp_settings[wednesday]', 'Wednesday', 'WPOTP_Settings::times_field_callback', 'wpotp_settings_times', 'wpotp_settings_times', array('name' => 'Wednesday', 'id' => 'wednesday'));
			add_settings_field( 'wpotp_settings[thursday]', 'Thursday', 'WPOTP_Settings::times_field_callback', 'wpotp_settings_times', 'wpotp_settings_times', array('name' => 'Thursday', 'id' => 'thursday'));
			add_settings_field( 'wpotp_settings[friday]', 'Friday', 'WPOTP_Settings::times_field_callback', 'wpotp_settings_times', 'wpotp_settings_times', array('name' => 'Friday', 'id' => 'friday'));
			add_settings_field( 'wpotp_settings[saturday]', 'Saturday', 'WPOTP_Settings::times_field_callback', 'wpotp_settings_times', 'wpotp_settings_times', array('name' => 'Saturday', 'id' => 'saturday'));
			add_settings_field( 'wpotp_settings[sunday]', 'Sunday', 'WPOTP_Settings::times_field_callback', 'wpotp_settings_times', 'wpotp_settings_times', array('name' => 'Sunday', 'id' => 'sunday'));

			add_settings_field( 'wpotp_settings[settings][interval]', 'Time Selection Interval', 'WPOTP_Settings::interval_field_callback', 'wpotp_settings_settings', 'wpotp_settings_settings');
			add_settings_field( 'wpotp_settings[settings][display]', 'Display Type', 'WPOTP_Settings::display_field_callback', 'wpotp_settings_settings', 'wpotp_settings_settings');

			register_setting( 'wpotp_settings', 'wpotp_settings', array(__CLASS__, 'settings_sanitize') );
		}


		public static function settings_sanitize( $input = array() )
		{
			global $wpotp_options;

			if ( empty( $_POST['_wp_http_referer'] ) )
				return $input;

			parse_str( $_POST['_wp_http_referer'], $referrer );

			$dows = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');

			$input = $input ? $input : array();

			foreach( $dows as $day )
			{
				if( !isset($input[$day]) )
					$input[$day] = array();
			}

			$output = array_merge( $wpotp_options, $input );

			add_settings_error( 'wpotp-notices', '', __( 'Settings updated.', 'wpotp' ), 'updated' );

			return $output;
		}


		public static function sanitize_text_field( $input )
		{
			return trim( $input );
		}

		public static function times_field_callback( $args )
		{
			global $wpotp_options;
			echo '<div class="wpotp-timepicker">';
				echo '<div class="wpotp-timepicker__inner" id="js-wptop-timepicker__target-'.$args['id'].'">';
					if( $wpotp_options[$args['id']] ): $i = 0; foreach( $wpotp_options[$args['id']] as $day):

						echo '<div class="wpotp-timepicker__row">';
							echo '<span class="wpotp-timepicker__divide">open from</span>';
							echo '<input type="text" value="'.$day['open'].'" name="wpotp_settings['.$args['id'].'][0][open]" id="wpotp_settings['.$args['id'].'][0][open]" class="wpotp-timepicker__input js__wpotp-timepicker">';
							echo '<span class="wpotp-timepicker__divide">to</span>';
							echo '<input type="text" value="'.$day['close'].'" name="wpotp_settings['.$args['id'].'][0][close]" id="wpotp_settings['.$args['id'].'][0][close]" class="wpotp-timepicker__input js__wpotp-timepicker">';
							echo '<span class="wpotp-timepicker__delete js__wpotp-timepicker-remove"><span class="dashicons dashicons-no-alt"></span></span>';
						echo '</div>';

						$i++;

					endforeach; endif;
				echo "</div>";
				echo '<span class="wpotp-timepicker__button js__wpotp-timepicker-new" data-template="#timepicker-template--'.$args['id'].'" data-target="#js-wptop-timepicker__target-'.$args['id'].'">Add New Period</span>';
				echo '<script id="timepicker-template--'.$args['id'].'" type="text/x-handlebars-template"><div class="wpotp-timepicker__row"><span class="wpotp-timepicker__divide">open from</span><input type="text" value="" name="wpotp_settings['.$args['id'].'][{{count}}][open]" id="wpotp_settings['.$args['id'].'][{{count}}][open]" class="wpotp-timepicker__input js__wpotp-timepicker"><span class="wpotp-timepicker__divide">to</span><input type="text" value="" name="wpotp_settings['.$args['id'].'][{{count}}][close]" id="wpotp_settings['.$args['id'].'][{{count}}][close]" class="wpotp-timepicker__input js__wpotp-timepicker"><span class="wpotp-timepicker__delete js__wpotp-timepicker-remove"><span class="dashicons dashicons-no-alt"></span></span></div></script>';
			echo '</div>';
		}

		public static function interval_field_callback()
		{
			global $wpotp_options;

			if( isset($wpotp_options['settings']['interval']) && in_array($wpotp_options['settings']['interval'], array('1','5','15','30','60')) ):
				$value = $wpotp_options['settings']['interval'];
			else:
				$value = '30';
			endif;

			echo '<div class="wpotp-settings__select">';
				echo '<select name="wpotp_settings[settings][interval]" id="wpotp_settings[settings][interval]">';
					echo '<option '.( $value == "1" ? "selected" : "" ).' value="1">1 minute</option>';
					echo '<option '.( $value == "5" ? "selected" : "" ).' value="5">5 minute</option>';
					echo '<option '.( $value == "15" ? "selected" : "" ).' value="15">15 minute</option>';
					echo '<option '.( $value == "30" ? "selected" : "" ).' value="30">30 minute</option>';
					echo '<option '.( $value == "60" ? "selected" : "" ).' value="60">1 hour</option>';
				echo '</select>';
			echo '</div>';
		}
		
		public static function display_field_callback()
		{
			global $wpotp_options;

			if( isset($wpotp_options['settings']['display']) && in_array($wpotp_options['settings']['display'], array('12', '24')) ):
				$value = $wpotp_options['settings']['display'];
			else:
				$value = '24';
			endif;

			echo '<div class="wpotp-settings__select">';
				echo '<select name="wpotp_settings[settings][display]" id="wpotp_settings[settings][display]">';
					echo '<option '.( $value == "12" ? "selected" : "" ).' value="12">12 Hour</option>';
					echo '<option '.( $value == "24" ? "selected" : "" ).' value="24">24 Hour</option>';
				echo '</select>';
			echo '</div>';
		}

		public static function set_settings_cap()
		{
			return 'manage_options';
		}
		
	}
}






