const dotenv = require('dotenv');
const dotenvExpand = require('dotenv-expand');
const env = dotenv.config();
dotenvExpand.expand(env);
const path = require('path')
const fs = require('fs');

const args = process.argv
const pluginName = 'poeticsoft-heart-ai'
const blockName = args[2] ||'blockbase'
const prodDest = path.join(process.env.plugins, 'poeticsoft-heart-ai/blocks', blockName)
const srcBuild = path.join(process.env.src, '/common/block/build')
const srcBlockJson = path.join(process.env.src, '/common/block/block.json')
const destBuild = path.join(process.env.plugins, pluginName, 'blocks', blockName, 'build')
const destBlockJson = path.join(process.env.plugins, pluginName, 'blocks', blockName, 'block.json')
const srcRender = path.join(process.env.src, '/common/block/render.php')
const destRender = path.join(process.env.plugins, pluginName, 'blocks', blockName, 'render.php');

console.log('blockName: ' +  blockName)
console.log('prodDest: ' +  prodDest)
console.log('srcBuild: ' +  srcBuild)
console.log('srcBlockJson: ' +  srcBlockJson)
console.log('destBuild: ' +  destBuild)
console.log('destBlockJson: ' +  destBlockJson)
console.log('srcRender: ' +  srcRender)
console.log('destRender: ' +  destRender)

try {
  fs.cpSync(srcBuild, destBuild, { recursive: true })
  console.error('Copiado Build a ' + destBuild)
} catch (error) {
  console.error('Hubo un error al copiar la carpeta:', error.message);
}

try {
  fs.cpSync(srcBlockJson, destBlockJson)
  
  // Leer y reemplazar variables en block.json
  const blockCategory = 'poeticsoft-heart'
  let content = fs.readFileSync(destBlockJson, 'utf8')
  content = content.replace(/{{name}}/g, blockName)
  content = content.replace(/{{title}}/g, blockName.charAt(0).toUpperCase() + blockName.slice(1))
  content = content.replace(/{{description}}/g, `Bloque ${blockName} para el ecosistema Poeticsoft Heart.`)
  content = content.replace(/{{icon}}/g, 'admin-plugins')
  content = content.replace(/{{category}}/g, blockCategory)
  
  fs.writeFileSync(destBlockJson, content)
  console.error('Copiado block.json a ' + destBlockJson)
} catch (error) {
  console.error('Hubo un error al procesar block.json:', error.message);
}

try {
  fs.cpSync(srcRender, destRender)
  console.error('Copiado render.php a ' + destRender)
} catch (error) {
  console.error('Hubo un error al copiar la carpeta:', error.message);
}




