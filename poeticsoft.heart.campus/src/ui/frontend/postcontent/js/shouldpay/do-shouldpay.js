import form from './forms'
import payChannel from './do-paychannel'

export default ($, $wrapper) => {

  const $adviceText = $wrapper.find('.AdviceText')
  const adviceText = $adviceText.html()  
  const $forms = $wrapper.find('.Forms.ShouldPay')  

  $forms.html(form({ 
    form: 'shouldPay',
    adviceText: adviceText
  }))

  const $shouldPay = $forms.find('.Form.ShouldPay')
  const $shouldPayBuy = $shouldPay.find('button.Buy')

  $shouldPayBuy.on(
    'click',
    function() {

      payChannel($)
    }
  )
}