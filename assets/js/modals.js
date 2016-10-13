(function($){
    
    var evenoteBackdrop = null,
        evenoteModals   = {},
        activeModals    = [],
        activeSticky    = [],
        pageHTML,
        pageBody,
        mainWindow;

    var positionModals = function(){

        if( !activeModals.length && !activeSticky.length ){
            return;
        }


        var modalId  = ( activeModals.length ? activeModals[ ( activeModals.length - 1 ) ] : activeSticky[ ( activeSticky.length - 1 ) ] ),
            windowWidth  = mainWindow.width(),
            windowHeight = mainWindow.height(),
            //modalHeight  = evenoteModals[ modalId ].body.outerHeight(),
            modalHeight  = evenoteModals[ modalId ].config.height,
            modalOuterHeight  = modalHeight,
            modalWidth  = evenoteModals[ modalId ].config.width,
            top          = 0,
            flickerBD    = false,
            modalReduced = false;

        evenoteModals[ modalId ].body.css( {
            height      : ''
        } );


        if( evenoteBackdrop ){ pageHTML.addClass('has-evenote-modal'); }




        // check modals for %
        if( typeof modalWidth === 'string' ){
            modalWidth = parseInt( modalWidth );
            modalWidth = windowWidth / 100 * parseInt( modalWidth );
        }
        if( typeof modalHeight === 'string' ){
            modalHeight = parseInt( modalHeight );
            modalHeight = windowHeight / 100 * parseInt( modalHeight );
        }       
        // top
        top = (windowHeight - modalHeight ) / 2.2;

        if( top < 0 ){
            top = 0;
        }

        if( modalHeight + ( evenoteModals[ modalId ].config.padding * 2 ) > windowHeight && evenoteBackdrop ){
            modalHeight = windowHeight;// - ( evenoteModals[ modalId ].config.padding * 2 );
            modalOuterHeight = '100%';
            if( evenoteBackdrop ){
                evenoteBackdrop.css( {
                    //paddingTop: evenoteModals[ modalId ].config.padding,
                    //paddingBottom: evenoteModals[ modalId ].config.padding,
                });
            }
            modalReduced = true;
        }
        if( modalWidth + ( evenoteModals[ modalId ].config.padding * 2 ) >= windowWidth ){
            modalWidth = '100%';
            if( evenoteBackdrop ){
                evenoteBackdrop.css( {
                    //paddingLeft: evenoteModals[ modalId ].config.padding,
                    //paddingRight: evenoteModals[ modalId ].config.padding,
                });
            }
            modalReduced = true;
        }

        if( true === modalReduced ){
            if( windowWidth <= 700 && windowWidth > 600 ){
                if( evenoteBackdrop ){
                    modalHeight = windowHeight - ( evenoteModals[ modalId ].config.padding * 2 );
                }
                modalWidth = windowWidth;
                modalOuterHeight = modalHeight - ( evenoteModals[ modalId ].config.padding * 2 );
                modalWidth = '100%';
                top = 0;
                if( evenoteBackdrop ){ evenoteBackdrop.css( { padding : evenoteModals[ modalId ].config.padding } ); }
            }else if( windowWidth <= 600 ){
                if( evenoteBackdrop ){ modalHeight = windowHeight; }
                modalWidth = windowWidth;
                modalOuterHeight = '100%';
                top = 0;
                if( evenoteBackdrop ){ evenoteBackdrop.css( { padding : 0 } ); }
            }
        }
        // set backdrop
        if( evenoteBackdrop && evenoteBackdrop.is(':hidden') ){
            flickerBD = true;
            evenoteBackdrop.show();
        }

        // title?
        if( evenoteModals[ modalId ].header ){
            if( evenoteBackdrop ){ evenoteBackdrop.show(); }
            modalHeight -= evenoteModals[ modalId ].header.outerHeight();
            evenoteModals[ modalId ].closer.css( {
                padding     : ( evenoteModals[ modalId ].header.outerHeight() / 2 ) - 3.8
            } );
            evenoteModals[ modalId ].title.css({ paddingRight: evenoteModals[ modalId ].closer.outerWidth() } );
        }
        // footer?
        if( evenoteModals[ modalId ].footer ){
            if( evenoteBackdrop ){ evenoteBackdrop.show(); }
            modalHeight -= evenoteModals[ modalId ].footer.outerHeight();
        }

        if( evenoteBackdrop && flickerBD === true ){
            evenoteBackdrop.hide();
            flickerBD = false;
        }

        // set final height
        if( modalHeight != modalOuterHeight ){
            evenoteModals[ modalId ].body.css( {
                height      : modalHeight
            } );
        }
        evenoteModals[ modalId ].modal.css( {
            width       : modalWidth    
        } );
        if( evenoteModals[ modalId ].config.sticky && evenoteModals[ modalId ].config.minimized ){
            var toggle = {},
                minimizedPosition = evenoteModals[ modalId ].title.outerHeight() - evenoteModals[ modalId ].modal.outerHeight();
            if( evenoteModals[ modalId ].config.sticky.indexOf( 'bottom' ) > -1 ){
                toggle['margin-bottom'] = minimizedPosition;
            }else if( evenoteModals[ modalId ].config.sticky.indexOf( 'top' ) > -1 ){
                toggle['margin-top'] = minimizedPosition;
            }
            evenoteModals[ modalId ].modal.css( toggle );
            if( evenoteModals[ modalId ].config.sticky.length >= 3 ){
                pageBody.css( "margin-" + evenoteModals[ modalId ].config.sticky[0] , evenoteModals[ modalId ].title.outerHeight() );
                if( modalReduced ){
                    evenoteModals[ modalId ].modal.css( evenoteModals[ modalId ].config.sticky[1] , 0 );
                }else{
                    evenoteModals[ modalId ].modal.css( evenoteModals[ modalId ].config.sticky[1] , parseFloat( evenoteModals[ modalId ].config.sticky[2] ) );
                }
            }
        }
        if( evenoteBackdrop ){
            evenoteBackdrop.fadeIn( evenoteModals[ modalId ].config.speed );

            evenoteModals[ modalId ].modal.css( {
                top   : 'calc( 50% - ' + ( evenoteModals[ modalId ].modal.outerHeight() / 2 ) + 'px)',
                left   : 'calc( 50% - ' + ( evenoteModals[ modalId ].modal.outerWidth() / 2 ) + 'px)',
            } );
            setTimeout( function(){
                evenoteModals[ modalId ].modal.addClass( 'evenote-animate' );
            }, 10);

        }

        return evenoteModals;
    }

    var closeModal = function( lastModal ){


        if( activeModals.length ){
            if( !lastModal ) {
                lastModal = activeModals.pop();
            }else{
                activeModals.splice( lastModal.indexOf( activeModals ), 1 );
            }

            if( evenoteModals[ lastModal ].modal.hasClass( 'evenote-animate' ) && !activeModals.length ){
                evenoteModals[ lastModal ].modal.removeClass( 'evenote-animate' );
                setTimeout( function(){
                    var current_modal = evenoteModals[ lastModal ];
                    current_modal.modal.fadeOut( 200, function(){
                        current_modal.modal.remove();
                    } )

                    if( evenoteModals[ lastModal ].flush ){
                        delete evenoteModals[ lastModal ];
                    }
                }, 500 );
            }else{
                if( evenoteBackdrop ){
                    var current_modal = evenoteModals[ lastModal ];
                    current_modal.modal.fadeOut( 200, function(){
                        current_modal.modal.remove();
                    } )

                    if( evenoteModals[ lastModal ].flush ){
                        delete evenoteModals[ lastModal ];
                    }

                }
            }

        }

        if( !activeModals.length ){
            if( evenoteBackdrop ){
                evenoteBackdrop.fadeOut( 250 , function(){
                    $( this ).remove();
                    evenoteBackdrop = null;
                });
            }
            pageHTML.removeClass('has-evenote-modal');
            $(window).trigger( 'modals.closed' );
        }else{
            evenoteModals[ activeModals[ ( activeModals.length - 1 ) ] ].modal.find('.evenote-modal-blocker').remove();
            evenoteModals[ activeModals[ ( activeModals.length - 1 ) ] ].modal.animate( {opacity : 1 }, 100 );
        }
        $(window).trigger( 'modal.close' );
    }
    $.evenoteModal = function(opts,trigger){

        pageHTML        = $('html');
        pageBody        = $('body');
        mainWindow      = $(window);

        var defaults    = $.extend(true, {
            element             :   'form',
            height              :   550,
            width               :   620,
            padding             :   12,
            speed               :   250,
            content             :   ''
        }, opts );
        defaults.trigger = trigger;
        if( !evenoteBackdrop && ! defaults.sticky ){
            evenoteBackdrop = $('<div>', {"class" : "evenote-backdrop"});
            if( ! defaults.focus ){
                evenoteBackdrop.on('click', function( e ){
                    if( e.target == this ){
                        closeModal();
                    }
                });
            }
            pageBody.append( evenoteBackdrop );
            evenoteBackdrop.hide();
        }

        // create modal element
        var modalElement = defaults.element,
            modalId = defaults.modal;


        if( typeof evenoteModals[ modalId ] === 'undefined' ){
            if( defaults.sticky ){
                defaults.sticky = defaults.sticky.split(' ');
                if( defaults.sticky.length < 2 ){
                    defaults.sticky = null;
                }
                activeSticky.push( modalId );
            }
            evenoteModals[ modalId ] = {
                config  :   defaults
            };

            evenoteModals[ modalId ].body = $('<div>', {"class" : "evenote-modal-body",id: modalId + '_evenoteModalBody'});
            evenoteModals[modalId].content = $('<div>', {"class": "evenote-modal-content", id: modalId + '_evenoteModalContent'});


        }else{
            evenoteModals[ modalId ].config = defaults;
        }



        var options = {
            id                  : modalId + '_evenoteModal',
            tabIndex            : -1,
            "ariaLabelled-by"   : modalId + '_evenoteModalLable',
            "method"            : 'post',
            "enctype"           : 'multipart/form-data',
            "class"             : "evenote-modal-wrap " + ( defaults.sticky ? ' evenote-sticky-modal ' + defaults.sticky[0] + '-' + defaults.sticky[1] : '' )
        };

        if( opts.config ){
            $.extend( options, opts.config );
        }
        //add in wrapper
        evenoteModals[ modalId ].modal = $('<' + modalElement + '>', options );


        // push active
        if( !defaults.sticky ){ activeModals.push( modalId ); }

        // add animate      
        if( defaults.animate && evenoteBackdrop ){
            var animate         = defaults.animate.split( ' ' ),
                animateSpeed    = defaults.speed + 'ms',
                animateEase     = ( defaults.animateEase ? defaults.animateEase : 'ease' );

            if( animate.length === 1){
                animate[1] = 0;
            }

            evenoteModals[ modalId ].modal.css( {
                transform               : 'translate(' + animate[0] + ', ' + animate[1] + ')',
                '-web-kit-transition'   : 'transform ' + animateSpeed + ' ' + animateEase,
                '-moz-transition'       : 'transform ' + animateSpeed + ' ' + animateEase,
                transition              : 'transform ' + animateSpeed + ' ' + animateEase
            } );

        }




        // padd content
        evenoteModals[ modalId ].content.css( {
            //padding : defaults.padding
        } );
        evenoteModals[ modalId ].body.append( evenoteModals[ modalId ].content ).appendTo( evenoteModals[ modalId ].modal );
        if( evenoteBackdrop ){ evenoteBackdrop.append( evenoteModals[ modalId ].modal ); }else{
            evenoteModals[ modalId ].modal . appendTo( $( 'body' ) );
        }


        if( defaults.footer ){
            if( !evenoteModals[ modalId ].footer ) {
                evenoteModals[modalId].footer = $('<div>', {"class": "evenote-modal-footer", id: modalId + '_evenoteModalFooter'});
                evenoteModals[ modalId ].footer.css({ padding: defaults.padding });

                // function?
                if( typeof window[defaults.footer] === 'function' ){
                    evenoteModals[ modalId ].footer.append( window[defaults.footer]( defaults, evenoteModals[ modalId ] ) );
                }else if( typeof defaults.footer === 'string' ){
                    // is jquery selector?
                    try {
                        var footerElement = $( defaults.footer );
                        evenoteModals[ modalId ].footer.html( footerElement.html() );
                    } catch (err) {
                        evenoteModals[ modalId ].footer.html( defaults.footer );
                    }
                }
            }

            evenoteModals[ modalId ].footer.appendTo( evenoteModals[ modalId ].modal );
        }

        if( defaults.title ){
            var headerAppend = 'prependTo';
            evenoteModals[ modalId ].header = $('<div>', {"class" : "evenote-modal-title", id : modalId + '_evenoteModalTitle'});
            evenoteModals[ modalId ].closer = $('<a>', { "href" : "#close", "class":"evenote-modal-closer", "data-dismiss":"modal", "aria-hidden":"true",id: modalId + '_evenoteModalCloser'}).html('&times;');
            evenoteModals[ modalId ].title = $('<h3>', {"class" : "modal-label", id : modalId + '_evenoteModalLable'});

            evenoteModals[ modalId ].title.html( defaults.title ).appendTo( evenoteModals[ modalId ].header );
            evenoteModals[ modalId ].title.css({ padding: defaults.padding });
            evenoteModals[ modalId ].title.append( evenoteModals[ modalId ].closer );
            if( evenoteModals[ modalId ].config.sticky ){
                if( evenoteModals[ modalId ].config.minimized && true !== evenoteModals[ modalId ].config.minimized ){
                    setTimeout( function(){
                        evenoteModals[ modalId ].title.trigger('click');
                    }, parseInt( evenoteModals[ modalId ].config.minimized ) );
                    evenoteModals[ modalId ].config.minimized = false;
                }
                evenoteModals[ modalId ].closer.hide();
                evenoteModals[ modalId ].title.addClass( 'evenote-modal-closer' ).data('modal', modalId).appendTo( evenoteModals[ modalId ].header );
                if( evenoteModals[ modalId ].config.sticky.indexOf( 'top' ) > -1 ){
                    headerAppend = 'appendTo';
                }
            }else{
                evenoteModals[ modalId ].closer.data('modal', modalId).appendTo( evenoteModals[ modalId ].header );
            }
            evenoteModals[ modalId ].header[headerAppend]( evenoteModals[ modalId ].modal );
        }
        // hide modal
        //evenoteModals[ modalId ].modal.outerHeight( defaults.height );
        evenoteModals[ modalId ].modal.outerWidth( defaults.width );

        if( defaults.content && !evenoteModals[ modalId ].content.children().length ){
            // function?
            if( typeof defaults.content === 'function' ){
                evenoteModals[ modalId ].content.append( defaults.content( defaults, evenoteModals[ modalId ] ) );
            }else if( typeof defaults.content === 'string' ){

                if( typeof window[ defaults.content ] === 'function' ){
                    evenoteModals[modalId].content.html( window[ defaults.content ]( defaults ) );
                }else {

                    // is jquery selector?
                    try {
                        var contentElement = $(defaults.content);
                        if (contentElement.length) {
                            evenoteModals[modalId].content.append(contentElement.html());
                            contentElement.show();
                        } else {
                            throw new Error;
                        }
                        evenoteModals[modalId].modal.removeClass('processing');
                    } catch (err) {
                        evenoteModals[modalId].footer.hide();
                        setTimeout(function () {
                            evenoteModals[modalId].modal.addClass('processing');
                            $.post(defaults.content, trigger.data(), function (res) {
                                evenoteModals[modalId].content.html(res);
                                evenoteModals[modalId].modal.removeClass('processing');
                                evenoteModals[modalId].footer.show();
                            });
                        }, 250);
                    }
                }
            }
        }else{
            evenoteModals[ modalId ].modal.removeClass('processing');
        }

        // others in place?
        if( activeModals.length > 1 ){
            if( activeModals[ ( activeModals.length - 2 ) ] !== modalId ){
                evenoteModals[ activeModals[ ( activeModals.length - 2 ) ] ].modal.prepend( '<div class="evenote-modal-blocker"></div>' ).animate( {opacity : 0.6 }, 100 );
                evenoteModals[ modalId ].modal.hide().fadeIn( 200 );
                //evenoteModals[ activeModals[ ( activeModals.length - 2 ) ] ].modal.fadeOut( 200, function(){
                  //  evenoteModals[ modalId ].modal.fadeIn( 2200 );
                //} );
            }
        }

        // set position;
        positionModals();
        // return main object
        $( window ).trigger('modal.open');

        if( opts.master && activeModals ){
            delete evenoteModals[ activeModals.shift() ];
        }


        evenoteModals[ modalId ].positionModals = positionModals;
        evenoteModals[ modalId ].closeModal = function(){
            closeModal( modalId );
        }
        var submit = evenoteModals[ modalId ].modal.find('button[type="submit"]');

        if( !submit.length ){
            evenoteModals[ modalId ].modal.find('input').on('change', function(){
                evenoteModals[ modalId ].modal.submit();
            })
        }else{
            evenoteModals[ modalId ].flush = true;
        }

        var notice = $('<div class="notice error"></div>'),
            message = $('<p></p>'),
            dismiss = $( '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>' );

        message.appendTo( notice );
        dismiss.appendTo( notice );

        dismiss.on('click', function(){
            notice.animate( { height: 0 }, 100, function(){
                notice.css('height', '');
                message.html();
                notice.detach();
            });
        });

        evenoteModals[ modalId ].modal.attr('data-load-element', '_parent' ).baldrick({
            request : window.location.href,
            before : function( el, e ){
                submit = evenoteModals[ modalId ].modal.find('button[type="submit"]');
                if( submit.length ){
                    submit.prop( 'disabled', true );
                    evenoteModals[ modalId ].modal.addClass('processing');
                }
                notice.detach();
            },
            callback : function( obj ){

                obj.params.trigger.find( '[type="submit"],button' ).prop( 'disabled', false );
                evenoteModals[ modalId ].modal.removeClass('processing');
                evenoteModals[ modalId ].data = obj.rawData.data;
                if ( typeof obj.rawData === 'object' ) {
                    if( obj.rawData.success ) {
                        if( typeof obj.rawData.data === 'string' ){
                            obj.rawData = obj.rawData.data;
                        }else if( typeof obj.rawData.data === 'object' ){
                            if( obj.rawData.data.redirect ){
                                window.location = obj.rawData.data.redirect;
                            }
                            evenoteModals[ modalId ].modal.trigger('modal.complete');
                        }else if( typeof obj.rawData.data === 'boolean' && obj.rawData.data === true ){

                            if( submit.length ) {
                                evenoteModals[ modalId ].flush = false;
                            }
                        }
                        closeModal();
                    }else{
                        obj.params.target = false;
                        if( typeof obj.rawData.data === 'string' ){
                            message.html( obj.rawData.data );
                            notice.appendTo( evenoteModals[ modalId ].body );
                            var height = notice.height();
                            notice.height(0).animate( { height: height }, 100 );
                        }else{
                            closeModal();
                        }
                    }
                }else{
                    closeModal();
                }
            },
            complete : function () {
                $(document).trigger('evenote.init');
            }
        });
        return evenoteModals[ modalId ];
    }

    $.fn.evenoteModal = function( opts ){

        if( !opts ){ opts = {}; }
        opts = $.extend( {}, this.data(), opts );
        return $.evenoteModal( opts, this );
    }

    // setup resize positioning and keypresses
    if ( window.addEventListener ) {
        window.addEventListener( "resize", positionModals, false );
        window.addEventListener( "keypress", function(e){
            if( e.keyCode === 27 && evenoteBackdrop !== null ){
                evenoteBackdrop.trigger('click');
            }
        }, false );

    } else if ( window.attachEvent ) {
        window.attachEvent( "onresize", positionModals );
    } else {
        window["onresize"] = positionModals;
    }

    $(document).on('click', '[data-modal]:not(.evenote-modal-closer)', function( e ){
        e.preventDefault();
        return $(this).evenoteModal();
    });

    $(document).on( 'click', '.evenote-modal-closer', function( e ) {
        e.preventDefault();
        $(window).trigger('close.modal');
    })

    $(window).on( 'close.modal', function( e ) {
        closeModal();
    })
    $(window).on( 'modal.init', function( e ) {
        $('[data-modal][data-autoload]').each( function(){
            $( this ).evenoteModal();
        });
    })
    $(window).on( 'modal.open', function( e ) {
        $(document).trigger('evenote.init');
    });
    $(window).load( function(){
        $(window).trigger('modal.init');
    });



})(jQuery);
