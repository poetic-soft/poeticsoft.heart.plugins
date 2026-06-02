import message from '../common/message'
import form from './forms'
import confirmPayStripe from './do-confirmpay-stripe'
import confirmPayTransfer from './do-confirmpay-transfer'
import confirmPayBizum from './do-confirmpay-bizum'

const confirmPay = {
  stripe: confirmPayStripe,
  transfer: confirmPayTransfer,
  bizum: confirmPayBizum
}

export default ($, $wrapper) => {
  
  const $forms = $wrapper.find('.Forms.ShouldPay')  

  $forms.html(form({ form: 'payChannel'}))

  const $paychannel = $forms.find('.Form.PayChannel')
  const $inputChannels = $paychannel.find('.Channel input[type=radio]')
  const $paychannelpay = $paychannel.find('button.Pay')

  $inputChannels.on(
    'change',
    function() {

      $paychannelpay.prop('disabled', false)
    }
  )

  $paychannelpay.on(
    'click',
    function() {

      const typeSelected = $paychannel
      .find('.Channel input[type=radio]:checked')
      .val()

      $inputChannels.prop('disabled', true)
      $paychannelpay.prop('disabled', true)

      message(
        $, 
        $wrapper,
        'Conectando...', 
        'Warn'
      )
      
      confirmPay[typeSelected]($)
    }
  )
}