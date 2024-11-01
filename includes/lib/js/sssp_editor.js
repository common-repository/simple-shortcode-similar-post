(function() {
  tinymce.create( 'tinymce.plugins.ssspost_button', {
    init: function( ed, url ) {
      ed.addButton( 'ssspost', {
        title: 'shortcode simple similar post',
        icon: 'code',
        cmd: 'sssp'
      });

      ed.addCommand( 'sssp', function() {
        var selected_text = ed.selection.getContent(),
            return_text = '[ssspost post_num=1]';
        ed.execCommand( 'mceInsertContent', 0, return_text );
      });
    },
    createControl : function( n, cm ) {
      return null;
    },
  });
  tinymce.PluginManager.add( 'sssp_script', tinymce.plugins.ssspost_button );
})();