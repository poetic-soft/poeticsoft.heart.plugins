export default data => {

  return `
    <div class="Form RegisterConfirm">
      <div class="FormName">RegisterConfirm</div>
      <div class="Explain">
        Tu mail se ha registrado, recibiras un correo con un código para confirmar tu suscripción, por favor introduce el código recibido en el siguiente campo.
      </div>
      <div class="Fields">
        <div class="Field Code">
          <input
            class="Code"
            type="text"
            placeholder="Código recibido"
            name="confirm-code"
          />
        </div>
      </div>
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
      <div class="Message"></div>          
    </div>
  `
}