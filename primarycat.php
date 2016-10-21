<?php
/**
 * @package PrimaryCat Plugin
 * @version 1.0
 */
/*
Plugin Name: PrimaryCategories
Plugin URI: http://kierahowe.com
Description: Adds the ability to select a category as the primary
Author: Kiera Howe
Version: 1.0
Author URI: http://kierahowe.com
*/


class PrimaryCategories { 
	function __construct(){
		//  Class created when plugin is inited - this constructor called from there
	   	add_action('init', array(&$this, 'init'));
	}

	function init () { 
		add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'), 1);

		add_action( 'add_meta_boxes', array ($this, 'register_meta_boxes'));
		add_action( 'save_post', array($this,'save_pricat_data'), 10, 2 );

	}

	function register_meta_boxes () {
	    add_meta_box( 
	    	'meta-box-id', 
	    	__( 'Primary Category', 'pricat' ), 
	    	array ($this, 'display_pricat_box'), 
	    	'post',
	    	'side'
	    );
	}

	function enqueue_scripts ($hook) {
		if ('post.php' == $hook) { 
			wp_enqueue_script( 'jquery' );
		    wp_enqueue_script( 'custom-script', plugins_url('pricat-admin.js', __FILE__ ), array( 'jquery' ) );

		    // wp_enqueue_style( 'custom-style', plugins_url('dff-admin.css', __FILE__ ) );
		}
	}

	function display_pricat_box ($post) { 
		
		$primarycat = get_post_meta( $post->ID, 'primarycat', true );

		$args = array( 'hide_empty' => false,
						'show_option_none'   => "No Primary",
						'name' => 'primarycat',
						'id' => 'primarycat',
						'selected' => $primarycat
		);

		wp_dropdown_categories( $args );
	}

	function save_pricat_data ( $post_id ){
		print $_POST['primarycat'] . ' -> ' .$post_id;
		update_post_meta( $post_id, 'primarycat', intval( $_POST['primarycat'] ) );
	}
}

new PrimaryCategories ();

