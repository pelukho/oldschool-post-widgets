<?php

/* ---------- Oldschool Post By Tags Widget ---------- */

class Oldschool_Post_By_Tags_Widget extends WP_Widget {
	// init of class
	public function init(){
		add_action( 'widgets_init', function(){
			return register_widget ( 'Oldschool_Post_By_Tags_Widget');;
		});
	}

	public function __construct(){
		parent::__construct(
			'Oldschool_Post_By_Tags_Widget',
			'Oldschool Posts By Tags',
			array(
				'classname' => 'oldschool-posts-by-tags-widget',
				'description' => 'Works only on single.page - get posts by first-tag'
			));
	}

	public function widget( $args, $instance ) {
		if (is_singular() && has_tag() ) {
			
			$title = apply_filters( 'widget_title', $instance[ 'title' ]);
			$posts_per_page = $instance[ 'posts_per_page' ];
			echo $args[ 'before_widget' ];
			
			if( !empty( $title )) 
				echo $args['before_title'] . $title . $args['after_title'];
			
			global $post;
			$tags = get_the_tags( $post->ID );
			if( $tags && is_single() ){
				$first_tag = $tags[0]->term_id;
				$args = array( 
					 'post_status' 	=> 'publish',
					 'tag__in' 		=> array( $first_tag ),
					 'post__not_in'  => array( $post->ID ),
					 'showposts' 	=> $posts_per_page,
					 'orderby'		=> 'rand'
				);

				$q = new WP_Query( $args );
				$post_number = 1;
				if( $q->have_posts() ): 
				?>

					<div class="posts-by-tags-widget__post-block">
						<?php 
							if( $q->have_posts() ) : while( $q->have_posts() ) : $q->the_post(); 
						?>
						
						<div class="posts-by-tags-widget__content-grid">
							<div class="posts-by-tags-widget__post-counter">
								<?php 
									echo esc_attr( $post_number );
								?>
							</div>
							<a class="posts-by-tags-widget__link" href="<?php echo get_permalink($post->ID);?>" title="<?php echo substr(strip_tags(get_the_title()),0,160); ?>" rel="nofollow">
								<?php 
									$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'posts-by-tags-thumbnail' ); 
								?>							
								<div class="posts-by-tags-widget__thumbnail svg-background-icon">
									<img class="posts-by-tags-widget__thumbnail-image" src="<?php echo  $thumbnail['0'];?>" alt="<?php the_title();?>" width="100" height="69" />
								</div>							
								<div class="posts-by-tags-widget__title">
									<h3><?php echo get_the_title( $post->ID ); ?></h3>
								</div>
							</a>
						</div>
						<?php 
							$post_number++;
							endwhile; 
							echo '
								</div>
							</div>';
							endif;									
					endif;
					wp_reset_postdata();
			}
							
		} /* end "if has tag string" */
	}
	
	public function form ( $instance ) {
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$posts_per_page = isset( $instance['posts_per_page'] ) ? absint( $instance['posts_per_page'] ) : 5;
		?>

		<p>
			<label for="<?php echo $this -> get_field_id ( 'title' ); ?>">Заголовок</label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'posts_per_page' ); ?>">Количество постов:</label>
			<input id="<?php echo $this->get_field_id( 'posts_per_page' ); ?>" name="<?php echo $this->get_field_name( 'posts_per_page' ); ?>" type="text" value="<?php echo $posts_per_page ? esc_attr( $posts_per_page ) : '5'; ?>" size="3" />
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( !empty ( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['posts_per_page'] = ( is_numeric( $new_instance['posts_per_page'] ) ) ? $new_instance['posts_per_page'] : '5'; 
		return $instance;
	}
}