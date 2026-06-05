import message from '../common/message'
import {
  validateEmail
} from '../common/utils'
import {
  apiFetch
} from '../common/utils'
import form from './forms'
import confirmCode from './do-confirmcode'
import registerShould from './do-register-should'
import registerWant from './do-register-want'

export default ($, $wrapper) => {

  const accessType = poeticsoft_heart_campus_access_by
  
  const $forms = $wrapper.find('.Forms.Identify')  
  $forms.find('.Form').remove()

  $forms.html(form({ form: 'identify'}))

  const $identify = $forms.find('.Form.Identify')
  const $identifyEmail = $identify.find('input.Email')
  const $identifySendEmail = $identify.find('button.SendEmail')
  const $identifyNotRegistered = $identify.find('a.NotRegistered')

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

  $identifyNotRegistered.on(
    'click',
    function() {

      registerWant($, $wrapper)

      return false
    }
  )

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

        apiFetch({
          url: 'identify/subscriber/identify',
          body: {
            email: email
          }
        })
        .then(data => {

          if(data.success) {

            confirmCode(
              $, 
              $wrapper,
              email, 
              data.code
            )

          } else {

            message(
              $, 
              $wrapper,
              data.error.message,
              'Error'
            )
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
