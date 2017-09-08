;(function($){
    $(document).ready(function(){

        var $imageList = $('#images');
        if ($imageList.length)
        {
            $imageList.sortable();
        }

        $('.delete-image').bind('click', function(e){
            e.preventDefault();
            var $this = $(this),
                $li = $this.closest('li'),
                id = parseInt( $li.find('[type=hidden]').val() );

            if (!isNaN(id) && id > 0 && confirm('Confirm image deletion!')) {

                $.get('/admin/',{
                    module: get('module'),
                    do: 'removeImage',
                    id: get('id'),
                    imageId: id
                }, function() {
                    $li.remove();
                });
            }
        });


        var _get = {}, _getParams = window.location.search.slice(1).split('&');
        for(var i = 0, l = _getParams.length; i < l; i++) {
            var keyVal = _getParams[i].split('=');
            _get[decodeURIComponent(keyVal[0])] = decodeURIComponent(keyVal[1]);
        }

        function get(key, defaultValue) {
            if (typeof _get[key] === 'undefined') {
                return defaultValue;
            }
            else {
                return _get[key];
            }
        }


    });
})(jQuery)