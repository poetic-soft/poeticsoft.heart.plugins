import message from '../common/message'
import form from './forms'
import {
  apiFetch
} from '../common/utils'
import confirmPayStripeEnd from './do-confirmpay-stripe-end'
import payChannel from './do-paychannel'

export default ($, $wrapper, paytype) => {
  
  const postContentData = $wrapper.data()
  const $forms = $wrapper.find('.Forms.ShouldPay')  

  $forms.html(form({ form: 'confirmPayStripe' }))

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
        'Conectando con Stripe...', 
        'Warn'
      )

      apiFetch({
        url: 'pay/init',
        body: {
          type: 'stripe',
          email: postContentData.email,
          postid: postContentData.postid
        }
      })
      .then(data => { 
        
        window.open(
          data.stripesession.url,
          'STRIPE',
          'width=1080,height=800'
        );
      
        message(
          $, 
          $wrapper,
          'Esperando confirmación de pago, no cierres esta ventana...', 
          'Warn'
        )

        const waitStripe = setInterval(() => {

          apiFetch({
            url: 'pay/stripe/session/check',
            body: {
              stripesessionid: data.stripesession.id
            }
          })
          .then(result => {

            if(result.done) {

              clearInterval(waitStripe)

              confirmPayStripeEnd($)
            }
          })

        }, 3000)

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
}