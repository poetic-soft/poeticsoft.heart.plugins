import message from '../common/message'
import form from './forms'
import {
  apiFetch
} from '../common/utils'
import payChannel from './do-paychannel'

export default ($, $wrapper) => {
  
  const postContentData = $wrapper.data()
  const $forms = $wrapper.find('.Forms.ShouldPay')  

  $forms.html(form({ form: 'confirmPayBizum' }))

  const $confirmPay = $forms.find('.Form.ConfirmPay')
  const $confirmPayPay = $confirmPay.find('button.Pay')
  const $confirmPayOther = $confirmPay.find('a.OtherChannel')
  
  let allowBack = true
  $confirmPayOther.on(
    'click',
    function() {
    
      allowBack && payChannel($)
    }
  )

  $confirmPayPay.on(
    'click',
    function() {     

      $confirmPayPay.prop('disabled', true)
      $confirmPayOther.addClass('Disabled')
      
      allowBack = false
      
      message(
        $, 
        $wrapper,
        'Enviando...', 
        'Warn'
      )

      apiFetch({
        url: 'pay/init',
        body: {
          type: 'bizum',
          email: postContentData.email,
          postid: postContentData.postid
        }
      })
      .then(data => {   
      
        $forms.html(form({ 
          form: 'confirmPayBizumEnd',
          result: data
        }))

      })
      .catch(error => {

        console.log(error)

        message(
          $, 
          $wrapper,
          'Error de servidor, intentalo de nuevo, por favor.',
          'Error'
        )   

        $confirmPayPay.prop('disabled', false)
        $confirmPayOther.removeClass('Disabled')
        
        allowBack = true
      })
    }
  )

  $confirmPayOther.on(
    'click',
    function() {

      payChannel($)

      return false
    }
  )
}