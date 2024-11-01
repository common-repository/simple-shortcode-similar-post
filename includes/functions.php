<?php
define('SSSP_DEBUG',false);

/**
 * debug script
 */
function sssp_mylog( $message , $filename = 'debug.txt' ){
	if(SSSP_DEBUG){
		if( !is_string($message) ){
			$message = print_r( $message , true );
		}
		$message = date_i18n('Y-m-d H:i:s') . "\t" . $message . "\n";
		$log_file = dirname(__FILE__) . '/' . $filename;
		$fp = fopen( $log_file , 'a' );
		fwrite( $fp , $message );
		fclose( $fp );
	}
}

/**
 * register tiny mce button
 */
function sssp_register_tinymce_button( $buttons ) {
     array_push( $buttons, "ssspost" );
     return $buttons;
}

/**
 * register tiny mce button
 */
function sssp_add_tinymce_button( $plugin_array ) {
     $plugin_array['sssp_script'] = plugin_dir_url( __FILE__ ). 'lib/js/sssp_editor.js';
     return $plugin_array;
}

/**
 * register tiny mce button
 */
function sssp_tinymce_button() {
     if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
          add_filter( 'mce_buttons', 'sssp_register_tinymce_button' );
          add_filter( 'mce_external_plugins', 'sssp_add_tinymce_button' );
     }
}