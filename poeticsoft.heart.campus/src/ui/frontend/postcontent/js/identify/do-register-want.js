import {
  validateEmail
} from '../common/utils'
import forms from './forms'
import message from '../common/message'
import identify from './do-identify'
import confirmCode from './do-confirmcode'
import {
  apiFetch
} from '../common/utils'

export default ($, $wrapper) => {
  
  const $forms = $wrapper.find('.Forms.Identify')  
  $forms.find('.Form').remove()

  $forms.html(forms({ form: 'registerWant' }))

  const $registerWant = $forms.find('.Form.RegisterWant')
  const $registerWantEmail = $registerWant.find('input.Email')
  const $registerWantSendEmail = $registerWant.find('button.SendEmail')  
  const $registerWantBackIdentify = $registerWant.find('a.BackIdentify') 
  
  function checkEmail () {

    const $this = $(this)      
    const email = $this.val()

    if(
      $this[0].checkValidity()
      &&
      validateEmail(email)
    ) {

      $registerWantSendEmail.prop('disabled', false)

    } else {

      $registerWantSendEmail.prop('disabled', true)
      
    }

    message(
      $, 
      $wrapper,
      '', 
      ''
    )
  } 

  $registerWantEmail.on('keydown', checkEmail)
  $registerWantEmail.on('change', checkEmail)

  $registerWantBackIdentify.on(
    'click',
    function() {

      identify($, $wrapper)

      return false
    }
  )
  
  $registerWantSendEmail.on(
    'click',
    function() {

      const email = $registerWantEmail.val()

      if(
        $registerWantEmail[0].checkValidity()
        &&
        validateEmail(email)
      ) {

        $registerWantEmail.prop('disabled', true)  
        $registerWantSendEmail.prop('disabled', true)

        message(
          $, 
          $wrapper,
          'Enviando...', 
          'Warn'
        )

        apiFetch({
          url: 'identify/subscriber/register',
          body: {
            email: email
          }
        })
        .then(result => {

          if(result.data.errors) {     

            const errors = 'Error. ' + 
            Object.keys(result.data.errors)
            .map(key => result.data.errors[key].map(e => e + ' '))
            .join(', ')            

            message(
              $, 
              $wrapper,
              errors, 
              'Error'
            )

            $registerWantEmail.prop('disabled', false)  
            $registerWantSendEmail.prop('disabled', false)

          } else {

            confirmCode(
              $,  
              $wrapper,
              email
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

          $registerWantEmail.prop('disabled', false)  
          $registerWantSendEmail.prop('disabled', false)
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
