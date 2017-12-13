jQuery( 'document' ).ready( function ( $ ) {

    // Check if 'tsv_header_filename' is defined
    if ( typeof tsv_header_filename != "undefined" ) {

	// Create an HTML div with classes 'tsv-template-path ' and 'header'
	var template = $( '<div class="tsv-template-path tsv-header" />' );

	// Print the path
	template.html( tsv_header_filename.path );

	// Prepend the div to 'header.site-header'
	$( "header.site-header" ).prepend( template );
    }
} );