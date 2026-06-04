import {
  updateOpen,
  updateData
} from './utils'

export default ($, $pageStatuses, formClass='') => {

  $pageStatuses
  .each(function() {
    
    const $this = $(this) 
    const id = $this.attr('id').replace('post-', '')
    const $toggleFree = $this.find('.AccessTools .Access input.IsOpen')
    const $toggleLabel = $this.find('.AccessTools .Access label')

    $toggleFree
    .on(
      'click',
      function() {

        const $this = $(this)
        const isChecked = $this.is(':checked')

        $toggleLabel.removeClass('Open')
        $toggleLabel.addClass('Updating')
        $toggleLabel.html('Actualizando')

        updateOpen($, id, isChecked) 
        .then(result => {

          $toggleLabel.removeClass('Updating')

          if(result && result.success) {

            if(isChecked) {

              $toggleLabel.addClass('Open')
              $toggleLabel.html('Abierta')

            } else {

              $toggleLabel.html('Restringida')
            }

          } else {

            // If failed, revert the checkbox state
            $this.prop('checked', !isChecked);
            if (!isChecked) {
               $toggleLabel.addClass('Open')
               $toggleLabel.html('Abierta')
            } else {
               $toggleLabel.html('Restringida')
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
  
  updateData($, $pageStatuses)
}