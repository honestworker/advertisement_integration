jQuery( function( $ ) {
    $( '.adintgr_selector' ).click(function() {
        $( '.adintgr-form' )[0].action = this.attributes['page_url'].value;
    });

    $( '.wpadintgr-submit' ).click(function() {
        var valid_result = false;
        if ( $( '#adintgr-zipcode' ).val() != '' ) {
            valid_result = true;
        } else {
            $( '.wpadintgr-list-item' ).each( function ( index, el ) {
                if ( $( el ).find( '.adintgr_selector' ).prop('checked') ) {
                    if ( $( el ).find( '.valid-zipcode' ).val() == '0' ) {
                        valid_result = true;
                    }
                }
            });
        }
        if ( !valid_result ) {
            alert('Please input the zipcode!');
        }
	    return valid_result;
    });
});
