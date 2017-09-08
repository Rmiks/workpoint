function initLeafFileFields( domNode, show )
{
	if( domNode == undefined )
	{
		domNode = '.leafFile-field-wrap';
	}

	jQuery( domNode ).each(function()
	{
        var wrapQ = jQuery( this );

        var fileIdInput  = wrapQ.find('input.leafFile-id-field:first');
        var removeButton = wrapQ.find('.removeFileButton');
        var removeConfirmationField = wrapQ.find('.leafFile-remove-confirmation-field:first');
        var filePreview = wrapQ.find('.pure-file-preview');

        removeButton.each(function()
        {
            jQuery( this ).click(function()
            {
                 if (confirm(removeConfirmationField.val()))
                 {
                    fileIdInput.val( -1 );
                    wrapQ.removeClass('field-has-leafFile');

					 var $label = wrapQ.find('label');
					 if ($label.length) {
						 if (fileIdInput.prop('multiple')) {
							 $label.html('Files will be removed on save');
						 } else {
							 $label.html('File will be removed on save');
						 }
						 $label.fadeTo('slow', 0.5).fadeTo('slow', 1.0);
					 }

					 filePreview.remove();
				 }
            });

        });
	});
}

function initFileInputs(){
    var inputs = document.querySelectorAll( '.pure-file' );
    Array.prototype.forEach.call( inputs, function( input )
    {
    	var label	 = input.nextElementSibling,
    		labelVal = label.innerHTML;

    	input.addEventListener( 'change', function( e )
    	{
    		var fileName    = '',
                targetFiles = e.target.files;

			if (targetFiles.length > 1) {
				fileName = targetFiles.length + ' files';  //( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '[count]', targetFiles.length );
			}
			else if (targetFiles && targetFiles.length == 1) {
				var inputFilename = e.target.value.split('\\').pop();
				fileName = 'File "[filename]" selected'.replace('[filename]', '<b style="color: #37b1a7">' + inputFilename+ '</b>');
			}
			else {
				fileName = 'No files selected';
			}

			if ( fileName )
    			label.innerHTML = fileName;
    		else
    			label.innerHTML = labelVal;

			var filePreview = input.closest('.field').querySelector('.pure-file-preview');

			if (filePreview) {
				filePreview.remove();
			}

			$(label).fadeTo('slow', 0.5).fadeTo('slow', 1.0);
    	});
    });
}

jQuery( document ).ready( function()
{
	initLeafFileFields();
    initFileInputs();
});
