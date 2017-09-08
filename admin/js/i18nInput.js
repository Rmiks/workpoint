jQuery(function()
{
	jQuery( '.i18nInput .languageWrap .languageWrap_select' ).live( 'change', function()
	{
		var tab = jQuery( this );
		var language = tab.val();

		var i18nInputs = jQuery( '.i18nInput' );
		i18nInputs.each(function()
		{
			var wrap = jQuery( this );
			wrap.find( '.languageWrap .languageWrap_select' ).removeClass( 'active' );
			wrap.find( '.languageWrap .languageWrap_select' ).val( language );
			var previousTinyMceTable = wrap.find( '.input:visible .mceLayout' );
			var previous =
			{
				tableWidth:   previousTinyMceTable.css( 'width' ),
				tableHeight:  previousTinyMceTable.css( 'height' ),
				iframeWidth:  previousTinyMceTable.find( 'iframe' ).css('width'),
				iframeHeight: previousTinyMceTable.find( 'iframe' ).css('height')
			};
			wrap.find( '.inputWrap .input,.slugInput' ).hide();
			wrap.find( '.inputWrap .slugInput[data-language="' + language + '"], .input[data-language="' + language + '"]' ).show();
			if( previousTinyMceTable.length > 0 )
			{
				wrap.find( '.input:visible .mceLayout' ).css( 'width', previous.tableWidth );
				wrap.find( '.input:visible .mceLayout' ).css( 'height', previous.tableHeight );
				wrap.find( '.input:visible .mceLayout iframe' ).css( 'width', previous.iframeWidth );
				wrap.find( '.input:visible .mceLayout iframe' ).css( 'height', previous.iframeHeight );
			}
		});
		var wrap = tab.parents( '.i18nInput' );
		// normal input
		wrap.find( '.inputWrap .input[data-language="' + language + '"]' ).focus();
		// textarea
		wrap.find( '.inputWrap .input[data-language="' + language + '"] textarea' ).focus();
        // slug
        wrap.find( '.inputWrap .slugInput[data-language="' + language + '"] .input' ).focus();
		// richtext
		var richtext = wrap.find( '.inputWrap .input[data-language="' + language + '"] textarea:tinymce' );

		if( richtext.length )
		{
			richtext.tinymce().focus();
		}
	});
	// keyboard language navigation
	// TODO: disable autocomplete flickering on ff
	jQuery( '.i18nInput' ).live( 'keyup', function( event )
	{
		var input = jQuery( event.target );
		var wrap = jQuery( this );
		if( ( event.altKey || event.ctrlKey ) && ( event.keyCode == 38 || event.keyCode == 40 ) )
		{
			var active = wrap.find( '.languageWrap button.active' );
			switch( event.keyCode )
			{
				case 38: // up arrow
					active.prev().click();
				break;
				case 40: // down arrow
					active.next().click();
				break;
			}
		}
	});
	// forward keyup events from richtext iframe
	jQuery( 'body' ).bind( 'tinymceinit', function( event )
	{
		var iframe = jQuery( event.target ).find( 'iframe' );
		jQuery( iframe[0].contentDocument ).keyup(function( event )
		{
			iframe.trigger( event );
		});
	});

    // slug
    var $rewrite = $( 'input[name$="slug"][name^="i18n"]' );

    if ( $rewrite.length )
    {
        var $name    = $( 'input[name$="' + slugifyThis + '"][name^="i18n"]' );

        $rewrite.on( 'blur', function() {
            rewriteI18nSlug( $(this).data( 'language' ), 'slug' );
        });

        $name.on( 'blur', function() {
            var language = $(this).data( 'language' );
            var $slug  = $( 'input[name="i18n:' + language + ':slug"]' );

            if ( $slug.data( 'was' ) === 'empty' || !$slug.val() ) {
                rewriteI18nSlug( language, 'input' );
            }
        });
    }
});

function rewriteI18nSlug( language, source ) {
    var $input = $( 'input[name="i18n:' + language + ':' + slugifyThis + '"]' );
    var $slug  = $( 'input[name="i18n:' + language + ':slug"]' );

    if ( source === 'slug' && $slug.val() ) {
        $input = $slug;
    }

    if ( $input.val() !== '' ) {

        var rewrite_name = $input.val().trim().toLowerCase();
        var url = document.location.href
            + '&suggest_rewrite_name=' + encodeURIComponent( rewrite_name )
            + '&i18n_language=' + language;

        var guessed_name = openXmlHttpGet( url, true );

        $slug.val( guessed_name );
    }
}