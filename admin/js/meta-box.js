jQuery( function ( $ ) {
	// Allow Tabbing
	$( '#titlediv' ).find( '#title' ).keyup( function( event ) {
		var code = event.keyCode || event.which;

		// Tab key
		if ( code === '9' && $( '#adintgr-coupon-description' ).length > 0 ) {
			event.stopPropagation();
			$( '#adintgr-coupon-description' ).focus();
			return false;
		}
	});

	$( '.adintgr-metaboxes-wrapper' ).on( 'click', '.adintgr-metabox > h3', function() {
		$( this ).parent( '.adintgr-metabox' ).toggleClass( 'closed' ).toggleClass( 'open' );
	});

	// Tabbed Panels
	$( document.body ).on( 'adintgr-init-tabbed-panels', function() {
		$( 'ul.adintgr-tabs' ).show();
		$( 'ul.adintgr-tabs a' ).click( function( e ) {
			e.preventDefault();
			var panel_wrap = $( this ).closest( 'div.panel-wrap' );
			$( 'ul.adintgr-tabs li', panel_wrap ).removeClass( 'active' );
			$( this ).parent().addClass( 'active' );
			$( 'div.panel', panel_wrap ).hide();
			$( $( this ).attr( 'href' ) ).show();
		});
		$( 'div.panel-wrap' ).each( function() {
			$( this ).find( 'ul.adintgr-tabs li' ).eq( 0 ).find( 'a' ).click();
		});
	}).trigger( 'adintgr-init-tabbed-panels' );

	// Meta-Boxes - Open/close
	$( '.adintgr-metaboxes-wrapper' ).on( 'click', '.adintgr-metabox h3', function( event ) {
		// If the user clicks on some form input inside the h3, like a select list (for variations), the box should not be toggled
		if ( $( event.target ).filter( ':input, option, .sort' ).length ) {
			return;
		}
		
		$( this ).next( '.adintgr-metabox-content' ).stop().slideToggle();
	})
	.on( 'click', '.expand_all', function() {
		$( this ).closest( '.adintgr-metaboxes-wrapper' ).find( '.adintgr-metabox > .adintgr-metabox-content' ).show();
		return false;
	})
	.on( 'click', '.close_all', function() {
		$( this ).closest( '.adintgr-metaboxes-wrapper' ).find( '.adintgr-metabox > .adintgr-metabox-content' ).hide();
		return false;
	});
	$( '.adintgr-metabox.closed' ).each( function() {
		$( this ).find( '.adintgr-metabox-content' ).hide();
	});
});
