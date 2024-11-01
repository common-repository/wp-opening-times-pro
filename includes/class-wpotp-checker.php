<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WPOTP_Checker' ) )
{
	class WPOTP_Checker
	{
		private $wpotp_options;
		private $time_regex = '/^[0-9]{1,2}:[0-9]{2}$/';
		private $result = 'closed';

		public function __construct()
		{
			global $wpotp_options;
			$this->wpotp_options = $wpotp_options;
			$this->run_check();
		}

		public function is_open()
		{
			return ( $this->result == 'open' );
		}

		public function is_closed()
		{
			return ( $this->result == 'closed' );
		}

		private function run_check()
		{
			$wp_timezone = get_option('timezone_string');
			date_default_timezone_set($wp_timezone);

			$now = time();
			$dow = strtolower(date('l'));

			if( isset($this->wpotp_options[$dow]) ):
				foreach( $this->wpotp_options[$dow] as $slot ):
					if( preg_match($this->time_regex, $slot['open']) && preg_match($this->time_regex, $slot['close']) ):

						$open_time = strtotime( date('d-m-Y').' '.$slot['open'] );
						$close_time = strtotime( date('d-m-Y').' '.$slot['close'] );

						if( $open_time < $now && $now < $close_time ):
							$this->result = 'open';
						endif;
					endif;
				endforeach;
			else:
				return 'closed';
			endif;
		}

	}
}