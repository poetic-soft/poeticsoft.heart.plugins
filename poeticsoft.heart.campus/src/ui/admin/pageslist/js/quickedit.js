export default $ => {

  $(document)
  .on(
    'click', 
    '.editinline', 
    function() {

      console.log('editinline')
    
      const postid = $(this).closest('tr').attr('id').replace('post-', '');
      const valoractual = $('#post-' + postid).find('.valor-meta-contenedor').data('valor');
      
      console.log(postid)

      if (valor_actual === undefined || valor_actual === '') {
          valor_actual = '0';
      }
      var inline_edit_row = $(this).closest('tr').next();
      if(!inline_edit_row.hasClass('inline-edit-row')) {
          inline_edit_row = inline_edit_row.next(); // A veces hay filas intermedias
      }
      inline_edit_row.find('.mi-meta-bool-select').val(valor_actual);
    }
  );

}