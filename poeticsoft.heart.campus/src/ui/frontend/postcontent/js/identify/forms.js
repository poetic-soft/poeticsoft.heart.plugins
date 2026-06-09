import formIdentify from './form-identify'
import formConfirmLink from './form-confirmlink'
import formAsk from './form-ask'

const forms = {
  identify: formIdentify,
  confirmLink: formConfirmLink,
  ask: formAsk
}

export default data => forms[data.form](data)
