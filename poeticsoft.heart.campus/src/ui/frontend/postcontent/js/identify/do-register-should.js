import forms from './forms'
import message from '../common/message'
import confirmCode from './do-confirmcode'
import registerWant from './do-register-want'
import {
  apiFetch
} from '../common/utils'

export default ($, $wrapper, email) => {
  
  const $forms = $wrapper.find('.Forms.Identify')  
  $forms.find('.Form').remove()

  $forms.html(forms({ 
    form: 'registerShould',
    email: email
  }))  

  const $registerShould = $forms.find('.Form.RegisterShould')
  const $registerShouldConfirmCode = $registerShould.find('button.RegistryEmail')  
  const $registerShouldOtherMail = $registerShould.find('a.OtherMail') 

  $registerShouldConfirmCode.on(
    'click',
    function() {

      $registerShouldConfirmCode.prop('disabled', true)  
    
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

          $registerShouldConfirmCode.prop('disabled', false) 

        } else {

          confirmCode(
            $,  
            $wrapper,
            email, 
            result.usercode
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

        $registerShouldConfirmCode.prop('disabled', false) 
      })
    }
  )

  $registerShouldOtherMail.on(
    'click',
    function() {
      
      registerWant($, $wrapper)      

      return false
    }
  )
}
