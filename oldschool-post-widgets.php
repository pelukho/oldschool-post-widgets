<?php
/**
 * @package Oldschool Post Widgets
 * @version 1.0
 */
/*
Plugin Name: Oldschool Post Widgets
Description: This plugin add two post widgets: oldschool popular post widgets and post by tags widgets. All you need to do is install the plugin, and connect the necessary widgets in the site settings. This plugin was created only for WordPress "OldSchool" theme.
Author: Deni Wassulmaier
Version: 1.0
Text Domain: Oldschool-Post-Widgets
*/

// Die if get directly
if( !defined( 'WPINC' ) ){
	die;
}

/* ---------- Old School Post Widget ---------- */
// Define version
define( 'OPW_VERSION' , '1.0' );

// Add post views
require_once( plugin_dir_path( __FILE__ ) . '/inc/post-widget-view.php' );
$opw_add_vies = new Post_Widget_View();
$opw_add_vies->init();

// Add new custom widget
require_once( plugin_dir_path( __FILE__ ) . '/inc/oldschool_popular_post_widget.php' );
$opw_add_widget = new Oldschool_Popular_Post_Widget();
$opw_add_widget->init();

// Add new custom widget
require_once( plugin_dir_path( __FILE__ ) . '/inc/oldschool_popular_post_widget_by_tags.php' );
$opw_add_widget_by_tags = new Oldschool_Post_By_Tags_Widget();
$opw_add_widget_by_tags->init();


/* Public styles */
require_once( plugin_dir_path( __FILE__ ) . '/public/enqueue_styles.php' );
$opw_add_styles = new Enqueue_Styles( OPW_VERSION );
$opw_add_styles->add_style();