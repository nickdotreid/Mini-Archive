<?php
   /*
   Plugin Name: Mini Archive
   Plugin URI: http://something at github
   Description: Create mini archives for post types on any page
   Version: 0.0.1
   Author: Nick Reid
   Author URI: http://nickreid.com
   License: GPL2
   */
	
	define( 'MINI_ARCHIVE_VERSION','0.0.1');
	define( 'MINI_ARCHIVE_IS_INSTALLED', 1 );
	
	define( 'MINI_ARCHIVE_PLUGIN_DIR', dirname( __FILE__ ) );
	define ( 'MINI_ARCHIVE_DB_VERSION', '1' );
	
	require_once('draw.php');
	require_once('metaboxes.php');
	
	/* Only load the component if BuddyPress is loaded and initialized. */
	function bp_mini_archive_init() {
		// Because our loader file uses BP_Component, it requires BP 1.5 or greater.
		if ( version_compare( BP_VERSION, '1.5', '>' ) )
			define( 'MINI_ARCHIVE_BP_IS_INSTALLED', 1 );
	}
	add_action( 'bp_include', 'bp_mini_archive_init' );

?>