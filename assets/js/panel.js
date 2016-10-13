(function( $ ) {

    $( document ).on( 'click', '.evenote-tab-trigger', function( e ){
        e.preventDefault();
        var clicked  = $( this ),
            target   = $( clicked.attr('href') ),
            wrapper  = clicked.closest('.evenote-panel-inside'),
            tabs     = wrapper.find('> .evenote-panel-tabs').children(),
            sections = wrapper.find('> .evenote-sections').children();

        tabs.attr('aria-selected', false );
        clicked.parent().attr('aria-selected', true );

        sections.attr('aria-hidden', true );
        target.attr('aria-hidden', false );

    });

})( jQuery );