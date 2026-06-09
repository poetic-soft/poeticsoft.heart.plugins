export default data => {

  const adviceText = `
    Identificate con tu email para acceder a los contenidos.  
  `

  return `
    <div class="Form Ask">
      <div class="FormName">Ask</div>
      <div class="Explain">
        ${ data.adviceText || adviceText }  
      </div>  
      <div class="Message"></div>          
    </div>
  `
}