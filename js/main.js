
function create_zip($){
    // get file urls
    var urls = Array();
    $("input.dl-doc:checked").each(function () {
        urls.push($(this).val());
    })
//console.log(urls);

    var zip = new JSZip();
    var count = 0;
    var zipFilename = "archive.zip";

    urls.forEach(function(url){
      var filename = url.substr(url.lastIndexOf("/")+1);
//console.log(filename);
      // loading a file and add it in a zip file
      JSZipUtils.getBinaryContent(url, function (err, data) {
         if(err) {
            throw err; // or handle the error
         }
         zip.file(filename, data, {binary:true});
         count++;

        if (count == urls.length) {
           zip.generateAsync({type:'blob'}).then(function(content) {
              saveAs(content, zipFilename);
           });
        }
      });
    });
};

function getURLFragment($,url) {
console.log('url in fragment call ' + url);
    
    if (url == '') {
//console.log('url null ' + url);

        page = $('.page-numbers').not('.prev').eq(0);
        url = page.attr('href');
    }
//    console.log('page');    
//console.log(page);
    frag = url.replace(window.location.origin,'');
//console.log(frag);
    return frag;
}

function getPageReference($,link) {

}

function load_posts($,link) {
console.log('load posts');
  direction = link.attr("class");

  if (link.attr('id') == 'title-sort') {
    direction = direction == 'ASC' ? 'DESC' : 'ASC';
  //   url = '';
  //   page = 1;
  // } else {
  //     // get page reference

  }
console.log('direction = ' + direction);
console.log(ajax_object.permalink);

  $.ajax({
    type : "POST",
    data : {
      action    : "lister_doc_sort", 
      dir       : direction, 
      nonce     : ajax_object.ajax_nonce,
      permalink : ajax_object.permalink,
    },
      dataType  : "json",
      url       : ajax_object.ajax_url,
      success : function(data){
//console.log($('.lister-tbody').html());
        // replace the table body and pagination 
        $('.lister-tbody').html(data['posts']);
        link.attr("class", direction);
        $('.lister-pagination').html(data['pages']);

//  console.log('data');
//  console.log(data);
            // set the url accordingly
            // frag = getURLFragment($,url);
            // history.pushState(null, null, frag);

    },
      error : function(jqXHR, textStatus, errorThrown) {
      console.log(jqXHR + " :: " + textStatus + " :: " + errorThrown);
    }
  });
};

jQuery( document ).ready(function($) {

  $( '.select-all' ).on('click', function () {
    $( this ).closest( 'table' ).find( ':checkbox' ).prop('checked', this.checked );
  });

  $('#tag').dsTagsSuggest();

  $('#lister-download-button').on('click',function(){
//console.log('click download button');
    create_zip($);
    $( '.diskscrape-table' ).find( ':checkbox' ).prop('checked', false );
  });

  $('#title-sort').click(function(e) {
    e.preventDefault();
    link = $(this);
    load_posts($,link);
  });

})
