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
} );