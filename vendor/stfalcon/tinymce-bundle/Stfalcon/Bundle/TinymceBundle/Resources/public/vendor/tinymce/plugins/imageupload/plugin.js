tinymce.PluginManager.add('imageupload', function(editor, url) {
    var displayIcon = 'image';
    var longText = 'Upload and insert an image';
    var menuParent = 'insert';

    function openDialog() {
        editor.windowManager.open({
            title: longText,
            file: url + '/dialog.html',
            width: 350,
            height: 80,
            buttons: [{
                text: 'Cancel',
                onclick: 'close'
            }]
        });
    };

    editor.addButton('imageupload', {
        tooltip: longText,
        icon: displayIcon,
        onclick: openDialog
    });

    editor.addMenuItem('imageupload', {
        text: longText,
        icon: displayIcon,
        context: menuParent,
        onclick: openDialog
    });
});