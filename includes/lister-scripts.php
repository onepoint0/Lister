<?php  

function cidl_enqueue_admin_scripts() {
  wp_enqueue_style( 'cidl_admin_style_css', plugins_url().'/lister-ajax/css/admin-style.css' );
}
add_action('admin_enqueue_scripts','cidl_enqueue_admin_scripts');

function lister_plugin_assets() {
  // for file download
  wp_enqueue_script( 'lister-3p-jszip',  plugins_url( '/lister-ajax/js/jszip/dist/jszip.min.js' ), array( 'jquery' ) );
  wp_enqueue_script( 'lister-3p-jszip-utils',  plugins_url( '/lister-ajax/js/jszip-utils-master/dist/jszip-utils.min.js' ), array( 'jquery' ) );
  wp_enqueue_script( 'lister-3p-filesaver',  plugins_url( '/lister-ajax/js/FileSaver.js-master/FileSaver.js' ), array( 'lister-3p-jszip', 'lister-3p-jszip-utils') );

  // for tag search
  wp_enqueue_script('lister-3p-autocomplete-js', plugins_url( '/lister-ajax/js/jquery-ui-1.12.1.custom/jquery-ui.js'), array('jquery'));
  wp_enqueue_style('lister-3p-autocomplete-css', plugins_url( '/lister-ajax/js/jquery-ui-1.12.1.custom/jquery-ui.css'));
  wp_enqueue_script('lister-wp-tags-suggest', plugins_url( '/lister-ajax/js/ds_tags_suggest.js'), array('jquery'));

  wp_enqueue_style( 'lister-style-css', plugins_url().'/lister-ajax/css/style.css' );
  wp_enqueue_style( 'dashicons' );

  /* set up ajax - wp_localize_script passes data variable name and wp ajax php script
    handles need to be the same for the script containing the ajax and the localize call  
  */
  wp_enqueue_script( 'lister-main-js', plugins_url().'/lister-ajax/js/main.js', array('jquery','jquery-ui-sortable','lister-wp-tags-suggest','lister-3p-filesaver') );

  wp_localize_script( 'lister-main-js'
                      ,'ajax_object'
                      ,array( 
                         'ajax_url' => admin_url( 'admin-ajax.php' )
                        ,'permalink' => get_permalink(intval($_REQUEST['id'])) 
                        ,'ajax_nonce' => wp_create_nonce('listrsajxnncecode:)!!')
                      ) 
  );

  $settings = (array) get_option( 'cidl-lister-plugin-settings' );
  $colour = wp_strip_all_tags( sanitize_text_field( $settings['link-colour'] ) );

//  $bkgrd_opacity_int & $bkgrd_opacity are set at the top of the main script so they can be used again later
  global $bkgrd_opacity_int;
  global $bkgrd_opacity;
  $custom_css = "
    .lister-pagination .page-numbers.current {
      background: {$colour};
      color: white;
      filter: alpha(opacity={$bkgrd_opacity_int});
      -khtml-opacity: {$bkgrd_opacity};
      -moz-opacity: {$bkgrd_opacity};
      opacity: {$bkgrd_opacity};
    }
    
    .lister-pagination a.page-numbers:hover {
      background: {$colour};
      color: white;
      filter: alpha(opacity={3});
      -khtml-opacity: 0.3;
      -moz-opacity: 0.3;
      opacity: 0.3;
    }
    a span.sorting-indicator::before {
      color: {$colour};
    }"; 

    wp_add_inline_style( 'lister-style-css', $custom_css ); 

}
add_action( 'wp_enqueue_scripts', 'lister_plugin_assets' );

