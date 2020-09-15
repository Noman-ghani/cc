jQuery( function ( $ ) {
    var anchors = $( '.mail-debug-message__tabs-anchor' ),
        tabs    = $( '.mail-debug-message__tab' );

    anchors.on( 'click', function () {
        var tab = $( '#' + $( this ).data( 'ref' ) );
        anchors.removeClass( 'active' );
        tabs.removeClass( 'active' );

        $( this ).addClass( 'active' );
        tab.addClass( 'active' );
    } );

} );