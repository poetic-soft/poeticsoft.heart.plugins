export default $ => {

  $(document)
  .on(
    'click', 
    '.editinline', 
    function() {

      console.log('editinline')
    
      const postId = $(this).closest('tr').attr('id').replace('post-', '');
      let valorActual = $('#post-' + postId).find('.valor-meta-contenedor').data('valor');
      
      console.log(postId)

      if (valorActual === undefined || valorActual === '') {
          valorActual = '0';
      }
      var inlineEditRow = $(this).closest('tr').next();
      if(!inlineEditRow.hasClass('inline-edit-row')) {
          inlineEditRow = inlineEditRow.next(); // A veces hay filas intermedias
      }
      inlineEditRow.find('.mi-meta-bool-select').val(valorActual);
    }
  );

}