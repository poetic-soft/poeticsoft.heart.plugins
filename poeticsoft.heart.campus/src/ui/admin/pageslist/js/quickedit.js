import { getPageStatus} from './utils'

export default $ => {

  $(document)
  .on(
    'click', 
    '.editinline', 
    function() {

      const $this = $(this);
    
      const postId = $this.closest('tr').attr('id').replace('post-', '');
      getPageStatus(postId)
      .then(result => {
      
        if(result.success) {

          const inCampus = result.data.in_campus          
          let $inlineEditRow = $(this).closest('tr').next();
          if(!$inlineEditRow.hasClass('inline-edit-row')) {
              $inlineEditRow = $inlineEditRow.next();
          }
          const $statusFieldset = $inlineEditRow.find('fieldset.inline-edit-col-right.poeticsoft-heart-campus-access')

          if(inCampus) {

            const status = result.data.access
            const $statusSelect = $statusFieldset.find('select.poeticsoft-heart-campus-access')

            $statusSelect.val(status)

          } else {

            $statusFieldset.remove()
          }
        }
      })
    }
  );

}