<?php
/**
 * Plugin Name: Simple Shortcode Similar Post
 * Plugin URI: https://ruana.co.jp/simple-shortcode-similar-post
 * Description: This is a better way to view similar post
 * Version: 1.5.0
 * Author: Ruana LLC
 * Author URI: https://ruana.co.jp/
 * Text Domain: simple-shortcode-similar-post
 * Domain Path: /languages/
 *
 * Copyright 2018 Ruana LLC (email : info@ruana.co.jp)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Initialize plugin and all settings
 */
function sssp_init() {
	require_once( dirname( __FILE__ ) . '/includes/functions.php' );
	require_once( dirname( __FILE__ ) . '/includes/class-shortcode-simple-similar-post.php' );

	$ssspost = new SSSPost;
	add_action('wp_head',[ $ssspost, 'sssp_get_category_posts' ]);
	add_shortcode( 'ssspost', [ $ssspost, 'sssp_shortcode' ] );
	add_action( 'wp_enqueue_scripts', 'sssp_load_plugin_css' );

	sssp_tinymce_button();
}

/**
 * load text domain
 */
function sssp_load_plugin_textdomain(){
	load_plugin_textdomain('simple-shortcode-similar-post', false, basename(dirname(__FILE__)).'/languages/' );
}

/**
 * load sssp style sheet
 */
function sssp_load_plugin_css() {
	$plugin_url = plugin_dir_url( __FILE__ );
	wp_register_style( 'sssp_style', $plugin_url . 'includes/lib/css/sssp_style.css');
	wp_enqueue_style( 'sssp_style' );
}

add_action( 'plugins_loaded', 'sssp_init' );
add_action( 'plugins_loaded', 'sssp_load_plugin_textdomain' );
