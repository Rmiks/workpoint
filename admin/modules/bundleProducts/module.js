jQuery( document ).ready( function () {

    var filterForm = jQuery( 'form.filterForm' );
    var searchForm = jQuery( 'form.searchForm' );

    jQuery( 'select[name=filterCategory]' ).bind( 'change', function () {
        var url = new RequestUrl( false );
        url.add( filterForm.serializeArray() );
        url.add( searchForm.serializeArray() );
        window.location = url.getUrl();
        url.add( { ajax: 1 } );
    } );

} );
