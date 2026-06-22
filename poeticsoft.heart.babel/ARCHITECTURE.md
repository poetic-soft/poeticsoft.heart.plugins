# Arquitectura Técnica: Poeticsoft Heart Babel

Este documento proporciona una descripción exhaustiva y detallada de la arquitectura, patrones de diseño, flujos de datos e integraciones de **Poeticsoft Heart Babel**, un sistema nativo y soberano de gobernanza del conocimiento y motor RAG (Retrieval-Augmented Generation) integrado en WordPress.

---

## 1. Principios Arquitectónicos Fundamentales

El plugin ha sido desarrollado bajo estrictas directrices de ingeniería de software corporativa, priorizando los siguientes principios:

1.  **Soberanía Tecnológica (Local-First):** Diseñado para operar 100% de forma local y soberana mediante integraciones directas con **Ollama** (motor de IA local) y **ChromaDB v2** (base de datos vectorial local), eliminando la dependencia de servicios SaaS externos y protegiendo la confidencialidad de la información.
2.  **Desacoplamiento Absoluto (SOLID):** Cumplimiento estricto del _Principio de Inversión de Dependencias_. Los motores de IA y almacenamiento vectorial se comunican mediante interfaces abstractas, permitiendo cambiar de proveedor tecnológico (p. ej., migrar de ChromaDB a Pinecone, o de Ollama a OpenAI) modificando únicamente una línea de configuración sin alterar el código de lógica de negocio.
3.  **Context-Aware Routing (Rendimiento Óptimo):** Separación modular de la lógica según el contexto de WordPress (Administración, Frontend público y API REST). Las clases de soporte de cada contexto solo se instancian cuando WordPress se ejecuta en dicho ámbito, minimizando el consumo de CPU y memoria.
4.  **Idempotencia Garantizada:** Toda inserción o actualización de documentos o posts realiza de forma obligatoria un borrado predictivo previo de los vectores antiguos correspondientes a dicho identificador, evitando la redundancia e inconsistencia en la base de datos vectorial.

---

## 2. Estructura de Directorios del Repositorio

El plugin de WordPress está encapsulado de forma limpia en el subdirectorio `poeticsoft-heart-babel/` para facilitar su portabilidad y despliegue:

