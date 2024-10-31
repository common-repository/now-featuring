<?php

/**
 * Class for loading the featured of the Now Featuring Wordpress plugin
 */
class NowFeaturing {

	/**
	 * Constructor - Adding actions, fiters & registration hooks
	 */
	function __construct() {
		
		// activation & deactivation
		register_activation_hook( NF_PLUGIN_FILE, array( 'NowFeaturing', 'activation' ) );
		register_activation_hook( NF_PLUGIN_FILE, array( 'NowFeaturing', 'deactivation' ) );

		add_action( 'init', array( 'NowFeaturing', 'update_plugin' ), 20 );
		
		// page excerpt
		add_action('init', array( 'NowFeaturing', 'init_page_excerpt_and_taxonomy' ));
		
		// assets
		add_action( 'wp_head',  array( 'NowFeaturingAssets', 'register_assets' ) );
		
		// shortcodes
		add_action( 'init', array( 'NowFeaturing', 'register_shortcodes' ) );
		
		// widget
		add_action( 'widgets_init', array( 'NowFeaturingWidget', 'register_widget' ) );
		
		// admin actions & filters
		if ( is_admin() ){
			add_action( 'admin_head',  array( 'NowFeaturingAssets', 'register_admin_assets' ) );
		}
	}

	/*
	 * activation - Plugin activation... for crons & templates etc.
	 */
	public static function activation() {
		update_option( 'nf_option_version', NF_PLUGIN_VERSION );
	}

	/*
	 * deactivation - Plugin deactivation... clean up cron, templates etc.
	 */
	public static function deactivation() {
		// nothing to undo
	}

	/*
	 * update_plugin - update the plugin
	 */
	public static function update_plugin() {
		$option = get_option( 'nf_option_version' );
		if ( $option != NF_PLUGIN_VERSION ) {
			update_option( 'nf_option_version', NF_PLUGIN_VERSION );
		}
	}

	/*
	 * Register shortcodes - for rendering on the front end
	 */
	public static function register_shortcodes() {
		add_shortcode( 'now_featuring', array( 'NowFeaturingShortcodes', 'now_featuring' ) );
	}
	
	/*
	 * init_page_excerpt - adds excerpt, tag & category support to the 'page' post_type
	 */
	public static function init_page_excerpt_and_taxonomy() {
		if(function_exists("add_post_type_support")){
			add_post_type_support( 'page', 'excerpt' );
		}
		
		register_taxonomy_for_object_type('post_tag', 'page');
		register_taxonomy_for_object_type('category', 'page');
	}
}

// initiate loading process
new NowFeaturing();

?>