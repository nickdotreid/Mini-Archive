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
	
	include_once('filters/taxonomy.php');
	include_once('filters/post2post.php');
	
	require_once('draw.php');
	require_once('metaboxes.php');

?>