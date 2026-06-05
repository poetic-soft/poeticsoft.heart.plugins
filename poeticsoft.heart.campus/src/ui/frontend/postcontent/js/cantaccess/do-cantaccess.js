import form from './forms'

export default ($, $wrapper) => {

  const $adviceText = $wrapper.find('.AdviceText')
  const adviceText = $adviceText.html()  
  const $forms = $wrapper.find('.Forms.CantAccess')  

  $forms.html(form({ 
    form: 'cantAccess',
    adviceText: adviceText
  }))
}