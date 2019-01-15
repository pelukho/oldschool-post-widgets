<?php


class Oldschool_Popular_Post_Widget extends WP_Widget {

	public function init(){
		add_action( 'widgets_init', function(){
			return register_widget( "Oldschool_Popular_Post_Widget" );
		});
	}

	/**
	 * Sets up a new Oldschool Popular Post widget instance.
	 *
	 * @since 2.8.0
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'oldschool-popular-post-widget',
			'description' => __( 'Show your popular posts in cool design', 'Oldschool-Popular-Post-Widgets' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'Oldschool_Popular_Post_Widget', __( 'Oldschool Popular Post Widget' ), $widget_ops );
		$this->alt_option_name = 'oldschool_popular_post_widget';
	}

	/**
	 * Outputs the content for the current Oldschool Popular Post widget instance.
	 *
	 * @since 2.8.0
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Oldschool Popular Post widget instance.
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Oldschool Popular Posts' );

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number ) {
			$number = 5;
		}
		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

		/**
		 * Filters the arguments for the Oldschool Popular Posts widget.
		 *
		 * @since 3.4.0
		 * @since 4.9.0 Added the `$instance` parameter.
		 *
		 * @see WP_Query::get_posts()
		 *
		 * @param array $args     An array of arguments used to retrieve the recent posts.
		 * @param array $instance Array of settings for the current widget.
		 */
		$r = new WP_Query( apply_filters( 'widget_posts_args', array(
			'posts_per_page'      => $number,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'meta_key' 			  => 'wpb_post_views_count',
			'orderby'  			  => 'meta_value_num',						
			'order' 			  => 'DESC'
		), $instance ) );

		if ( ! $r->have_posts() ) {
			return;
		}
		?>
		<?php echo $args['before_widget']; ?>
		<?php
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		$post_number = 1;
		?>		
		<?php foreach ( $r->posts as $popular_post ) : ?>
			<?php
			$post_title = get_the_title( $popular_post->ID );
			$title      = ( ! empty( $post_title ) ) ? $post_title : __( '(no title)' );
			?>
			<div class="popular-posts-widget__content-grid">
				<div class="popular-posts-widget__post-counter">
				<?php echo esc_attr($post_number); ?>
				</div>
				<a class="popular-posts-widget__link" href="<?php the_permalink( $popular_post->ID ); ?>" title = "<?php echo substr( strip_tags( $title ) , 0, 160); ?>" rel = "nofollow">
					<div class="popular-posts-widget__thumbnail svg-background-icon">
				<?php $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id( $popular_post->ID ) , 'thumbnail' ); ?>
						<?php if( $thumbnail ): ?>
							<img class="popular-posts-widget__thumbnail-image" src="<?php echo esc_attr( $thumbnail['0'] ); ?>" alt="<?php echo $title; ?>" width="100" height="69"/>
						<?php endif; ?>
					</div>
					<div class="popular-posts-widget__title">
						<h3><?php echo $title; ?></h3>
					</div>
				</a>
			</div>
		<?php $post_number++; endforeach; ?>		
		<?php
		echo $args['after_widget'];
	}

	/**
	 * Handles updating the settings for the current Oldschool Popular Posts widget instance.
	 *
	 * @since 2.8.0
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];

		return $instance;
	}

	/**
	 * Outputs the settings form for the Oldschool Popular Posts widget.
	 *
	 * @since 2.8.0
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
		<input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" /></p>
<?php
	}
}