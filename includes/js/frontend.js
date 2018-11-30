jQuery( function( $ ) {
    $( '.adintgr_selector' ).click(function() {
        $( '.adintgr-form' )[0].action = this.attributes['page_url'].value;
    });
});
