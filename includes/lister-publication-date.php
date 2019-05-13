<?php  

function cidl_add_fields_metabox() {

  add_meta_box(
    'cidl_lister_pub_date',
    __('Publication Date'),
    'cidl_add_fields_callback',
    'attachment',
    'normal',
    'default'
  );
}

add_action('add_meta_boxes','cidl_add_fields_metabox');

function cidl_add_fields_callback($post) {

  wp_nonce_field( basename(__FILE__), 'cidl_lister_nonce' );
  $cidl_stored_meta = get_post_meta( $post->ID );
?> 

    <div class="cidl-lister-form wrap">
      <div class="form-group">
        <label for="publication_date"><?php esc_html_e('Publication Date', 'cidl_domain'); ?></label>
        <input  class="full" 
                type="date" 
                name="publication_date" 
                id="publication_date" 
                value="<?php if (!empty($cidl_stored_meta['publication_date'])) { echo $cidl_stored_meta['publication_date'][0]; } ?>">
      </div>

    </div>
  <?php  
}

function cidl_meta_save($post_id) {

  $is_autosave = wp_is_post_autosave( $post_id ); 
  $is_revision = wp_is_post_revision( $post_id );
  $is_valid_nonce = (isset($_POST['cidl_lister_nonce']) 
      && wp_verify_nonce( $_POST['cidl_lister_nonce'], basename(__FILE__)) ? true : false );

  if ($is_autosave || $is_revision || !$is_valid_nonce) {
      return;
  }

  if ($_POST['publication_date']) {
      update_post_meta( $post_id, 'publication_date', sanitize_text_field( $_POST['publication_date'] ) );
  }

}

add_action( 'edit_attachment', 'cidl_meta_save');