```text
poeticsoft-heart-babel/
├───poeticsoft-heart-babel.php          # Inicializador y cabecera del plugin en WordPress
├───composer.json                       # Definición de dependencias (Guzzle, smalot/pdfparser)
├───composer.lock                       # Bloqueo de versiones de Composer
├───vendor/                             # Dependencias autocompiladas de PHP
├───assets/                             # Recursos estáticos encolados condicionalmente
│   ├───admin/                          # Estilos y JS exclusivos para paneles de administración
│   └───front/                          # Estilos exclusivos para el frontend público
├───views/                              # Capa de presentación (MVC - View engine)
│   └───admin/                          # Vistas HTML/PHP para los paneles de administración
└───classes/                            # Lógica de Negocio con Autoloading PSR-4 (Namespace Poeticsoft\Heart\Babel)
    ├───API.php                         # API - Clase pública externa para integraciones de Babel
    ├───Babel.php                       # Babel - Singleton y Service Container (Controlador central)
    ├───Updater.php                     # Updater - Gestor de actualizaciones remotas autohospedadas
    ├───Admin/                          # Admin - Contexto de administración de WordPress
    │   ├───Admin.php                   # Admin - Inicializador y orquestador principal del administrador
    │   ├───AbstractPage.php            # AbstractPage (Clase Base) - Molde abstracto para páginas de menú
    │   ├───MenuController.php          # MenuController - Gestor del menú de administración de Babel en WP
    │   ├───PostEditorController.php    # PostEditorController - Capturador de eventos de posts (save, delete, trash)
    │   └───Pages/                      # Subclases de Páginas de Administración (heredan de AbstractPage)
    │       ├───DashboardPage.php       # DashboardPage - Panel de control principal con métricas y logs
    │       ├───IngestionPage.php       # IngestionPage - Interfaz para escáner manual de archivos y uploads
    │       └───SettingsPage.php        # SettingsPage - Configuración de modelos de IA, proveedores y depuración
    ├───Chunking/                       # Chunking - Segmentación de textos
    │   └───ContentChunker.php          # ContentChunker - Fragmentador semántico y extractor de YAML Front Matter
    ├───Database/                       # Database - Almacenamiento local
    │   └───Database.php                # Database - Administrador de tablas personalizadas (p. ej., logs del sistema)
    ├───Embeddings/                     # Embeddings - Representaciones vectoriales
    │   ├───EmbeddingGeneratorInterface.php # EmbeddingGeneratorInterface (Interfaz) - Reglas para la generación de embeddings
    │   └───OllamaGenerator.php         # OllamaGenerator (Implementación) - Integración nativa con Ollama (/api/embed)
    ├───Frontend/                       # Frontend - Contexto público web
    │   ├───Frontend.php                # Frontend - Inicializador y cargador de lógicas del frontend público
    │   └───FrontendAssets.php          # FrontendAssets - Encolado y carga condicional de estilos y scripts web
    ├───Indexing/                       # Indexing - Orquestación del RAG
    │   └───IndexManager.php            # IndexManager - Coordinador de fragmentación, embeddings y guardado vectorial
    ├───Ingestion/                      # Ingestion - Entrada de conocimiento
    │   ├───Ingestor.php                # Ingestor - Orquestador del buzón físico de archivos (raw, processed, failed)
    │   ├───PostIngestor.php            # PostIngestor - Gestor de importación de posts de WP con tag 'babel'
    │   ├───HtmlToMarkdown.php          # HtmlToMarkdown - Convertidor de HTML (Gutenberg/Clásico) a Markdown limpio
    │   └───Converters/                 # Conversores de Archivos por Extensión (patrón Strategy)
    │       ├───ConverterInterface.php  # ConverterInterface (Interfaz) - Estándar para conversores a Markdown
    │       ├───DocxConverter.php       # DocxConverter (Implementación) - Extractor ultra veloz de archivos XML .docx
    │       ├───MarkdownConverter.php   # MarkdownConverter (Implementación) - Normalizador de archivos .md y .txt
    │       └───PdfConverter.php        # PdfConverter (Implementación) - Extractor de PDFs mediante smalot/pdfparser
    ├───Languages/                      # Languages - Internacionalización
    │   └───Languages.php               # Languages - Cargador y gestor de archivos de traducción (i18n)
    ├───Rest/                           # Rest - Interfaces de API REST de WordPress
    │   ├───Rest.php                    # Rest - Inicializador y registrador principal de rutas de la API REST
    │   ├───AbstractEndpoint.php        # AbstractEndpoint (Clase Base) - Molde abstracto para endpoints REST de Babel
    │   └───Endpoints/                  # Subclases de Endpoints (heredan de AbstractEndpoint)
    │       ├───SystemEndpoint.php      # SystemEndpoint - Endpoint de administración, estado del sistema e indexados
    │       └───UploadEndpoint.php      # UploadEndpoint - Endpoint para subir archivos binarios directos por API
    ├───UI/                             # UI - Helpers visuales
    │   └───Ui.php                      # Ui - Generador de componentes de interfaz reutilizables del panel
    ├───Validation/                     # Validation - Seguridad y sanitización
    │   └───Validation.php              # Validation - Filtros y validación estricta de variables del sistema
    ├───VectorStore/                    # VectorStore - Almacenamiento vectorial
    │   ├───VectorDatabaseInterface.php # VectorDatabaseInterface (Interfaz) - Reglas para inserción, borrado y búsqueda
    │   └───ChromaDatabase.php          # ChromaDatabase (Implementación) - Conector HTTP para ChromaDB v2 (API v2)
    └───View/                           # View - Motor de plantillas
        └───View.php                    # View - Controlador del motor de renderizado de vistas del backend
```

---

## 3. Patrón Service Container (Babel.php)

