<?php
                    
  if( $posts->have_posts() ) :  
    while( $posts->have_posts() ) : $posts->the_post(); 
?>

  <tr>
    <td>
        <input id="tag" class="dl-doc" type="checkbox" name="bulk-download[]" value="<?php  the_guid(); ?>" /> 
    </td>
    <td> 
        <a class="doc-loc" href=" <?php the_guid(); ?>" target="_blank" style="color: <?php echo $vars['linkcolour']; ?> "> <?php the_title(); ?> </a> 
    </td>
    <td>
        <?php 
            $result = array();
            $posttags = get_the_tags();
//error_log(print_r($posttags,true));
            if ( ( !empty( $posttags ) && !empty( $vars['tag_list' ] ) ) || $vars['showtags'] == 1 ) {
              foreach($posttags as $ptag) {
                if ( $vars['showtags'] == 1  ) {
                  $result[] = $ptag->name;
                } else {
                  foreach ( $vars['tag_list'] as $stag ) {
                    if ( $ptag->name == $stag ) {
                      $result[] = $ptag->name;
                      continue;
                    }
                  }
                }
              }
            } else {
              echo '-';
            }
            $res = implode(', ',$result);
            echo $res;
          ?>
        </td>
        <td> 

          <?php
// echo get_post_type();
// echo get_the_ID();
// echo get_post_meta( get_the_ID(), 'publication_date')[0];
// echo ' / ';
// echo get_the_date();
            $pubdate = get_post_type() == 'attachment' ? get_post_meta( get_the_ID(), 'publication_date')[0] : get_the_date();
            if (isset($pubdate)) {
              echo date($vars['dateformat'],strtotime($pubdate));
            }
          ?> 
        </td>
      <tr>

    <?php endwhile; ?>
<?php else: ?>
    <tr><td></td><td> No documents found.</td></tr>
<?php endif ?>
