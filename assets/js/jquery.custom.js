jQuery( 'document' ).ready( function ( $ ) {
    $( '.tsv-color-picker' ).wpColorPicker();
    
    if ( typeof tsv_custom_options != "undefined" ) {
        (".tsv-template-path").css("background-color", tsv_custom_options.background_colour);
        (".tsv-template-path").css("color", tsv_custom_options.font_colour);
    }
} );
    