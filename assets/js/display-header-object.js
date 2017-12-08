jQuery('document').ready(function($){
    
    // Create an HTML div with classes 'tsv-template-path ' and 'header'
    var template = $('<div class="tsv-template-path header" />');
    
    // Print the path
    template.html(tsv_header_filename.path);
    
    // Prepend the div to 'header.site-header'
    $("header.site-header").prepend(template);
    
});