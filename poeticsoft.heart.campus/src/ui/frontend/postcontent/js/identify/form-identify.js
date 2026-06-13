export default (data) => {
    return `
    <div class="Form Identify">
      <div class="FormName">Identify</div>
      <div class="Explain">
        Identifícate para acceder a los contenidos.
      </div>
      <div class="Fields">
        <div class="Field Email">
          <input
            class="Email"
            type="email"
            placeholder="Tu E-mail"
            name="user-email"
          />    
          <div class="Tools wp-block-button">
            <button 
              class="
                SendEmail
                wp-block-button__link 
                wp-element-button
              "
              disabled="disabled"
            >
              ENTRAR
            </button>
          </div>
        </div>
      </div>
      <div class="Message"></div>          
    </div>
  `;
};
