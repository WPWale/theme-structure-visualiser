jQuery('document').ready(function($){
    
    var template = $('<div class="tsv-template-path header" />');
    
    template.html(tsv_header_filename.path);
    
    $("header.site-header").prepend(template);
    
});