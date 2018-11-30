jQuery( function( $ ) {
    $( '.adintgr_selector' ).click(function() {
        $( '.adintgr-form' )[0].action = this.attributes['page_url'].value;
    });

    $( '.wpadintgr-submit' ).click(function() {
        var valid_result = false;
        $( '.wpadintgr-list-item' ).each( function ( index, el ) {
            if ( $( el ).find( '.adintgr_selector' ).prop('checked') ) {
                if ( $( el ).find( '.valid-zipcode' ).val() == '-1' ) {
                } else if ( $( el ).find( '.valid-zipcode' ).val() == '0' ) {
                    valid_result = true;
                } else if ( $( el ).find( '.valid-zipcode' ).val() == '1' ) {
                    if ( $( '#adintgr-zipcode' ).val() == '' ) {
                        alert('Please input the zipcode!');
                    } else {
                        valid_result = true;
                    }
                }
            }
        });
	    return valid_result;
    });
});
