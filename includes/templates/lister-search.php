<?php
  global $bkgrd_opacity_int;
  global $bkgrd_opacity;
?>
<div id="lister-panel">
  <form id="cidl-lister-search-form" role="search" method="post" class="search-form" action="">
    <label id="cidl-tags-search-ajax-label">Search   </label>
      <span class="screen-reader-text">Search for:</span>
         <input id="tag" type="search" class="search-field lister-tag-search" placeholder="&hellip;by tag" name="cs" />    
      <!-- <input type="submit" class="search-submit cidl-tags-search-ajax" value="Search" /> -->
      <button type="submit" style="background-color: <?php echo $vars['linkcolour']; ?>;
                    filter: alpha(opacity=<?php echo $bkgrd_opacity_int; ?>); /* internet explorer */
                    -khtml-opacity: <?php echo $bkgrd_opacity; ?>;      /* khtml, old safari */
                    -moz-opacity: <?php echo $bkgrd_opacity; ?>;       /* mozilla, netscape */
                    opacity: <?php echo $bkgrd_opacity; ?>;           /* fx, safari, opera */ "> 
                    Search </button>
  </form>

  <table class="lister-table">

    <thead>
      <tr>
        <th>
          <input class="select-all" type="checkbox" name="download" />
        </th>
        <th><a href="#" id="title-sort" class="<?php echo $dir; ?>" style="color: <?php echo $vars['linkcolour']; ?>"><span>Document</span><span class="dashicons sorting-indicator"></span></a></th>
        <th>Tags</th>
        <th>Publication Date </th>
      </tr>
    </thead>

    <tbody class="lister-tbody">
      <?php include(plugin_dir_path(__FILE__).'lister-table-body.php'); ?> 
    </tbody>

  </table>

  <?php 

    if (!is_null($pagination)) {
      echo '<div id="lister-pagination">' . $pagination . '</div>';
    } 
  ?>

  <input id="lister-download-button" type="submit" value="Download" />
</div>

<?php wp_reset_postdata(); ?>

