<?php

/**
 * Class for managing plugin assets
 */
class NowFeaturingAssets {

	/**
	 * Register assets
	 */
	public static function register_assets() {
        
		// js
		wp_register_script( 'nf', plugins_url( 'assets/js/now_featuring.js', NF_PLUGIN_FILE ) );
        wp_enqueue_script( 'nf' );
        
        // css
        wp_register_style( 'nf', plugins_url( 'assets/css/now_featuring.css', NF_PLUGIN_FILE ) );
        wp_enqueue_style( 'nf' );
	}
    
    /**
	 * Register admin assets
	 */
	public static function register_admin_assets() {
        
		// js
		wp_register_script( 'nf_admin', plugins_url( 'assets/js/nf_admin.js', NF_PLUGIN_FILE ) );
        wp_enqueue_script( 'nf_admin' );
        
        // css
        wp_register_style( 'nf_admin', plugins_url( 'assets/css/nf_admin.css', NF_PLUGIN_FILE ) );
        wp_enqueue_style( 'nf_admin' );
	}
}

?>