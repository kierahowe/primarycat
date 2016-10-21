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

 /**
   * This class has the core functionality for this plugin.
   */
class PrimaryCategories { 

	/** 
	 * Start up the plugin by adding an init action
	 *
	 * @return void;
	 */
	function __construct(){
		//  Class created when plugin is inited - this constructor called from there
	   	add_action('init', array(&$this, 'init'));
	}

	/** 
	 * Setup all actions
	 *
	 * @return void;
	 */
	function init () { 
		//  
		add_action( 'admin_enqueue_scripts', array($this, 'enqueue_scripts'), 1);

		add_action( 'add_meta_boxes', array ($this, 'register_meta_boxes'));
		add_action( 'save_post', array($this,'save_pricat_data'), 10, 2 );

		add_action ( 'pre_get_posts', array ($this, 'getposts'));

	}

	/** 
	 * getposts add a meta query to the primary query when pricat is
	 * defined in the url query vars
	 *
	 * @param WP_Query $query the parameters for the query in a WP_Query object
	 *
	 * @return void;
	 */
	function getposts ($query) { 
		if (isset($_GET['pricat']) && $query->is_main_query() ) {
			$pricat = intval($_GET['pricat']);
		 	$query->set( 'meta_query', array(
				array(
					'key'     => 'primarycat',
					'value'   => $pricat,
					'compare' => '='
				)
			));
		}
	}

	/** 
	 * registers the media box for the post edit page
	 *
	 * @return void;
	 */
	function register_meta_boxes () {
	    add_meta_box( 
	    	'meta-box-id', 
	    	__( 'Primary Category', 'pricat' ), 
	    	array ($this, 'display_pricat_box'), 
	    	'post',
	    	'side'
	    );
	}

	/** 
	 * enqueue java, only on the edit post admin page
	 *
	 * @return void;
	 */
	function enqueue_scripts ($hook) {
		if ( 'post.php' == $hook ) { 
			wp_enqueue_script( 'jquery' );
		    wp_enqueue_script( 'custom-script', plugins_url('pricat-admin.js', __FILE__ ), array( 'jquery' ) );

		    // wp_enqueue_style( 'custom-style', plugins_url('dff-admin.css', __FILE__ ) );
		}
	}

	/** 
	 * display the primary cat list in the metabox on the post page
	 *
	 * @return void;
	 */
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

	/** 
	 * update the primarycat on save
	 *
	 * @return void;
	 */
	function save_pricat_data ( $post_id ){
		update_post_meta( $post_id, 'primarycat', intval( $_POST['primarycat'] ) );
	}
}

new PrimaryCategories ();