El núcleo del plugin está gobernado por la clase `Babel` (`classes/Babel.php`), la cual implementa de manera unificada el patrón **Singleton** (única instancia de inicialización) y **Service Container** (contenedor de servicios con inyección diferida/lazy-load).

### Registro de Servicios

En lugar de instanciar componentes de forma rígida con `new Class()`, el sistema utiliza el Service Container para registrar, instanciar y recuperar de manera única (_Singleton compartido_) cualquier servicio bajo demanda:

```php
use Poeticsoft\Heart\Babel\Babel;
use Poeticsoft\Heart\Babel\Ingestion\Ingestor;

// Recupera la instancia única (compartida) del Ingestor.
// Si no existía, la instancia de forma automatizada.
$ingestor = Babel::get( Ingestor::class );
```

### Context-Aware Routing

La inicialización en `Babel::init()` redirige la ejecución a módulos desacoplados según el entorno actual de WordPress:

```php
private function init() {
        if ( is_admin() ) {
                $this->init_admin(); // Carga menús, páginas y metaboxes
        }

        $this->init_rest(); // Registra siempre los endpoints REST bajo el hook rest_api_init

        if ( ! is_admin() && ! ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
                $this->init_frontend(); // Carga shortcodes y activos públicos
        }

        $this->init_global(); // Carga traductores, base de datos y tareas autónomas (Cron)
}
```

---

## 4. Sub-sistema de Ingesta y Normalización

Babel dispone de una arquitectura híbrida de ingesta capaz de recibir información desde dos flujos independientes, normalizándola en un estándar unificado de archivos Markdown con cabecera **YAML Front Matter**:

```text
[Buzón de Archivos raw/]  ───► [Ingestor.php + Converters] ───┐
                                                            ├───► [Markdown + YAML unificado] ───► [ContentChunker.php]
[Entradas de WordPress]   ───► [PostIngestor.php + HTML]   ───┘
```

### A. Ingesta de Archivos y Carpetas (Ingestor.php)

- **Almacenamiento Físico:** Ubicado de forma dinámica en la carpeta de subidas de WordPress: `/wp-content/uploads/babel/`.
- **Estructura del Almacén:**
    - `raw/`: Bandeja de entrada donde se depositan archivos crudos individuales o directorios jerárquicos completos.
    - `converted/`: Contiene las versiones `.md` unificadas tras pasar por los conversores.
    - `processed/`: Archivo histórico donde se mueven automáticamente los archivos crudos originales indexados con éxito.
    - `failed/`: Carpeta de cuarentena donde se aíslan de forma segura los archivos corruptos o vacíos (p. ej., PDFs puramente escaneados como imagen sin capa de texto).
- **Converters (Patrón Strategy):**
    - `MarkdownConverter.php`: Lee `.md` y `.txt`, forzando codificación UTF-8 limpia y normalizando saltos de línea a formato Unix LF (`\n`).
    - `PdfConverter.php`: Utiliza la librería nativa de PHP **`smalot/pdfparser`** instalada vía Composer. Es 100% portable y segura, aislando fallos de encriptación o corrupción.
    - `DocxConverter.php`: Extractor nativo ultra veloz que descomprime el `.docx` mediante `ZipArchive` y procesa directamente el XML principal `word/document.xml` mediante `SimpleXMLElement`, evitando librerías pesadas de terceros.
- **Limpieza de Directorios:** Tras procesar un archivo y moverlo, el Ingestor limpia de forma recursiva los subdirectorios que hayan quedado vacíos en `raw/` para mantener impecable la bandeja de entrada.

### B. Ingesta de Contenido de WordPress (PostIngestor.php)

