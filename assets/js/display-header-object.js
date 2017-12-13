jQuery( 'document' ).ready( function ( $ ) {

    $( '.tsv-template-path' ).each( function () {
	var tpl_path_div = $( this );
	if ( tpl_path_div.hasClass( 'footer' ) ) {
	    $( "footer.site-footer" ).prepend( tpl_path_div );
	} else if ( tpl_path_div.hasClass( 'sidebar' ) ) {
	    $( "#secondary.widget-area" ).prepend( tpl_path_div );
	} else {
	    var next_elem = tpl_path_div.next();
	    if ( !next_elem.hasClass( 'tsv-template-path' ) ) {
		next_elem.prepend( tpl_path_div );
	    }

	}
    } );

    // Check if 'tsv_header_filename' is defined
    if ( typeof tsv_header_filename != "undefined" ) {

	// Create an HTML div with classes 'tsv-template-path ' and 'header'
	var template = $( '<div class="tsv-template-path header" />' );

	// Print the path
	template.html( tsv_header_filename.path );

	// Prepend the div to 'header.site-header'
	$( "header.site-header" ).prepend( template );
    }

} );