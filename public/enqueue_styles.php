<?php
/* Adding styles on public fase*/
class Enqueue_Styles {
	private $version;
	public function __construct( $plugin_version ) {
		$this->version = $plugin_version; 
	}
	public function add_style(){		
		add_action( 'wp_enqueue_scripts', array( $this, 'load_oldschool_post_widgets_styles' ), 100 );		
	}
	public function load_oldschool_post_widgets_styles(){
		wp_enqueue_style( 
			'oldschool-popular-post', 
			plugins_url('/assets/popular-post.css', __FILE__) ,
			array(),
			$this->version
		);
		wp_enqueue_style( 
			'oldschool-posts-by-tags', 
			plugins_url('/assets/post-by-tags.css', __FILE__) ,
			array(),
			$this->version
		);	
	}
}