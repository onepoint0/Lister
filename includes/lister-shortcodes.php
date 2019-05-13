<?php  

function cidl_get_pagination( $paged, $posts, $base,$dir ) {

  $base = $base . '%_%/?order=' . $dir;

  $args = array(
    'base'      => $base,
    'format'    => 'page/%#%',
    'current'   => max( 1, $paged ),
    'total'     => $posts->max_num_pages,
    'prev_text' => '<',
    'next_text' => '>'
  ); 

  $pagination = '<div class="lister-pagination">';
  $pagination .= paginate_links( $args );  
  $pagination .= '</div>';    

  return $pagination;

}

function cidl_get_wp_query($atts,$dir,&$vars) {

  $settings = (array) get_option( 'cidl-lister-plugin-settings' );

  if (!empty($settings['pagination'])) {
    $vars['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
  }

  $vars['posttype']   = sanitize_text_field($settings['post-type']);
  $vars['posttype']   = $vars['posttype'] == null ? 'any' : $vars['posttype'];
  $vars['poststatus'] = sanitize_text_field($settings['post-status']);
  $vars['poststatus'] = $vars['poststatus'] == null ? 'any' : $vars['poststatus'];
  $vars['linkcolour'] = sanitize_text_field($settings['link-colour']);
  $vars['dateformat'] = sanitize_text_field($settings['date-format']);    
  $vars['dateformat'] = $vars['dateformat'] == null ? 'd/m/Y' : $vars['dateformat'];
  $vars['showtags']   = sanitize_text_field($settings['show-tags']); 

  $args = array(
    'post_type'         => $vars['posttype'], //sanitize_text_field($settings['post-type']),
    'post_mime_type'    => sanitize_text_field($settings['mime-type']),
    'post_status'       => $vars['poststatus'],
    'posts_per_page'    => sanitize_text_field($settings['pagination']),
    'paged'             => sanitize_text_field($vars['paged']),
    'orderby'           => 'title',
    'order'             => $dir,
    'lister_q'          => true,
  );

  if($_POST) {
    $tags = sanitize_text_field($_POST['cs']); 
    $vars['tag_list'] = explode(',',$tags);
  } else {
    $vars['tag_list'] = $settings['search-tags'];
  }

  $args['tag'] = $vars['tag_list'] ;

  $posts = new WP_Query( $args );

  return $posts;
}

function cidl_add_shortcode($atts) {

  $dir = isset( $_GET['order'] ) ? $_GET['order'] : 'ASC';
  $posts = cidl_get_wp_query($atts, $dir, $vars);

  $pagination = cidl_get_pagination( $vars['paged'], $posts, get_permalink( ), $dir);

  ob_start(); // load_template doesn't work cos it somehow changes the $post var to an array (????)
  include(plugin_dir_path(__FILE__).'templates/lister-search.php');
  $content = ob_get_clean();
  echo $content;

}

add_shortcode('lister', 'cidl_add_shortcode');
