<?php
# http://kovshenin.com/2012/the-wordpress-settings-api/
# http://codex.wordpress.org/Settings_API

add_action( 'admin_menu', 'cidl_lister_admin_menu' );
function cidl_lister_admin_menu() {
  $page = add_options_page( __('Lister Options', 'cidl_domain' ), __('Lister Plugin Options', 'cidl_domain' ), 'manage_options', 'cidl-lister-plugin', 'cidl_lister_options_page' );
  add_action("load-$page", 'cidl_add_lister_help_tabs');
}
add_action( 'admin_init', 'cidl_lister_init' );

function cidl_lister_init() {
  
  /* 
	 * http://codex.wordpress.org/Function_Reference/register_setting
	 * register_setting( $option_group, $option_name, $sanitize_callback );
	 * The second argument ($option_name) is the option name. It’s the one we use with functions like get_option() and update_option()
	 * */
  	# With input validation:
  	# register_setting( 'my-settings-group', 'my-plugin-settings', 'my_settings_validate_and_sanitize' );    
  register_setting( 'cidl-lister-settings-group', 'cidl-lister-plugin-settings','cidl_lister_settings_validation' );
	
  	/* 
	 * http://codex.wordpress.org/Function_Reference/add_settings_section
	 * add_settings_section( $id, $title, $callback, $page ); 
	 * */	 
  add_settings_section( 'section-1', __( 'Data', 'cidl_domain' ), 'cidl_lister_list_cb', 'cidl-lister-plugin' );
  add_settings_section( 'section-2', __( 'Display', 'cidl_domain' ), 'cidl_lister_display_cb', 'cidl-lister-plugin' );         

	/* 
	 * http://codex.wordpress.org/Function_Reference/add_settings_field
	 * add_settings_field( $id, $title, $callback, $page, $section, $args );
	 * */
  add_settings_field( 'post-type', __( 'Post Type', 'cidl_domain' ), 'cidl_lister_post_type_cb', 'cidl-lister-plugin', 'section-1' );
  add_settings_field( 'mime-type', __( 'MIME Type', 'cidl_domain' ), 'cidl_lister_mime_type_cb', 'cidl-lister-plugin', 'section-1' );
  add_settings_field( 'post-status', __( 'Post Status', 'cidl_domain' ), 'cidl_lister_post_status_cb', 'cidl-lister-plugin', 'section-1' );
  add_settings_field( 'pagination', __( 'Pagination', 'cidl_domain' ), 'cidl_lister_pagination_cb', 'cidl-lister-plugin', 'section-1' );
  add_settings_field( 'tags', __( 'Tags to List', 'cidl_domain' ), 'cidl_lister_tags_cb', 'cidl-lister-plugin', 'section-1' );
  add_settings_field( 'show-tags', __( 'List tags not selected in "Tags to List"', 'cidl_domain' ), 'cidl_lister_show_tags_cb', 'cidl-lister-plugin', 'section-1' );
  add_settings_field( 'link-colour', __( 'Colour of the links in the list', 'cidl_domain' ), 'cidl_lister_link_colour_cb', 'cidl-lister-plugin', 'section-2' );
  add_settings_field( 'date-format', __( 'Date column format', 'cidl_domain' ), 'cidl_lister_date_format_cb', 'cidl-lister-plugin', 'section-2' );

}
/* 
 * THE ACTUAL PAGE 
 * */
function cidl_lister_options_page() {

?>
  <div class="wrap">
    <h2><?php _e('Lister Options', 'cidl_domain'); ?></h2>
    <form action="options.php" method="POST">
      <?php settings_fields('cidl-lister-settings-group'); ?>
      <?php do_settings_sections('cidl-lister-plugin'); ?>
      <?php submit_button(); ?>
    </form>
  </div>
<?php 
}

/*
* THE SECTIONS
* Hint: You can omit using add_settings_field() and instead
* directly put the input fields into the sections.
* */
function cidl_lister_list_cb() {
	_e( 'The following settings are filters for the list that is output from your shortcode. All settings are optional', 'cidl_domain' );
}

function cidl_lister_display_cb() {
	_e( 'The following settings are for the look and feel of the list. All settings are optional', 'cidl_domain' );
}

/*
* THE FIELDS
* */

function cidl_lister_generate_dropdown($data, $field, $value,$vals) {

  if (empty($vals)) {
    $vals = $data;
  }

  $output  = '';
  $output .= '<select name="cidl-lister-plugin-settings['.$field.']" id="cidl-lister-plugin-settings['.$field.']">';
  $output .= '<option class="post-type" value=""></option>';
  
  foreach ($data as $key => $d) {
    $selected = $value == $vals[$key] ? 'selected="selected"' : '';
    $output .= '<option '.$selected.' class="post-type" value="' . $vals[$key] . '">'.$d.'</option>';
  }

  $output .= '</select>';

  return $output;
}

