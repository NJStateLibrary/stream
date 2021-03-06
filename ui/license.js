/* global ajaxurl, tb_remove, alert, stream_activation, tb_show, alert, console */
jQuery(function( $ ){

	var	i = $( '<iframe>' ).appendTo( 'body' ),
		spinner = $( 'h2 .spinner' ),
		login_first = function() {
			var url = ( 'connect' === stream_activation.action ) ? stream_activation.api.connect : stream_activation.api.disconnect;
			url += '&site=' + window.location.host;
			tb_show( stream_activation.i18n.login_to_stream, url + '&modal=1#TB_iframe?height=400&amp;width=350&amp;inlineId=hiddenModalContent', null );
		},
		receive = function( message ) {
			spinner.hide();

			if ( 'string' !== typeof message || ! message.match( /^stream:/ ) ) {
				return;
			}

			console && console.debug( message );

			var data = $.map(
				message
					.replace( /^(stream:)/, '' )
					.split( '&' ),
				function( i ) {
					return i.split( '=' );
				}
			);
			data[ 1 ] = decodeURIComponent( ( data[ 1 ] + '' ).replace( /\+/g, '%20' ) );
			if ( 'error' === data[ 0 ] ) {
				alert( data[ 1 ] );
			} else if( 'login' === data[ 0 ] ) {
				login_first();
			} else if( 'license' === data[ 0 ] ) {
				got_license( data[ 1 ] );
			} else if( 'disconnected' === data[ 0 ] ) {
				disconnect();
			}
		},
		got_license = function( license ) {
			console && console.debug( 'Got license: ', license );
			// Remove the modal box right away so it doesn't seemed frozen.
			tb_remove();
			$.ajax({
				url: ajaxurl,
				type: 'post',
				data: { action: 'stream-license-check', license: license, nonce: stream_activation.nonce.license_check },
				dataType: 'json',
				success: function( r ) {
					console && console.debug( 'Got license verification results: ', r );
					spinner.hide();
					if ( r.success ) {
						alert( r.data.message );
						window.location.reload();
					} else {
						alert( r.data );
					}
				}
			});
		},
		disconnect = function() {
			console && console.debug( 'Disconnected from mothership, removing local license.' );
			tb_remove();
			$.ajax({
				url: ajaxurl,
				type: 'post',
				data: { action: 'stream-license-remove', nonce: stream_activation.nonce.license_remove },
				dataType: 'json',
				success: function( r ) {
					spinner.hide();
					if ( r.success ) {
						alert( r.data.message );
						console && console.debug( 'Removed license locally, refreshing page.' );
						window.location.reload();
					}
				}
			});
		};



	if ( 'undefined' !== typeof window.postMessage ) {
		if ( 'undefined' === typeof window.boundMessageListner ) {
			// Receive postMessage
			window.addEventListener( 'message', function( event ) {
				receive( event.data );
			}, true );
			window.boundMessageListner = true;
		}
	} else {
		// TODO: Fall back
	}

	$( 'a[data-stream-connect]' ).click(function( e ) {
		e.preventDefault();
		spinner.css( { display: 'inline-block' } );
		i.attr( 'src', stream_activation.api.connect );
	});

	$( 'a[data-stream-disconnect]' ).click(function( e ) {
		e.preventDefault();
		spinner.css( { display: 'inline-block' } );
		i.attr( 'src', stream_activation.api.disconnect );
	});

});
