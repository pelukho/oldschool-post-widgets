<?php

class Post_Widget_View {
	public function init(){
		add_action ( 'wp_head', array( $this, 'wpb_track_post_views' ) );
	}
	private function wpb_set_post_views( $postID ){
		$count_key = 'wpb_post_views_count';
		$count = get_post_meta( $postID, $count_key, true );
		if ( $count == '' ) {
			$count = 0;
			delete_post_meta( $postID, $count_key );
			add_post_meta( $postID, $count_key, '0' );
		} else {
			$count++;
			update_post_meta( $postID, $count_key, $count );
		}
	}
	private function wpb_get_post_views( $postID ){
		$count_key = 'wpb_post_views_count';
		$count = get_post_meta( $postID, $count_key, true);
		if ( $count == '' ) {
			delete_post_meta( $postID, $count_key );
			add_post_meta( $postID, $count_key, '0' );
			return "0 View";
		}
		return $count . 'Views';
	}
	public function wpb_track_post_views( $postID ){
		if ( !is_single() ) return;
		if ( empty( $postID )){
			global $post;
			$postID = $post->ID;
		}	
		$this->wpb_set_post_views( $postID );
	}

}