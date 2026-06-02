import formShouldPay from './form-shouldpay'
import formPayChannel from './form-paychannel'
import formConfirmPayStripe from './form-confirmpay-stripe'
import formConfirmPayStripeEnd from './form-confirmpay-stripe-end'
import formConfirmPayTransfer from './form-confirmpay-transfer'
import formConfirmPayTransferEnd from './form-confirmpay-transfer-end'
import formConfirmPayBizum from './form-confirmpay-bizum'
import formConfirmPayBizumEnd from './form-confirmpay-bizum-end'

const forms = {
  shouldPay: formShouldPay,
  payChannel: formPayChannel,
  confirmPayStripe: formConfirmPayStripe,
  confirmPayStripeEnd: formConfirmPayStripeEnd,
  confirmPayTransfer: formConfirmPayTransfer,
  confirmPayTransferEnd: formConfirmPayTransferEnd,
  confirmPayBizum: formConfirmPayBizum,
  confirmPayBizumEnd: formConfirmPayBizumEnd
}

export default data => forms[data.form](data)