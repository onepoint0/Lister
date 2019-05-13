<?php  

// apply tags to categories
function cidl_lister_add_categories_to_attachments() {
  register_taxonomy_for_object_type( 'category', 'attachment' );
}
add_action( 'init' , 'cidl_lister_add_categories_to_attachments' );

// apply tags to attachments
function cidl_lister_add_tags_to_attachments() {
  register_taxonomy_for_object_type( 'post_tag', 'attachment' );
}
add_action( 'init' , 'cidl_lister_add_tags_to_attachments' );
