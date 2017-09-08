function leafDialogStyling($leafDialog) {
    $leafDialog.find('.ui-icon-closethick').html('<svg class="leafDialog__close"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#close"></use></svg>');
    $leafDialog.css({
        width: '280px',
        minHeight: '405px',
        background: '#1d1d1d',
        color: '#fff',
        padding: '0',
        border: 'none',
        borderRadius: '0'
    });
    $leafDialog.children('.ui-dialog-titlebar').css({
        border: 'none',
        borderRadius: '0',
        background: '#383838',
        padding: '8px 18px',
        fontSize: '18px',
        fontWeight: 'normal'
    });
    $leafDialog.children('.ui-dialog-content').css({
        height: 'auto',
        margin: '0'
    });
}
