var $rewrite = $( 'input[name="slug"]' );

if ( $rewrite.length )
{
    var $input = $( 'input[name="' + slugifyThis +'"]' );

    $rewrite.on( 'blur', function() {
        rewriteSlug( 'slug' );
    });

    $input.on( 'blur', function() {
        if ( $rewrite.data( 'was' ) === 'empty' || !$rewrite.val() ) {
            rewriteSlug( 'input' );
        }
    });
}

function rewriteSlug( source ) {
    var $name = $( 'input[name="' + slugifyThis +'"]' );
    var $slug = $( 'input[name="slug"]' );

    if ( source === 'slug' && $slug.val() ) {
        $name = $slug;
    }

    if ( $name.val() !== '' ) {

        var rewrite_name = $name.val().trim().toLowerCase();
        var url = document.location.href
            + '&suggest_rewrite_name=' + encodeURIComponent( rewrite_name );

        var guessed_name = openXmlHttpGet( url, true );

        $slug.val( guessed_name );
    }
}