- **Elegibilidad:** Procesa de forma unificada cualquier post, página o Custom Post Type que contenga la etiqueta (**`post_tag`**) con el slug **`babel`**.
- **Soporte de Visibilidad Avanzado:** Admite de forma nativa e indexa entradas en estados **`publish`** (publicados), **`private`** (privados) y **`future`** (programados/futuros), permitiendo indexar conocimiento interno confidencial y planificaciones futuras.
- **Conversor de HTML (`HtmlToMarkdown.php`):** Una clase utilitaria basada en expresiones regulares refinadas que traduce el HTML (clásico o bloques de Gutenberg) a Markdown plano, eliminando comentarios de bloques de WordPress, scripts e inline CSS.
- **Sincronización en Tiempo Real (`PostEditorController.php`):**
    - Mapeado al hook `save_post` para sincronizar o regenerar el Markdown al instante al guardar (ignorando autoguardados y revisiones).
    - Mapeado a `wp_trash_post` y `before_delete_post` para borrar físicamente el Markdown y sus vectores del RAG al instante si el post es enviado a la papelera o eliminado.

---

## 5. El Esquema de Metadatos YAML Normalizado

Todos los conversores del plugin inyectan un encabezado estricto de **YAML Front Matter** al inicio de cada archivo Markdown. Esto garantiza que el motor vectorial asocie de forma idéntica los metadatos de búsqueda para la trazabilidad de fuentes:

```yaml
---
id: '125' # ID numérico de WordPress o Hash MD5 del archivo relativo
title: 'Título de la Información' # Título legible del post o del documento original
type: 'pdf' # Extensión de origen ('pdf', 'docx', 'page', 'post'...)
status: 'private' # Estado de visibilidad: 'publish', 'private' o 'future'
author: 'Alberto' # Autor de la entrada o 'System' si es archivo subido
date: '2026-06-18 20:14:00' # Fecha de creación o modificación del archivo
url: 'https://site.com/permalink/' # Enlace de origen (permalink de WordPress o vacío)
source: 'wordpress' # Procedencia: 'wordpress' o 'file'
path: 'playcorp/archivo.pdf' # Ruta de subida relativa original (o vacío si es WP)
---
# Título de la Información

[Cuerpo en Markdown limpio...]
```

---

## 6. Motor RAG e Indexación Vectorial

La capa del motor RAG está totalmente aislada de la base de datos física de WordPress y de los servicios de IA de terceros, garantizando la mantenibilidad a largo plazo:

```text
                  ┌──► ContentChunker (Segmentación + Extracción YAML)
                  │
[IndexManager] ───┼──► EmbeddingGeneratorInterface (Abstracción de Embeddings) ──► Ollama (nomic-embed-text)
                  │
                  └──► VectorDatabaseInterface (Abstracción de Vectores) ────────► ChromaDB v2 REST API
```

### A. Fragmentador Semántico (ContentChunker.php)

- **Extractor:** Detecta la firma `---`, extrae los campos clave-valor del YAML en un array de PHP y remueve el bloque de metadatos del cuerpo del texto para no ensuciar los embeddings de la IA.
- **Recursive Character Chunking:** Divide el cuerpo del texto plano en fragmentos de caracteres ajustables (p. ej., 1000 caracteres) con un solapamiento regulable (p. ej., 200 caracteres).
- **Separadores Semánticos Regresivos:** En lugar de cortar palabras a la mitad, evalúa de forma regresiva la posición ideal de corte priorizando:
    1.  Límites de párrafo (`\n\n`)
    2.  Saltos de línea (`\n`)
    3.  Punto y final de frase (`. `)
    4.  Espacio entre palabras (` `)
- **Estructura de Chunk:** Devuelve una lista de fragmentos, donde cada uno contiene su texto y su array de metadatos, inyectando de forma automática los índices correlativos `chunk_index` y `total_chunks`.

### B. Abstracción de Embeddings (`EmbeddingGeneratorInterface.php`)

Cualquier generador de embeddings debe implementar esta interfaz para traducir texto plano a vectores de números reales (arrays de floats).

- **`OllamaGenerator.php` (Implementación por Defecto):**
    - Establece conexión por HTTP con el puerto local `11434` de Ollama mediante Guzzle.
    - Utiliza el endpoint moderno de Ollama **`/api/embed`** para enviar toda la lista de fragmentos de texto en un **único lote (Batch Generation)**, acelerando el procesamiento y optimizando el consumo de CPU.
    - Modelo por defecto: `nomic-embed-text` (dimensión de salida de vector: `768`).

