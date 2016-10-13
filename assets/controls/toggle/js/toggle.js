(function() {

    jQuery( function( $ ){
        $( document ).on( 'change', '.toggle-checkbox', function( e ){
            var clicked     = $( this ),
                parent      = clicked.closest( '.evenote-section-content' ),                
                toggleAll   = parent.find( '[data-toggle-all="true"]' ),
                allcount    = parent.find( '.evenote-control .switch > input' ).not( toggleAll ).length,
                tottlecount = parent.find( '.evenote-control .switch > input:checked' ).not( toggleAll ).length;

            if( clicked.data('value') ){
                clicked.prop( 'checked', true );
                clicked.data('value', false );
            }
            if( clicked.is(':checked') ){
                clicked.parent().addClass( 'active' );
                if( allcount === tottlecount ){
                   toggleAll.prop( 'checked', true ).parent().addClass( 'active' );
                }

            }else{
                clicked.parent().removeClass( 'active' );
                if( toggleAll.length ){
                    toggleAll.prop( 'checked', false ).parent().removeClass( 'active' );
                }
            }

        } );
        $( document ).on( 'evenote.init', function() {
            $('.evenote-control .toggle-checkbox').trigger('change');
        });
        $( document ).on('change', '[data-toggle-all="true"]', function(e){
            var clicked = $( this ),
                parent = clicked.closest( '.evenote-section-content' );

            parent.find('.evenote-control .switch > input').not( this ).prop('checked', this.checked ).trigger('change');
        });

    });



})( jQuery );