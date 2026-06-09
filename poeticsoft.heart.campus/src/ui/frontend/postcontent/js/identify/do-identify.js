import message from '../common/message'
import {
  validateEmail
} from '../common/utils'
import {
  apiFetch
} from '../common/utils'
import form from './forms'
import confirmLink from './do-confirmlink'

export default ($, $wrapper) => {
  
  const $forms = $wrapper.find('.Forms.Identify')  
  $forms.find('.Form').remove()

  $forms.html(form({ form: 'identify'}))

  const $identify = $forms.find('.Form.Identify')
  const $identifyEmail = $identify.find('input.Email')
  const $identifySendEmail = $identify.find('button.SendEmail')

  function checkEmail () {

    const $this = $(this)      
    const email = $this.val()

    if(
      $this[0].checkValidity()
      &&
      validateEmail(email)
    ) {

      $identifySendEmail.prop('disabled', false)

    } else {

      $identifySendEmail.prop('disabled', true)      
    }

    message(
      $, 
      $wrapper, 
      '', 
      ''
    )
  }

  $identifyEmail.on('change', checkEmail)
  $identifyEmail.on('keydown', checkEmail)
  $identifyEmail.on('keyup', checkEmail)

  $identifySendEmail.on(
    'click',
    function() {

      const email = $identifyEmail.val()

      message(
        $, 
        $wrapper,
        'Enviando...', 
        'Warn'
      )

      if(
        $identifyEmail[0].checkValidity()
        &&
        validateEmail(email)
      ) {

        $identifyEmail.prop('disabled', true)  
        $identifySendEmail.prop('disabled', true)

        const location = window.location.href

        apiFetch({
          url: 'identify',
          body: {
            email: email,
            url: location
          }
        })
        .then(data => {

          if(data.success) {

            confirmLink(
              $, 
              $wrapper,
              email, 
              location
            )

          } else {

            message(
              $, 
              $wrapper,
              data.error.message,
              'Error'
            )

            $identifyEmail.prop('disabled', false)  
            $identifySendEmail.prop('disabled', false)
          }
        })
        .catch(error => {

          message(
            $, 
            $wrapper,
            'Error de servidor, intentalo de nuevo, por favor.',
            'Error'
          )

          $identifyEmail.prop('disabled', false)  
          $identifySendEmail.prop('disabled', false)
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
