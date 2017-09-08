var objectlink = 
{
	openDialog: function( domNode, event )
	{
		// stop event
		if( event.preventDefault )
		{
			event.preventDefault();
		}
		// check for existing dialog
		if( domNode.dialog !== undefined )
		{
			domNode.dialog.dialog( 'open' );
		}
		// create tree dialog
		else 
		{
			domNode.dialog = Leaf.createTree
			({
				callback: function( id )
				{
					jQuery( domNode ).siblings( 'input' ).val( id );
					jQuery( domNode ).siblings( 'input' ).change();
					jQuery( domNode ).siblings( 'input' ).focus();
				}
			});	
		}

		var $leafDialog = jQuery('.leafDialog');
		var dialogIndex = 0;
		$leafDialog.each(function(index) {
			if (!jQuery(this).hasClass('leafMediaDialog') && !jQuery(this).children('.richtextEmbedDialog').length) {
				dialogIndex = index;
			}
		});
		$leafDialog = $leafDialog.eq(dialogIndex);

		leafDialogStyling($leafDialog);
	},
	extractIdFromUrl: function( domNode )
	{
		if( domNode.value == '' || parseInt( domNode.value ) == domNode.value)
		{
	        return;
		}
		var pattern = /(object_id=)(\d*)/;
		var result = domNode.value.match( pattern );
		if( result && result.length == 3 )
		{
			domNode.value = result[2];
		}
		jQuery( domNode ).change();
	}


};
