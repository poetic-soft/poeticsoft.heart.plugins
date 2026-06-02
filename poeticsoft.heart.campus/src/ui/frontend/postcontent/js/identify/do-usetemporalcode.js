import message from '../common/message'
import {
  apiFetch
} from '../common/utils'
import form from './forms'

export default ($, $wrapper) => {
  
  const $forms = $wrapper.find('.Forms.UseTemporalCode')  
  $forms.find('.Form').remove()

  $forms.html(form({ form: 'useTemporalCode'}))

  const $useTemporalCode = $forms.find('.Form.UseTemporalCode')
  const $useTemporalCodeCode = $useTemporalCode.find('input.TemporalCode')
  const $useTemporalCodeSend = $useTemporalCode.find('button.SendTemporalCode')

  function checkCode () {

    const $this = $(this)      
    const code = $this.val()

    if(code.length > 4) {

      $useTemporalCodeSend.prop('disabled', false)

    } else {

      $useTemporalCodeSend.prop('disabled', true)
      
    }

    message(
      $,
      $wrapper, 
      '', 
      ''
    )
  }

  $useTemporalCodeCode.on('change', checkCode)
  $useTemporalCodeCode.on('keydown', checkCode)
  $useTemporalCodeCode.on('keyup', checkCode)

  $useTemporalCodeSend.on(
    'click',
    function() {

      const code = $useTemporalCodeCode.val()

      message(
        $,
        $wrapper, 
        'Enviando...', 
        'Warn'
      )

      if(code.length > 4) {

        $useTemporalCodeCode.prop('disabled', true)  
        $useTemporalCodeSend.prop('disabled', true)

        apiFetch({
          url: 'identify/subscriber/checktemporalcode',
          body: {
            code: code
          }
        })
        .then(result => { 

          if(result.result == 'ok') { 

            setTimeout(() => {
              
              window.location.reload()

            }, 2000)

          } else {

            message(
              $,
              $wrapper, 
              result.message,
              'Error'
            )

            setTimeout(() => {   

              message(
                $, 
                $wrapper,
                '',
                ''
              )           

              $useTemporalCodeCode.prop('disabled', false)  
              $useTemporalCodeSend.prop('disabled', false)

            }, 2000)
          }

        })
        .catch(error => {

          console.log(error)

          message(
            $, 
            $wrapper,
            'Error de servidor, intentalo de nuevo, por favor.',
            'Error'
          )

          $useTemporalCodeCode.prop('disabled', false)  
          $useTemporalCodeSend.prop('disabled', false)
        })

      } else {

        message(
          $,
          $wrapper, 
          'El mail no es válido.', 
          'Error'
        )
      }
    }
  )
}