export default data => {

  const needRegister = [
    'mailrelay'
  ]

  const accessType = poeticsoft_heart_campus_access_by
  const wantRegister = needRegister.includes(accessType) ?
  `
  <a 
    class="Extra NotRegistered"
    href="#"
  >
    Quiero suscribirme
  </a>
  `
  :
  ''

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
              ENVIAR
            </button>
          </div>
        </div>
      </div>
      ${ wantRegister }
      <div class="Message"></div>          
    </div>
  `
}