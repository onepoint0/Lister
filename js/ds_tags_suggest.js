(function($) {

	$.fn.dsTagsSuggest = function() {

		var elem = $( this );
		var last;
		var tempID = 0;
//console.log('in func');
//console.log(elem);
		function split( val ) {
			return val.split( /,\s*/ );
		}
		function getLast( term ) {
			return split( term ).pop();
		}

		// don't navigate away from the field on tab when selecting an item
		elem.bind( "keydown", function( event ) {
			if ( event.keyCode === $.ui.keyCode.TAB && elem.autocomplete( "instance" ).menu.active ) {
				//$( this ).autocomplete( "instance" ).menu.active ) {
				event.preventDefault();
			}
		})
		.autocomplete({
			minLength: 2,
			source: function( request, response ) {
//console.log(request);
				term = request.term;
				var term;

				if ( last === request.term ) {
					response( cache );
					return;
				}

				term = getLast( request.term );

				$.get( ajax_object.ajax_url, //'http://localhost/tester/wp-admin/admin-ajax.php', 
				{
					action: 'lister_tag_search',
					tax: 'post_tag',
					q: term
				} ).done( function( data ) {
					var tagName;
					var tags = [];
//console.log(data);
					if ( data ) {
						data = data.split( '\n' );

						for ( tagName in data ) {
							var id = ++tempID;

							tags.push({
								id: id,
								label: data[tagName]
							});
						}

						cache = tags;
						response( tags );
					} else {
						response( tags );
					}
				} );

				last = request.term;
			// delegate back to autocomplete, but extract the last term
				// response( $.ui.autocomplete.filter(
				// 	projects, extractLast( request.term ) ) );
			},
			focus: function() {	// prevent value inserted on focus
				return false;
			},
			select: function( event, ui ) {

				var terms = split( this.value );
				// remove the current input
				terms.pop();
				// add the selected item
				terms.push( ui.item.value );
				// add placeholder to get the comma-and-space at the end
				terms.push( "" );
//				this.value = terms.join( ", " );
				this.value = terms.join( ", " );
				var selected_label = ui.item.label;
				var selected_value = ui.item.value;

				var labels = $('#labels').val();
				var values = $('#values').val();

				if(labels == "") {
					$('#labels').val(selected_label);
					$('#values').val(selected_value);
				} else {
					$('#labels').val(labels+","+selected_label);
					$('#values').val(values+","+selected_value);
				}

				return false;
			}
		});
		};
}( jQuery ) );
