<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WPOTP_Shortcodes' ) )
{
	class WPOTP_Shortcodes
	{
		public function __construct()
		{
			add_action( 'init', array(__CLASS__, 'register_shortcodes'));
		}
		public static function register_shortcodes()
		{
			add_shortcode('is_open', array(__CLASS__, 'is_open_shortcode'));
			add_shortcode('is_closed', array(__CLASS__, 'is_closed_shortcode'));
			add_shortcode('opening_times', array(__CLASS__, 'opening_times_shortcode'));
		}

		public static function is_open_shortcode($atts, $content = null)
		{
			if( WPOTP()->is_open() )
				return apply_filters('the_content', $content);

			return;
		}

		public static function is_closed_shortcode($atts, $content = null)
		{
			if( WPOTP()->is_closed() )
				return apply_filters('the_content', $content);

			return;
		}

		public static function opening_times_shortcode($atts, $content = null)
		{
			global $wpotp_options;

			extract(shortcode_atts(array(
				'show_closed_days' => false,
			), $atts));
			
			if( isset($wpotp_options['settings']['display']) && in_array($wpotp_options['settings']['display'], ['12', '24']) ):
				$display = $wpotp_options['settings']['display'];
			else:
				$display = '24';
			endif;

			$dows = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
			ob_start();
			?>
			<dl itemscope itemtype="http://schema.org/LocalBusiness">
				<?php foreach( $dows as $day ): ?>
					<?php if( isset($wpotp_options[$day]) && !empty($wpotp_options[$day]) ): ?>
						<dt><?php echo ucwords($day); ?></dt>
						<?php foreach( $wpotp_options[$day] as $slot ): ?>
							<dd>
								<time itemprop="openingHours" datetime="<?php echo substr(ucwords($day), 0, 2); ?> <?php echo $slot['open']; ?>-<?php echo $slot['close']; ?>">
									<?php
									if( $display == '24' ):
										echo $slot['open']." - ".$slot['close'];
									elseif( $display == '12' ):
										$this_open = strtotime($slot['open']);
										$this_close = strtotime($slot['close']);
										echo date('h:ia', $this_open)." - ".date('h:ia', $this_close);
									endif;
									?>
								</time>
							</dd>
						<?php endforeach; ?>
					<?php elseif( $show_closed === true || $show_closed === 'true' ): ?>
						<dt><?php echo ucwords($day); ?></dt>
						<dd>Closed</dd>
					<?php endif; ?>
				<?php endforeach; ?>
			</dl>
			<?php
			return ob_get_clean();
		}
	}
}
new WPOTP_Shortcodes();