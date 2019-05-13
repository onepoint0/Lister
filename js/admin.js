function buildHTML(i,tagarr) {
    spantag = `
    <div id="` + i + `"> 
    <button type="button" class="ntdelbutton">
        <span class="dashicons-dismiss remove-tag-icon" aria-hidden="true"></span>
        <span class="screen-reader-text">Remove term: some</span>
    </button>
    &nbsp;
    <input type="hidden" name='cidl-lister-plugin-settings[search-tags][` + i + `]' value=` + tagarr[i] + ` />             
    <span class="lister-tags" name="tag`+i+`]">`
    + tagarr[i] +
    '</span>';

    return spantag;
}

function listerAddTags($,tags) {
    var fullspan = '';
    tagarr = tags.split(",");
    currtags = $("#lister-add-tag-list").html();
     idx = $( currtags + ' span.idx:last' ); //.attr('id');
 console.log('idx ' + idx);

//     for (i = 0; i < tagarr.length; i++) { 
//         fullspan += buildHTML(i,tagarr);
//     }
//     $('#lister-add-tag-list').html(currtags + fullspan);
//     $('#lister-tag-post_tag').val('');
    
}
    
jQuery( document ).ready(function($) {
    $('#lister-tag-post_tag').wpTagsSuggest();

    $('#lister-add').on('click',function(){
        tags = $('#lister-tag-post_tag').val();

        if (tags.length > 0) {
            listerAddTags($,tags);                    
        }        
    });

})
