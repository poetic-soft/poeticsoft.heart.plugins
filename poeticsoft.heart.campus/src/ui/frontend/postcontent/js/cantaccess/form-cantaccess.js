export default data => { 

  const accessText = `
    Solicita tu registro para acceder a este contenido.  
  `
  return `
    <div class="Form CantAccess">
      <div class="FormName">Cant Access</div>
      <div class="Explain">
        ${ accessText }  
      </div>         
    </div>
  `
}