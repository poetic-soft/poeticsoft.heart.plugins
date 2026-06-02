import formIdentify from './form-identify'
import formUseTemporalCode from './form-usetemporalcode'
import formConfirmCode from './form-confirmcode'
import formRegisterShould from './form-register-should'
import formRegisterWant from './form-register-want'
import formRegisterConfirm from './form-register-confirm'

const forms = {
  identify: formIdentify,
  useTemporalCode: formUseTemporalCode,
  registerShould: formRegisterShould,
  registerWant: formRegisterWant,
  registerConfirm: formRegisterConfirm,
  confirmCode: formConfirmCode
}

export default data => forms[data.form](data)
