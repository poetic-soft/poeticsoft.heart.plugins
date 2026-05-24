# Flujo de Desarrollo de Bloques (Webpack Edition)

Este documento detalla el proceso para crear y compilar bloques de Gutenberg dentro del ecosistema Poeticsoft Heart.

## 1. Código Fuente (Frontend)

El desarrollo del bloque comienza en el entorno de desarrollo. Debes crear una nueva carpeta para tu bloque en `dev/src/blocks/[nombre-bloque]`.

**Recomendación:** Utiliza el bloque `./dev/src/blocks/blockbase` como plantilla. Puedes copiar su contenido para tener una estructura base funcional de `edit.js`, `view.js`, `edit.scss` y `view.scss` ya configurada para el ecosistema.

---

## 2. Creación del Destino (Scaffolding)

Es obligatorio crear la estructura del bloque en el destino antes de compilar. Esto copia los archivos base (`block.json`, `render.php`) y prepara el directorio `build/`.

### Comando
```bash
node ./dev/scripts/newblock.js [nombre-bloque]
```

### Parámetros
- `nombre-bloque`: Nombre del directorio del bloque (ej: `voiceinterface`).

> **Nota sobre Categorías:** El script asignará la categoría "poeticsoft-heart-ai".

---

## 3. Configuración del Bloque (Backend)

Una vez que el script de scaffolding ha creado la estructura en la carpeta de producción, es responsabilidad del desarrollador adaptar los archivos base generados a las necesidades del bloque:

*   **`block.json`**: Debes abrir este archivo en el destino (ej. `prod/plugins/poeticsoft-heart/blocks/[nombre-bloque]/block.json`) y definir los `attributes`, `supports` y cualquier otro metadato necesario para el funcionamiento del bloque en el editor y el frontend.
*   **`render.php`**: Para bloques dinámicos, debes editar este archivo en el destino para implementar la lógica en PHP que dibuje el DOM del bloque en el frontend.

---

## 4. Compilación (Webpack)

La compilación es dinámica. Se utiliza un string delimitado por guiones en la variable `--env` para inyectar la configuración en tiempo de ejecución.

### Comando Genérico
```bash
npx webpack --env block-[blockname]
```

### Desglose del String `--env`
1. `block`: Indica que es una compilación de bloque (aplica externals de WP y añade `/build`).
2. `blockname`: nome qeu se utilizará para el bloque.

### Ejemplo Real
```bash
npx webpack --env block-blockbase
```

---

## 5. Identificadores Únicos (Persistencia y Duplicación)

Para asegurar que cada instancia de un bloque tenga un ID único (necesario para selectores CSS específicos o lógica JS en el frontend), es obligatorio implementar el siguiente patrón en el archivo `edit.js`.

### Atributos Necesarios
En el `block.json` (en el destino), el bloque debe tener definidos al menos estos atributos:
```json
"attributes": {
    "blockId": { "type": "string" },
    "refClientId": { "type": "string" }
}
```

### Lógica en `edit.js`
Se utiliza un `useEffect` para detectar si el bloque es nuevo o ha sido duplicado. Si el `refClientId` guardado no coincide con el `clientId` actual proporcionado por Gutenberg, se genera un nuevo `blockId`.

```javascript
import { v4 as uuidv4 } from 'uuid';
const { useEffect } = wp.element;

export default function Edit( { clientId, attributes, setAttributes } ) {
    const { blockId, refClientId } = attributes;

    useEffect(() => {
        // Si no hay ID o el bloque ha sido duplicado (clientId != refClientId)
        if (!blockId || refClientId !== clientId) {
            setAttributes({ 
                blockId: uuidv4(),
                refClientId: clientId
            });
        }
    }, [clientId]); // Dependencia del clientId para reaccionar a cambios
    
    // ... resto del componente
}
```

Este patrón garantiza que al duplicar un bloque en el editor, la copia reciba automáticamente un nuevo `blockId` único, evitando conflictos.

---

## Notas de Diseño

- **Flexibilidad vs Shortcuts:** Se ha optado por comandos genéricos en lugar de scripts en `package.json` para evitar la saturación del archivo a medida que el número de bloques crezca.
- **Validación Automática:** Webpack verificará la existencia del directorio de destino. Si no existe, recordará que debe ejecutarse primero `newblock.js`.
- **Assets:** Los archivos compilados (`.js`, `.css`) y sus mapas de fuente se generan en la subcarpeta `build/` del bloque de destino, listos para ser registrados por WordPress mediante el `block.json`.