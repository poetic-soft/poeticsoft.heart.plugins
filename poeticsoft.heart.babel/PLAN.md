Poeticsoft Heart Babel

Plan de Implementación: Arquitectura RAG Soberana en WORDPRESS OLLAMA CHROMA

1. Listado de Componentes e Instalación Básica
Para que el entorno funcione 100% en local y mantengas tu soberanía tecnológica, necesitamos tres piezas independientes en tu entorno de desarrollo:

A. Ollama (El motor de IA y Embeddings)
Es el encargado de ejecutar el modelo que traduce texto a vectores y, opcionalmente, el modelo generativo si decides no usar APIs externas.

Instalación rápida (Linux/Debian):

Bash
curl -fsSL https://ollama.com/install.sh | sh
Descarga del modelo de Embeddings indispensable:

Bash
ollama run nomic-embed-text
(Este comando descarga nomic-embed-text, un modelo optimizado para embeddings con una ventana de contexto nativa de 8192 tokens).

B. ChromaDB (La base de datos vectorial)
Chroma se distribuye principalmente como paquete de Python o imagen de Docker. Dado que estás en un entorno local, la forma más limpia y aislada de levantar el servidor Chroma sin ensuciar el sistema operativo es mediante Docker.

Requisito previo: Tener Docker e instalado en tu máquina.

Instalación y despliegue rápido:

Bash
docker run -d -p 8000:8000 chromadb/chroma
(Esto expone la API REST de Chroma en el puerto 8000 de tu localhost, lista para recibir peticiones HTTP desde PHP).

La alternativa nativa: Chroma como servicio de Python

Dado que en tus VPS y entornos locales sueles manejar Debian, puedes levantar Chroma instalándolo directamente a través de pip (el gestor de paquetes de Python) y dejarlo corriendo en segundo plano como un servicio más del sistema, igual que harías con Ollama.

Pasos de instalación nativa (en tu consola Debian/WSL):
Instalar dependencias del sistema y Python:

Bash
sudo apt update
sudo apt install python3 python3-pip python3-venv -y
Crear un entorno aislado para Chroma (para no romper nada del sistema):

Bash
mkdir ~/chromadb-server
cd ~/chromadb-server
python3 -m venv venv
source venv/bin/activate
Instalar ChromaDB:

Bash
pip install chromadb
Levantar el servidor:

Bash
chroma run --host 127.0.0.1 --port 8000
¡Y listo! Ya tienes exactamente el mismo servidor HTTP escuchando en el puerto 8000, pero corriendo de forma nativa sobre tu sistema, consumiendo muchísima menos memoria que si levantaras todo el demonio de Docker.

C. Guzzle HTTP (Componente PHP)
Aunque WordPress incluye wp_remote_post(), si vas a desarrollar scripts independientes en consola (cli) para hacer pruebas previas antes de empaquetarlo en un plugin, necesitarás Guzzle para gestionar las peticiones HTTP concurrentes y asíncronas de forma profesional.

Instalación vía Composer:

Bash
composer require guzzlehttp/guzzle
2. Simulación de las Piezas de Funcionalidad (Código Simulado / Pseudocódigo PHP 8.x)
Aquí tienes cómo interactúan las piezas del ciclo de vida que hemos diseñado. Este código abstracto sirve para validar la congruencia de los datos antes de escribir el código definitivo.

Pieza A: El Motor de Chunking y Protocolo de Longitud
Su única responsabilidad es recibir el Markdown, romperlo por encabezados y controlar de forma recursiva que ningún fragmento supere el límite establecido, arrastrando el solapamiento (overlap).

PHP
class ContentChunker {
    private int $maxChars = 1500;
    private int $overlap = 200;

    public function sliceMarkdown(string $markdown): array {
        // 1. Separación primaria por encabezados (H2 y H3)
        $sections = preg_split('/(?=## )/', $markdown);
        $finalChunks = [];

        foreach ($sections as $section) {
            if (strlen($section) <= $this->maxChars) {
                $finalChunks[] = trim($section);
            } else {
                // 2. Fragmentación recursiva si la sección es gigante
                $chunksSubseccion = $this->splitRecursively($section);
                $finalChunks = array_merge($finalChunks, $chunksSubseccion);
            }
        }
        return $finalChunks;
    }

    private function splitRecursively(string $text): array {
        // Lógica matemática para cortar por párrafos (\n\n) o puntos
        // inyectando el $this->overlap al principio del siguiente fragmento.
        return ["fragmento_largo_1_con_solapamiento", "fragmento_largo_2"];
    }
}
Pieza B: El Conector de Ollama (Generador de Vectores)
Recibe el texto plano de un chunk y pide a Ollama las coordenadas matemáticas.

