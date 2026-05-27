import {
  updatefree,
  updatedata
} from './utils'

export default ($, $pagesprices, formclass='') => {

  $pagesprices
  .each(function() {
    
    const $this = $(this)
    const id = $this.attr('id').replace('post-', '')
    const $tooglefree = $this.find('.PriceTools .Access input.IsFree')
    const $tooglelabel = $this.find('.PriceTools .Access label')

    $tooglefree
    .on(
      'click',
      function() {

        const $this = $(this)
        const ischecked = $this.is(':checked')

        $tooglelabel.removeClass('Free')
        $tooglelabel.addClass('Updating')
        $tooglelabel.html('Actualizando')

        updatefree($, id, ischecked) 
        .then(result => {

          $tooglelabel.removeClass('Updating')

          if(result && result.success) {

            if(ischecked) {

              $tooglelabel.addClass('Free')
              $tooglelabel.html('Abierta')

            } else {

              $tooglelabel.html('Restringida')
            }

          } else {

            // If failed, revert the checkbox state
            $this.prop('checked', !ischecked);
            if (!ischecked) {
               $tooglelabel.addClass('Free')
               $tooglelabel.html('Abierta')
            } else {
               $tooglelabel.html('Restringida')
            }
            console.error('Update failed:', result);
          }
        })
        .catch(error => {

          console.log(error)
        })
      }
    )
  })
  
  updatedata($, $pagesprices)
}