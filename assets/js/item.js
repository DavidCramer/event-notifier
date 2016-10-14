var evenote_item_control_modal, evenote_item_control_modal_handler;
(function($){

    var current_item = null
        flush_current = false;

    evenote_item_control_modal = function( obj ){
        var template_ele = $('#' + obj.modal + '-tmpl'),
            template = Handlebars.compile( template_ele.html() ),
            data = {},
            state,
            html;

        if( null !== current_item && flush_current === false ){
            data = { config : current_item.data( 'config' ) };
            state = 'add';
        }else{
            current_item = null;
            flush_current = false;
            state = 'update';
            data = obj.trigger.data('default');
        }

        html = template( data );

        $('.evenote-modal-footer [data-state="' + state + '"]').remove();

        return html;
    }

    evenote_item_control_modal_handler = function( data, obj ){

        var item = create_item( obj.params.requestData.control, data.data ),
            target;
        if( null !== current_item ){
            target = current_item;
            current_item = null;
            target.replaceWith( item );
        }else {
            target = $('#' + obj.params.requestData.control);
            item.appendTo( target );
        }

        save_current_edit( $( '#' + obj.params.requestData.control ) );
    }

    var create_item = function( target, data ){

        var template_ele = $('#' + target + '-tmpl'),
            template = Handlebars.compile( template_ele.html() ),
            item = $( template( data )  );
        item.data( 'config', data );
        $(document).trigger('evenote.init');
        return item;
    }

    var save_current_edit = function ( parent ) {
        var holders;
        if( parent ){
            holders = $( parent );
        }else{
            holders = $( '.evenote-control-item' );
        }

        for( var i = 0; i < holders.length; i++ ){

            var holder = $( holders[ i ] ),
                input = $( '#' + holder.prop('id') + '-control' ),
                items = holder.find('.evenote-item'),
                configs = [];

                for( var c = 0; c < items.length; c++ ){
                    var item = $( items[ c ] );
                    configs.push( item.data('config') );
                }
            input.val( JSON.stringify( configs ) ).trigger('change');
        }
        $( document ).trigger('evenote.save');
    }

    $( document ).on( 'click', '.evenote-item-edit', function( ){
        var clicked = $( this ),
            control = clicked.closest('.evenote-control-item'),
            trigger = $('button[data-modal="' + control.prop('id') + '-config"]');

        current_item = clicked.closest('.evenote-item');
        flush_current = false;

        trigger.trigger('click');
    });

    $( document ).on( 'click', '.evenote-item-remove', function( ){
        var clicked = $( this ),
            control = clicked.closest('.evenote-control-item'),
            trigger = $('button[data-modal="' + control.prop('id') + '-config"]'),
            item = clicked.closest('.evenote-item');

        if( clicked.data('confirm') ){
            if( ! confirm( clicked.data('confirm') ) ){
                return;
            }
        }

        item.fadeOut( 200, function(){
            item.remove();
            save_current_edit( control );
        });
    });

    // clear edit
    $(window).on( 'modals.closed', function(){
        flush_current = true;
    });

    // init
    $(window).load(function () {
        $(document).on('evenote.init', function () {
            $('.evenote-control-item').not('._evenote_item_init').each( function(){
                var holder = $( this ),
                    input = $( '#' + holder.prop('id') + '-control' ),
                    data;

                try {
                    data = JSON.parse( input.val() );
                }catch (err) {

                }
                holder.addClass('_evenote_item_init');

                if( typeof data === 'object' && data.length ){
                    for( var i = 0; i < data.length; i++ ){
                        var item = create_item( holder.prop('id'), data[ i ] );
                        item.appendTo( holder );
                    }
                }
                holder.removeClass('processing');
            });
        });
    });
})(jQuery);