### C. Abstracción de Base de Datos Vectorial (`VectorDatabaseInterface.php`)

Establece las reglas para la indexación, borrado por filtros y búsquedas de similitud en la base de datos vectorial.

- **`ChromaDatabase.php` (Implementación por Defecto - API v2):**
    - Establece conexión por HTTP con el puerto local `8000` de ChromaDB mediante Guzzle.
    - **Adaptación a ChromaDB v0.6+ (API v2):** Dado que las versiones modernas de ChromaDB deprecian de forma absoluta la API v1, el conector está programado nativamente bajo el esquema de la **API v2** (`/api/v2/tenants/default_tenant/databases/default_database/...`).
    - **Idempotencia con borrado preventivo:** Antes de añadir nuevos fragmentos de un documento, invoca a `/delete` enviando el filtro `where => ['id' => $doc_id]`. Esto asegura que si re-guardas o re-subes un documento modificado, los fragmentos viejos desaparezcan de ChromaDB de inmediato, evitando inconsistencias semánticas.

### D. El Orquestador de Indexado (IndexManager.php)

Encapsula el flujo completo de indexación:

1.  Recibe el archivo `.md` de `converted/` o `posts/`.
2.  Llama al `ContentChunker` para obtener los fragmentos y extraer los metadatos YAML.
3.  Borra en ChromaDB v2 los fragmentos antiguos bajo el metadato `id` del documento.
4.  Genera en un solo lote (_batch_) los embeddings vectoriales llamando al Generador activo.
5.  Formatea los datos forzando el tipado de los metadatos como cadena de texto (`string`) para estricta compatibilidad con ChromaDB.
6.  Inserta los IDs de chunks (`{doc_id}_chunk_{index}`), vectores, textos y metadatos en lote en la colección vectorial activa.

### E. Servicios e Infraestructura de IA Local (Systemd)

Para asegurar la total soberanía tecnológica (Local-First) y el funcionamiento ininterrumpido del motor de embeddings y la base de datos vectorial en entornos Debian, tanto **Ollama** como **ChromaDB** están orquestados y gobernados por **Systemd**, garantizando que arranquen automáticamente en el boot y se auto-recuperen ante caídas.

#### 1. Servidor de Embeddings (Ollama)

Ollama actúa como el motor que traduce el texto a vectores de alta dimensión en el puerto `11434`.

- **Fichero de Configuración de Systemd (`/etc/systemd/system/ollama.service`):**
    - Este servicio se instala y habilita de forma nativa por el instalador oficial de Ollama.
    - **Estado en el boot:** Habilitado (`enabled`), asegurando que el servicio se levante automáticamente.
    - **Comando de servicio ejecutado:** `/usr/local/bin/ollama serve`
    - **Modelo requerido localmente:** `nomic-embed-text` (debe ser pre-descargado ejecutando `ollama run nomic-embed-text` para que los endpoints de embeddings de Babel respondan correctamente).

#### 2. Base de Datos Vectorial (ChromaDB)

ChromaDB almacena y busca los vectores resultantes para RAG en el puerto `8000`. Se levanta de forma nativa desde un entorno de Python aislado.

- **Ruta del Entorno Virtual (venv):** `/root/chromadb-server/venv/`
- **Directorio de Trabajo:** `/root/chromadb-server/`
- **Ruta de Persistencia (Base de Datos):** La base de datos persistente SQLite de ChromaDB está ubicada directamente dentro del plugin en `/home/heart/dev/plugins/poeticsoft.heart.babel/chroma/`. Esto asegura que los vectores de Babel permanezcan encapsulados con el código del proyecto.
- **Fichero de Configuración de Systemd (`/etc/systemd/system/chromadb.service`):**

    ```ini
    [Unit]
    Description=ChromaDB Vector Database Server
    After=network.target

    [Service]
    Type=simple
    User=root
    WorkingDirectory=/root/chromadb-server
    ExecStart=/root/chromadb-server/venv/bin/chroma run --host 127.0.0.1 --port 8000 --path /home/heart/dev/plugins/poeticsoft.heart.babel/chroma
    Restart=always
    RestartSec=5

    [Install]
    WantedBy=multi-user.target
    ```

