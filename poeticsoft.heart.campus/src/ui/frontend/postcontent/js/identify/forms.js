import formIdentify from './form-identify'
import formConfirmCode from './form-confirmcode'
import formRegisterShould from './form-register-should'
import formRegisterWant from './form-register-want'
import formRegisterConfirm from './form-register-confirm'

const forms = {
  identify: formIdentify,
  registerShould: formRegisterShould,
  registerWant: formRegisterWant,
  registerConfirm: formRegisterConfirm,
  confirmCode: formConfirmCode
}

export default data => forms[data.form](data)
