import formCantAccess from './form-cantaccess'

const forms = {
  cantAccess: formCantAccess
}

export default data => forms[data.form](data)