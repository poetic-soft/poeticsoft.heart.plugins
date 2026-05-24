export default data => {

  // value="${ data.code }"

  return `
    <div class="Form ConfirmCode">
      <div class="FormName">ConfirmCode</div>
      <div class="Explain">
        Para confirmar tu acceso hemos enviado un código 
        a tu email, por favor introduce el código 
        recibido en el siguiente campo.
      </div>
      <div class="Fields">
        <div class="Field Code">
          <input
            class="Code"
            type="text"
            placeholder="Código recibido"
            name="confirm-code"
          />
          <div class="Tools wp-block-button">
            <button 
              class="
                ConfirmCode
                wp-block-button__link 
                wp-element-button
              "
            >
              CONFIRMAR
            </button>
          </div>
        </div>
      </div>
      
      <a 
        class="Extra ResendCode"
        href="#"
      >
        Reenviar el código
      </a>
      <div class="Message"></div>          
    </div>
  `
}