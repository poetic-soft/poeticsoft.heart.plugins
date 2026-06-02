import forms from './forms'
import message from '../common/message'
import {
  apiFetch
} from '../common/utils'

export default ($, $wrapper, email, code) => {
  
  const $forms = $wrapper.find('.Forms.Identify')  
  $forms.find('.Form').remove()

  $forms.html(forms({ 
    form: 'confirmCode',
    code: code
  }))

  const $codeConfirm = $forms.find('.Form.ConfirmCode')
  const $codeConfirmInput = $codeConfirm.find('input.Code')
  const $codeConfirmConfirmCode = $codeConfirm.find('button.ConfirmCode') 
  const $identifyResendCode = $codeConfirm.find('a.ResendCode')
  
  $codeConfirmConfirmCode.on(
    'click',
    function() {

      const code = $codeConfirmInput.val()
      $codeConfirmInput.prop('disabled', true)  
      $codeConfirmConfirmCode.prop('disabled', true)

      message(
        $, 
        $wrapper,
        'Confirmando...', 
        'Warn'
      )

      apiFetch({
        url: 'identify/subscriber/confirmcode',
        body: {
          email: email,
          code: code
        }
      })
      .then(data => {

        if(data.result == 'ok') {          

          message(
            $, 
            $wrapper,
            'Identifiación confirmada. Redirigiendo a la página de contenidos', 
            'Info'
          )

          setTimeout(() => {
            
            window.location.reload()

          }, 2000)

        } else {

          console.log(data)

          message(
            $, 
            $wrapper,
            data.message, 
            'Error'
          )
        }

        $codeConfirmInput.prop('disabled', false)  
        $codeConfirmConfirmCode.prop('disabled', false)

      })
      .catch(error => {

        console.log(error)

        message(
          $, 
          $wrapper,
          'Error de servidor, intentalo de nuevo, por favor.',
          'Error'
        )

        $codeConfirmInput.prop('disabled', false)  
        $codeConfirmConfirmCode.prop('disabled', false)
      })
    }
  )  

  $identifyResendCode.on(
    'click',
    function() {     

      $codeConfirmInput.val('')
      $codeConfirmInput.prop('disabled', false)  
      $codeConfirmConfirmCode.prop('disabled', false)   

      message(
        $, 
        $wrapper,
        'Reenviando...', 
        'Warn'
      )

      apiFetch({
        url: 'identify/subscriber/identify',
        body: {
          email: email
        }
      })
      .then(data => {

        if(data.result == 'ok') {
          
          // $codeConfirmInput.val(data.code)

          message(
            $, 
            $wrapper,
            'Se ha reenviado el código.',
            'Info'
          )
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

        $codeConfirmInput.prop('disabled', false)  
        $codeConfirmConfirmCode.prop('disabled', false)
      })

      return false
    }
  )
}
