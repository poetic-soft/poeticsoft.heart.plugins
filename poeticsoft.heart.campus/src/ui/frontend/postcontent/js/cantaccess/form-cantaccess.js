export default data => { 

  const adviceText = `
    No tienes acceso a este contenido, solicítalo a la administración del campus.  
  `
  return `
    <div class="Form CantAccess">
      <div class="FormName">Cant Access</div>
      <div class="Explain">
        ${ data.adviceText || adviceText }  
      </div>         
    </div>
  `
}