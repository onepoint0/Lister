<?php

/*
 * Plugin Name: Ajax Lister
 * Description: Lists a bunch of docs and download them - ajax version!
 * Version: 1.0
 * Author: Clare Ivers
 */

 //Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) { exit; }

// global cos it's used in wp_enqueue_scripts for dynamic css and in the pagination in the lister table
$bkgrd_opacity = 0.75;
$bkgrd_opacity_int = 75;

// assets
require_once(plugin_dir_path(__FILE__).'includes/lister-scripts.php' );

// shortcode for displaying docs lists in posts
require_once(plugin_dir_path(__FILE__).'includes/lister-shortcodes.php' );

// add tags and cats for attachments
require_once(plugin_dir_path(__FILE__).'includes/lister-taxonomies.php' );

// admin settings
require_once(plugin_dir_path(__FILE__).'includes/lister-settings.php' );

// function for ajax tag completion in search box
require_once(plugin_dir_path(__FILE__).'includes/lister-ajax.php' );

// function for ajax tag completion in search box
require_once(plugin_dir_path(__FILE__).'includes/lister-publication-date.php' );