PHP
class OllamaConnector {
    private string $url = 'http://localhost:11434/api/embeddings';

    public function getEmbedding(string $textChunk): array {
        // Petición HTTP POST a Ollama
        // Payload: { "model": "nomic-embed-text", "prompt": $textChunk }
        // Retorna: Array de 768 números flotantes
        return [0.0142, -0.2341, 0.8910, /*...*/];
    }
}
Pieza C: El Orquestador de Sincronización (El JSON final hacia Chroma)
Une el texto, el vector de Ollama y el post_id de WordPress, y los envía en lote (batch) a Chroma.

PHP
class ChromaOrchestrator {
    private string $chromaUrl = 'http://localhost:8000/api/v1/collections/';

    public function syncPostToChroma(int $postId, array $chunks, array $embeddings, array $metadataBase): bool {
        $payload = [
            'ids' => [],
            'embeddings' => [],
            'documents' => [],
            'metadatas' => []
        ];

        foreach ($chunks as $index => $text) {
            $payload['ids'][] = "cp_{$postId}_ch_{$index}";
            $payload['embeddings'][] = $embeddings[$index]; // El vector de Ollama
            $payload['documents'][] = $text;
            $payload['metadatas'][] = array_merge($metadataBase, [
                'post_id' => $postId,
                'chunk_index' => $index,
                'total_chunks' => count($chunks)
            ]);
        }

        // Ejecuta un único POST HTTP masivo al endpoint /add de Chroma
        return $this->sendToChroma($payload);
    }
}
3. Plan de Desarrollo Basado en Tests (Fases de Supervisión)
Para asegurar la congruencia y evitar que arrastremos errores de una fase a otra, avanzaremos validando cada paso con pequeños scripts de prueba unitaria en consola.

[ Fase 1: Chunking ] ──► [ Fase 2: Vectores ] ──► [ Fase 3: Chroma ] ──► [ Fase 4: Consulta ]
         │                        │                        │                        │
  ¿Divide bien y           ¿Ollama responde         ¿El JSON guarda         ¿Chroma devuelve
  hace overlap?            un array numérico?       y borra por ID?         los IDs correctos?
Fase 1: Test del Fragmentador (Sin IA)
Objetivo: Verificar que el algoritmo PHP trocea bien el texto.

Test en Consola: Creamos un string largo de 4000 caracteres con tres H2. Al pasar por ContentChunker, el script debe imprimir el número de trozos generados y comprobar visualmente que las últimas líneas del Trozo 1 se repiten al inicio del Trozo 2.

Criterio de éxito: Cero pérdida de texto y solapamiento exacto.

Fase 2: Test de Conectividad con Ollama
Objetivo: Asegurar que Ollama procesa las peticiones de PHP.

Test en Consola: Enviar un texto de prueba corto a través de tu clase PHP y hacer un var_dump() del resultado.

Criterio de éxito: El resultado debe ser un array puro de números flotantes, y el conteo de elementos (count($embedding)) debe ser exactamente el número de dimensiones del modelo (768 para Nomic).

Fase 3: Test de Idempotencia en Chroma (Ciclo CRUD completo)
Objetivo: Validar que el borrado preventivo funciona y no duplicamos datos en la base de datos vectorial al editar.

Test en Consola: 1. Ejecutar el script simulando guardar el post_id => 99 con un texto largo (genera 3 chunks). Verificar en la API de Chroma que existen los 3 registros.
2. Modificar el texto en tu script para que sea muy corto (ahora solo genera 1 chunk) y volver a ejecutar el guardado sobre el mismo post_id => 99.

Criterio de éxito: Al consultar Chroma, los 3 chunks antiguos deben haber desaparecido por completo y solo debe existir el nuevo chunk único. Si quedan 4 chunks, el sistema de borrado por metadatos está fallando.

Fase 4: Test de Recuperación Semántica (El RAG)
Objetivo: Validar que la consulta devuelve los posts más relevantes para tu estrategia SEO/GEO.

Test en Consola: Insertar tres posts distintos (uno de Debian, uno de Nginx, uno de Diseño WordPress). Hacer una consulta pasando la frase "Configuración del proxy inverso".

Criterio de éxito: El primer resultado devuelto en el array de documentos de Chroma debe ser obligatoriamente el de Nginx, y el objeto de metadatos debe contener el post_id original correcto de ese post.