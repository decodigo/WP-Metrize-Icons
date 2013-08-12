(function($) {
    var currentEditor;

    tinymce.create('tinymce.plugins.wpmetrize', {
        init : function(ed, url){
            /***********************************
            Metrize Button
            ***********************************/
            ed.addButton( 'metrize_icons', {
                title : 'Add an Icon',
                cmd : 'metrizeicon',
                image : url + '/buttons/btn-icons.png',
                onclick : function(e) {
                    // ed.selection.setContent( '[icon name="' + ed.selection.getContent() + '"]');
                    $('#iconpopupbtn').click();

                    // Pass the editor to the window so that it's accessible to others
                    currentEditor = ed;
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        }
    });

    // Register plugin
    tinymce.PluginManager.add( 'wpmetrize', tinymce.plugins.wpmetrize );

    $('.metrize-icons-container a').live('click', function(e){
        var ed = currentEditor;
        ed.selection.setContent( '[icon name="'+this.rel+'" size="'+document.getElementById('metrize_size').value+'"]' );
        tb_remove();
        e.preventDefault();
    });
})(jQuery);