function cidl_lister_post_type_cb() {
	
	$settings = (array) get_option( 'cidl-lister-plugin-settings' );
	$field = 'post-type';
	$value = esc_attr( $settings[$field] );

  $post_types = get_post_types();

  $output  = cidl_lister_generate_dropdown($post_types,$field,$value,'');
  $output .= '<p class="description">The post type to list. Leave blank to list all.</p>';
      
  echo $output;
}

function cidl_lister_mime_type_cb() {
	
	$settings = (array) get_option( 'cidl-lister-plugin-settings' );
	$field = "mime-type";
	$value = esc_attr( $settings[$field] );

  $mime_types = get_allowed_mime_types();
  sort($mime_types);

  $output  = cidl_lister_generate_dropdown($mime_types,$field,$value,'');
  $output .= '<p class="description">For attachments, the MIME type to list. Leave blank to list all.</p>';
  
  echo $output;
}

function cidl_lister_post_status_cb() {
	
	$settings = (array) get_option( 'cidl-lister-plugin-settings' );
	$field = 'post-status';
	$value = esc_attr( $settings[$field] );

  $post_statuses = get_post_statuses();

  $output  = cidl_lister_generate_dropdown($post_statuses,$field,$value,'');
  $output .= '<p class="description">The post status to list. Leave blank to list all.</p>';
    
  echo $output;
}

function cidl_lister_pagination_cb() {
	
	$settings = (array) get_option( 'cidl-lister-plugin-settings' );
	$field = 'pagination';
  $value = esc_attr( $settings[$field] );

  $output = '<input type="text" name="cidl-lister-plugin-settings[' . $field . ']" value="' . $value . '" />';
  $output .= '<p class="description">The number of items to list per page. Leave blank to turn pagination off.</p>';

  echo $output;
}

function cidl_lister_tags_cb() {
    
  $settings = (array) get_option( 'cidl-lister-plugin-settings' );
  $field = 'search-tags';
  $values = $settings[$field];
  $taxonomy = 'post_tag';
  $tags = get_terms( $taxonomy, array( 'hide_empty' => false ) );
?>

  <div class="lister-postbox">
    <div class="lister-inside">
      <div id="lister-tags-box">
        <ul>
          <?php 
            if (!empty($tags)) {
              foreach ($tags as $key => $tag) { ?>
                <li id="tag-<?php echo $key; ?>" class="lister-tag">
                  <label>
                    <input  value="<?php echo $tag->name; ?>" 
                            type="checkbox" 
                            name="cidl-lister-plugin-settings[<?php echo $field; ?>][<?php echo $key; ?>]" <?php checked( $tag->name, $values[$key], true ) ?>> 
                            <?php echo $tag->name; ?>
                  </label>
                </li>
                <?php
              } 
            } else { ?>
                <p>Create some tags to populate this list. </p>
              <?php
            } ?>
        </ul>
      </div> <!-- END #lister-tags-box -->
    </div> <!-- END .lister-inside -->
  </div> <!-- END .lister-postbox -->
  <p class="description"> <?php echo _e('The tags to list. Leave blank to list all.', 'cidl_domain') ?> </p>

  <?php 
}

function cidl_lister_show_tags_cb() {
    
  $settings = (array) get_option( 'cidl-lister-plugin-settings' );
  $field = 'show-tags';
  $value = esc_attr( $settings[$field] );
  $checked = ( $value == 1 ? ' checked' : '' );

  echo  '<input type="checkbox" name="cidl-lister-plugin-settings[' . $field . ']" value="1"' . $checked . '/>';

}

function cidl_lister_date_format_cb() {
	
	$settings = (array) get_option( 'cidl-lister-plugin-settings' );
	$field = 'date-format';
  $value = esc_attr( $settings[$field] );

  $date_options = array('dd/mm/yyyy','dd/mm/yy','mm/dd/yyyy','mm/dd/yy','yyyy/mm/dd','yy/mm/dd');
  $value_options = array('d/m/Y','d/m/y','m/d/Y','m/d/y','Y/m/d','y/m/d',);

  $output = cidl_lister_generate_dropdown($date_options, $field, $value,$value_options);
  $output .= '<p class="description">The format for the list\'s date column. By default, this will be dd/mm/yyyy.</p>';

  echo $output;
}

function cidl_lister_orderby_cb() {
	
	$settings = (array) get_option( 'cidl-lister-plugin-settings' );
	$field = 'orderby';
  $value = esc_attr( $settings[$field] );

  $output = '<input type="text" name="cidl-lister-plugin-settings[' . $field . ']" value="' . $value . '" />';
  $output .= '<p class="description">The column to order the list by. By default, this will be the post type\'s date column.</p>';

  echo $output;
}

