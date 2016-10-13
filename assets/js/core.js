var EVENT_NOTIFY = {};

(function() {

    jQuery( document ).ready( function( $ ){
        jQuery( window ).load( function() {
            // main init
            $(document).trigger('evenote.init');
        });
    });

})( window );