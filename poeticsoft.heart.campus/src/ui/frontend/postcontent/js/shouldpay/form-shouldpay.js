export default data => { 
  
  const accessType = poeticsoft_content_payment_core_block_postcontent_accesstype_origin

  let payText = ''
  let accessButton = ''

  switch(accessType) {

    case 'gsheets':

      payText = data.adviceText   

      break

    default:

      payText = `
        Este contenido está disponible para suscriptores, 
        puedes obtener acceso a estos contenidos 
        por un periodo de <strong>12 meses</strong> a partir de la fecha de adquisición.  
      `
      accessButton = `
        <div class="Tools wp-block-button">
          <button 
            class="
              Buy
              wp-block-button__link 
              wp-element-button
            "
          >
            OBTENER ACCESO
          </button>
        </div>
      `
      break
  }

  return `
    <div class="Form ShouldPay">
      <div class="FormName">Should Pay</div>
      <div class="Explain">
        ${ payText }  
      </div>
      ${ accessButton }
      <div class="Message"></div>          
    </div>
  `
}