Ambos servicios garantizan de forma unificada que:

1.  Toda la infraestructura local de IA esté lista inmediatamente tras un reinicio del servidor físico/VPS.
2.  Cualquier caída accidental en los servicios se recupere en menos de 5 segundos de forma totalmente autónoma.
3.  La información e historial de vectores persista de manera segura, portable y localizada dentro de la estructura de ficheros del plugin.

---

## 7. Automatización del Escáner (System Cron)

Para evitar la limitación de ejecución "lazy-loading" nativa del sistema de Cron de WordPress (el cual solo se despierta si la web recibe visitas reales, algo que no ocurre en entornos de desarrollo), Babel adopta una integración híbrida:

1.  **WordPress Hourly Cron:** En `Ingestor::init()`, el plugin programa una tarea nativa de WordPress horaria (`hourly`) llamada `poeticsoft_heart_babel_scan_raw_cron` acoplada al procesamiento general de la bandeja de entrada `raw/`.
2.  **Desprogramación de Seguridad:** Se registra en `poeticsoft-heart-babel.php` un gancho `register_deactivation_hook` que elimina de forma limpia el evento cron en el núcleo de WordPress al desactivar el plugin, manteniendo la base de datos limpia.
3.  **Disparador de Sistema (Linux Crontab):** Para que las tareas se ejecuten de manera garantizada cada 5 minutos en entornos de desarrollo local sin tráfico, se configura una tarea programada real en el sistema operativo Linux:
    ```bash
    # Ejecuta el motor de wp-cron.php por curl de forma silenciosa cada 5 minutos
    */5 * * * * curl -s -k -L https://heart.poeticsoft.com/wp-cron.php?doing_wp_cron > /dev/null 2>&1
    ```

---

## 8. Guía para Futuras Ampliaciones y Mejoras

La arquitectura desacoplada de Babel permite expandir el sistema de forma muy sencilla:

### Cómo añadir un nuevo conversor de archivos (p. ej., `.xlsx` / Excel)

1.  Crea la clase `XlsxConverter` dentro de `classes/Ingestion/Converters/`.
2.  Haz que implemente `ConverterInterface`.
3.  Define en `supports()` que maneja la extensión `xlsx`.
4.  Escribe en `convert()` la lógica para leer y formatear a Markdown (p. ej., tablas Markdown `|`).
5.  Regístralo en el constructor de `classes/Ingestion/Ingestor.php`:
    ```php
    $this->register_converter( Babel::get( XlsxConverter::class ) );
    ```

### Cómo añadir un nuevo proveedor de Embeddings (p. ej., OpenAI)

1.  Crea la clase `OpenAiGenerator` en `classes/Embeddings/`.
2.  Haz que implemente `EmbeddingGeneratorInterface`.
3.  Implementa la llamada HTTP con Guzzle hacia la API de OpenAI (`text-embedding-3-small` / 1536 dimensiones).
4.  En `Babel::get( IndexManager::class )`, resuelve de forma dinámica (leyendo la opción de base de datos) qué generador instanciar en lugar de usar `OllamaGenerator` de forma rígida.

### Cómo añadir una nueva Base de Datos Vectorial (p. ej., pgvector / PostgreSQL)

1.  Crea la clase `PgVectorDatabase` en `classes/VectorStore/`.
2.  Haz que implemente `VectorDatabaseInterface`.
3.  Escribe las consultas SQL nativas usando `$wpdb` para interactuar con tu tabla de PostgreSQL o MySQL con extensión vectorial.
4.  En `IndexManager.php`, resuelve de forma dinámica la base de datos vectorial a utilizar leyendo la opción activa en los ajustes de Babel.
