<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WPOTP_Admin_Pages' ) )
{
	class WPOTP_Admin_Pages
	{
		public function __construct()
		{
			add_action('admin_menu', array($this, 'add_options_link'), 10);
		}

		public function add_options_link()
		{
			global $wpotp_settings_page;

			$wpotp_settings_page = add_submenu_page(
				'options-general.php',
				__( 'WP Opening Times Pro Settings', 'wpotp' ),
				__( 'Opening Times', 'wpotp' ),
				'manage_options',
				'wpotp-settings', array($this, 'display_options_page')
			);
		}

		function display_options_page() {
			ob_start();
			global $wpotp_options;
			?>
			<div class="wrap" id="js__wpotp-page">
				<div class="wpotp-container">
					<div class="wpotp-row">
						<div class="wpotp-col-full">
							<h1 class="wpotp-page-title"><?php echo __('WP Opening Times Pro Settings', 'wpotp'); ?></h1>
						</div>
					</div>
				</div>

				<?php //settings_errors(); ?>

				<div class="wpotp-container">
					<div class="wpotp-row">
						<div class="wpotp-col-large">
							<script>var wpotp_interval = <?php echo ( isset($wpotp_options['settings']['interval']) ? $wpotp_options['settings']['interval'] : '30' ); ?></script>
							<form method="post" action="options.php">
								<?php settings_fields( 'wpotp_settings' ); ?>
								<div class="wpotp-tabs">
									<nav class="wpotp-tabs__nav">
										<a class="wpotp-tabs__nav__item js__wpotp-nav-link active" href="#times">
											<div class="wpotp-tabs__nav__item__inner">
												<span class="dashicons dashicons-clock"></span>
												<span class="wpotp-tabs__nav__item__text">Opening Times</span>
											</div>
										</a>
										<?php /* ?>
										<a class="wpotp-tabs__nav__item js__wpotp-nav-link" href="#holidays">
											<div class="wpotp-tabs__nav__item__inner">
												<span class="dashicons dashicons-calendar-alt"></span>
												<span class="wpotp-tabs__nav__item__text">Holidays</span>
											</div>
										</a>
										<?php */ ?>
										<a class="wpotp-tabs__nav__item js__wpotp-nav-link" href="#settings">
											<div class="wpotp-tabs__nav__item__inner">
												<span class="dashicons dashicons-admin-settings"></span>
												<span class="wpotp-tabs__nav__item__text">Settings</span>
											</div>
										</a>
									</nav>
									<div id="times" class="wpotp-tabs__content active js__wpotp-nav-content">
										<?php do_settings_sections( 'wpotp_settings_times'); ?>
									</div>
									<?php /* ?>
									<div id="holidays" class="wpotp-tabs__content js__wpotp-nav-content">
										<?php do_settings_sections( 'wpotp_settings_holidays'); ?>
									</div>
									<?php */ ?>
									<div id="settings" class="wpotp-tabs__content js__wpotp-nav-content">
										<?php do_settings_sections( 'wpotp_settings_settings'); ?>
									</div>
								</div>
								<div class="wpotp-submit"><?php submit_button(); ?></div>
							</form>
						</div>
						<div class="wpotp-col-small">
							<div class="wpotp-advert">
								<img src="<?php echo WPOTP_PLUGIN_URL; ?>/assets/img/wpotp-logo.png" width="500" height="500">
							</div>
							<nav class="wpotp-links">
								<a href="http://wearewoolf.com/wp-opening-times-pro/">Plugin Website &amp; Docs</a>
								<a href="http://wearewoolf.com">Team Woolf Website</a>
							</nav>
						</div>
					</div>
				</div>
			</div>
			<?php
			echo ob_get_clean();
		}
	}
}

new WPOTP_Admin_Pages;