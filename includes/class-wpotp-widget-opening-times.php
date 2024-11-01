<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WPOTP_Widget_Opening_Times' ) )
{
	class WPOTP_Widget_Opening_Times extends WP_Widget
	{

		public function __construct() {
			parent::__construct(
				'wpotp_opening_times',
				'Opening Times',
				array( 'description' => 'Display opening times to customers' ) 
			);
		}

		public function widget( $args, $instance ) {
			$title = apply_filters( 'widget_title', $instance['title'] );
			$show_closed = $instance['show_closed'];

			echo $args['before_widget'];
			if ( ! empty( $title ) )
				echo $args['before_title'] . $title . $args['after_title'];

			echo do_shortcode('[opening_times show_closed='.$show_closed.']');

			echo $args['after_widget'];
		}
		
		public function form( $instance ) {
			if ( isset( $instance[ 'title' ] ) ) {
				$title = $instance[ 'title' ];
			} else {
				$title = 'Opening Times';
			}
			if ( isset( $instance[ 'show_closed' ] ) ) {
				$show_closed = $instance[ 'show_closed' ];
			} else {
				$show_closed = 'false';
			}
			?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title</label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<p>
				Show Closed Days? <br>
				<label>Yes <input type="radio" name="<?php echo $this->get_field_name( 'show_closed' ); ?>" value="true" <?php if( $show_closed == 'true'): ?>checked="checked"<?php endif; ?>></label>
				<label>No <input type="radio" name="<?php echo $this->get_field_name( 'show_closed' ); ?>" value="false" <?php if( $show_closed == 'false'): ?>checked="checked"<?php endif; ?>></label>
			</p>
			<?php 
		}
	
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['show_closed'] = ( ! empty( $new_instance['show_closed'] ) ) ? strip_tags( $new_instance['show_closed'] ) : '';
			return $instance;
		}
	}
}

add_action( 'widgets_init', function () {
	register_widget('WPOTP_Widget_Opening_Times' );
});