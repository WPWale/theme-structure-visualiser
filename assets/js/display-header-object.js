jQuery( 'document' ).ready( function ( $ ) {

    $( '.tsv-template-path' ).each( function () {
        if ( $( this ).hasClass( 'footer' ) ) {
            $( "footer.site-footer" ).prepend( $( this ) );
        } else if ( $( this ).hasClass( 'sidebar' ) ) {
            $( "#secondary.widget-area" ).prepend( $( this ) );
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