function cidl_lister_link_colour_cb() {

	$settings = (array) get_option( 'cidl-lister-plugin-settings' );
  $field = 'link-colour';
  $value = esc_attr( $settings[$field] );

  $output = '<input type="text" name="cidl-lister-plugin-settings[' . $field . ']" value="' . $value . '" />';
  $output .= '<p class="description">The colour of links in the list. Can be either a colour name or a HEX value.</p>';
    
  echo $output;
}

/*
* INPUT VALIDATION:
* */
function cidl_lister_settings_validation( $input ) {

  $output['post-type']    = sanitize_text_field( $input['post-type']);
  $output['mime-type']    = sanitize_text_field( $input['mime-type']);
  $output['post-status']  = sanitize_text_field( $input['post-status']);
  $output['search-tags']  = array_map( 'sanitize_text_field', $input['search-tags'] );
  $output['show-tags']    = sanitize_text_field( $input['show-tags']);
  $output['link-colour']  = sanitize_text_field( $input['link-colour']);

  if (empty($input['date-format']) || preg_match('/^[mdyY]{1}\/[md]{1}\/[mdyY]{1}$/',$input['date-format'])) {
    $output['date-format'] = $input['date-format'];        
  } else {
    add_settings_error( 'cidl-lister-plugin-settings', 'invalid-date-format', 'The date format must be empty or selected from the dropdown.' );        
        
  }

  if (!( is_numeric($input['pagination']) || empty($input['pagination']))) {
    add_settings_error( 'cidl-lister-plugin-settings', 'invalid-pagination', 'The pagination value must be a number or blank.' );
  } else {
      $output['pagination'] = $input['pagination'];        
  }

  return $output;
}

/** Adds the help tabs to the current page */
function cidl_add_lister_help_tabs() {
  $screen = get_current_screen();
  
  $tabs = array(
		array(
			'title'    => 'All About Lister',
			'id'       => 'cidl-lister-about',
			'callback' => 'cidl_about_lister_tab'
        ),
        array(
			'title'    => 'Attachments',
			'id'       => 'cidl-lister-about-attachments',
			'callback' => 'cidl_lister_about_attachments_tab'
		),
		array(
			'title'    => 'Post and MIME Type',
			'id'       => 'cidl-lister-about-posttype',
			'callback' => 'cidl_lister_about_posttype_tab'
		),
		array(
			'title'    => 'Tags',
			'id'       => 'cidl-lister-about-tags',
			'callback' => 'cidl_lister_about_tags_tab'
		)
	);
	foreach($tabs as $tab) {
		$screen->add_help_tab($tab);
	}
//  $screen->set_help_sidebar('<a href="#">More info!</a>');
}
/**
 * Outputs the content for the 'More About Books' Help Tab
 */
 function cidl_about_lister_tab()  { 

  $output = '';
  $output .= '<p>
                  Lister is a plugin built primarily for listing attachments and providing bulk download functionality, 
                  but it has a few other capabilities too, such as listing any kind of post.         
              </p>';
  $output .= '<p> 
                  The settings for the Lister plugin are described in these tabs. Not all are described in detail here
                  as some are self explanitory and covered in the description at the foot of the setting itself.
              </p>';

  echo $output;
}

function cidl_lister_about_attachments_tab() {

  $output = '';
  $output .= '<p>
                  Lister is intended to list attachments from the media library. As such, it adds a custom field to the 
                  attachments page called \'Publication Date\'. When post type attachment is selected from the Post Types
                  setting, the date field shown on the front end will be this Publication Date field. For all other post
                  types, it will use the Wordpress built in publication date field of the post.
              </p>';

  echo $output;

}

function cidl_lister_about_posttype_tab()  { 

  $output = '';
  $output .= '<p>
                  The Post Type dropdown displays all post types including Custom Post Types. Lister is designed to 
                  be used for attachments but you can use it for any kind of post. 
              </p>';
  $output .= '<p> 
                  If the attachment post type is chosen, the list can be further narrowed down by selecting an 
                  entry from the MIME Type dropdown, for example, you might want to list only attachments of type PDF
                  in which case you would chose Post Type \'attachment\' and MIME Type \'application/pdf\'. 
              </p>';

  echo $output;
}

function cidl_lister_about_tags_tab()  { 
    
  $output = '';
  $output .= '<p>
                  The Tags check boxes are used to control which posts are listed. If a post has more than one tag
                  and only a subset of those tags is chosen, then only the chosen tags will be visible and searchable 
                  on the front end. 
              </p>';
  $output .= '<p> 
                  For example, if you have a document under two tags, \'Treviso\' and \'Bologna\',
                  but only Treviso is selected in the tags settings then only the tag Treviso will show up in the list
                  and only the tag Treviso will return any documents when the search box is used. 
              </p>';

  echo $output;
}