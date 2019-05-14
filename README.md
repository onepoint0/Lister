# Lister
## Lister - List attachments and allow users to download them 
Contributors: onpoint0
Tags: attachments, posts, sort, download, list, ajax, jszip

# Description 

Lister was designed to list documents and provide download functionality for those documents. It uses Javascript libraries and AJAX to facilitate this.  Lister uses a shortcode[Lister] and a settings screen to display and format its data.

## Installation 

1. Unzip the download package
2. Upload `Lister` to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress

## How to use? 

Lister uses a short code to generate its document list. Just place "[lister]" in the text area of the page that you want the Lister list to appear in and lister will do the rest. Don't forget to configure the settings (see below) to make it display what you want.

## Plugin Settings 

Lister has a number of configurations under Admin > Settings > Lister Plugin Options:

**Post Type:** Lister was designed to list documents and allow them to be downloaded, however it allows you to choose any kind of post type you want. So you can list whichever posts are available in the dropdown, including Custom Post Types.

**MIME Type:** This field is only useful if you are listing attachments. It allows you to restrict the MIME Type of the attachments you are listing. Only want to list PDFs? Choose application/PDF from the dropdown.

**Post Status:** restrict the documents listed to a particular post status.

**Pagination:** Set the number of documents in each page of the list.

**Tags to List:** Restrict the list to only show documents in the selected tags. Leave blank to show documents from any tag.

**List tags not selected in "Tags to List":** The list has a column containing the tags for each document. If a document is in a selected "Tags to List" tag but it also has other tags that are not selected in that list, you can show those tags in the list by checking this checkbox. ie, if your document has tags "Jobs" and "Resume" but only Jobs is selected in "Tags to List" you can also display Resume by checking the box. 

**Colour of the links in the list:** The colour of the links and the buttons on the list. Can be a HEX value or the name of a colour recognisable by CSS. 

**Date Column Format:** The format of the date column in the list.

