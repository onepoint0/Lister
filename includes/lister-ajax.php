<?php  

/**
 * Ajax handler for tag search.
 *
 * 
 */

function wp_ajax_lister_tag_search() {

  $s = wp_unslash( $_GET['q'] );
	$input = preg_quote($s, '~'); // don't forget to quote input string!

	$settings = (array) get_option( 'cidl-lister-plugin-settings' );
	$data = $settings['search-tags'];

  // if $data is empty lister shows all tags, so get all tags
  if ( !isset( $data ) ) {
    $tags = get_tags(array(
      'hide_empty' => false
    ));
    $data = array();
    foreach ($tags as $tag) {
      array_push($data,$tag->name);
    }
  }

	$result = preg_grep('~' . $input . '~', $data);

	echo join( $result, "\n" );

	wp_die();
}

add_action('wp_ajax_nopriv_lister_tag_search', 'wp_ajax_lister_tag_search'); // autocompletion for non-logged-in users
add_action('wp_ajax_lister_tag_search', 'wp_ajax_lister_tag_search');

function wp_ajax_lister_doc_sort() {

  check_ajax_referer( 'listrsajxnncecode:)!!', 'nonce' );

  $permalink = $_POST['permalink'];

  // if it's the first sort the get request won't be set so use post
	$dir = sanitize_text_field( $_POST['dir'] );
 	$posts = cidl_get_wp_query($atts,$dir,$vars);

  $paged = get_option( 'paged' );
	if ( !is_null( $paged ) ) {
		$pagination = cidl_get_pagination( 1,$posts, $permalink,$dir );
	}

	ob_start(); // load_template doesn't work cos it somehow changes the $post var to an array (????)
	include(plugin_dir_path(__FILE__).'templates/lister-table-body.php');
	$postHTML = ob_get_clean();
  
	$content = array(
		'posts' => $postHTML,
		'pages' => $pagination,
		'url'	=> $url
	);
	echo json_encode($content);
	wp_die();

}

add_action( 'wp_ajax_nopriv_lister_doc_sort', 'wp_ajax_lister_doc_sort' );
add_action( 'wp_ajax_lister_doc_sort', 'wp_ajax_lister_doc_sort' );