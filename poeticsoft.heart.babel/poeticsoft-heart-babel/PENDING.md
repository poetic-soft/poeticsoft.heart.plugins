# Hoja de Ruta: Tareas Pendientes (Babel RAG)

Este documento detalla los pasos, especificaciones técnicas y archivos involucrados para continuar con el desarrollo del plugin **Poeticsoft Heart Babel** en la siguiente sesión de programación.

---

##  FASE A: Automatización e Integración de los Flujos de Ingesta

**Objetivo:** Conectar las dos vías de ingesta existentes (Archivos físicos y Entradas de WordPress) con el orquestador vectorial `IndexManager` para que todo Markdown unificado generado en `converted/` o `posts/` sea indexado de forma inmediata e invisible en ChromaDB.

### Tareas y Archivos a Modificar:

#### 1. Conectar Ingestor de Archivos (`classes/Ingestion/Ingestor.php`)
*   **Modificación:** En `Ingestor::ingest_file( $relative_path )`, justo después de guardar exitosamente el archivo en `converted/` con `file_put_contents()`, llamar al `IndexManager` para indexar el archivo.
*   **Lógica a insertar:**
    ```php
    // ... justo después de guardar exitosamente el archivo en $converted_path ...
    $index_manager = Babel::get( \Poeticsoft\Heart\Babel\Indexing\IndexManager::class );
    $indexed = $index_manager->index_file( $converted_path );

    if ( is_wp_error( $indexed ) ) {
            Babel::log( "Converted file saved but indexing failed for: {$relative_path}. Error: " . $indexed->get_error_message(), 'error' );
            // Opcional: Mover a failed/ si prefieres considerarlo error crítico de ingesta.
    }
    ```

#### 2. Conectar Ingestor de WordPress (`classes/Ingestion/PostIngestor.php`)
*   **Modificación:** En `PostIngestor::sync_post( $post_id_or_obj )`, tras guardar el archivo en la subcarpeta `posts/` con `file_put_contents()`, llamar al `IndexManager` de forma inmediata.
*   **Lógica a insertar:**
    ```php
    // ... justo después de guardar exitosamente el archivo en $file_path ...
    $index_manager = Babel::get( \Poeticsoft\Heart\Babel\Indexing\IndexManager::class );
    $indexed = $index_manager->index_file( $file_path );

    if ( is_wp_error( $indexed ) ) {
            Babel::log( "Post-{$post_id} converted but vector indexing failed: " . $indexed->get_error_message(), 'error' );
    }
    ```

#### 3. Conectar Eliminaciones de WordPress (`classes/Ingestion/PostIngestor.php`)
*   **Modificación:** En `PostIngestor::delete_post( $post_id )` y cuando un post pierde las condiciones de elegibilidad en `sync_post()`, debemos asegurarnos de eliminar los vectores asociados en ChromaDB v2.
*   **Lógica a insertar:**
    ```php
    // ... en delete_post() justo antes o después de eliminar el archivo físico ...
    $vector_db = Babel::get( \Poeticsoft\Heart\Babel\VectorStore\ChromaDatabase::class );
    $collection = get_option( 'poeticsoft_heart_babel_chromadb_collection', 'poeticsoft-heart-babel' );
    
    // Borrar de ChromaDB filtrando por el ID de origen
    $vector_db->delete_documents_by_metadata( $collection, [ 'id' => strval( $post_id ) ] );
    ```

---

##  FASE B: Motor de Consultas y API Pública para Otros Plugins

**Objetivo:** Desarrollar la clase API oficial expuesta en PHP para que plugins externos (como tu futuro plugin de Agentes de IA o de Chat) puedan realizar búsquedas semánticas sobre el almacén de conocimiento de WordPress, recibiendo fragmentos contextuales y sus metadatos de origen.

### Tareas e Ingeniería de Clases:

#### 1. Crear la Clase API Oficial (`classes/API.php`)
*   **Ubicación:** `classes/API.php`
*   **Namespace:** `Poeticsoft\Heart\Babel`
*   **Responsabilidades:** Exponer métodos públicos y estáticos súper limpios y fáciles de llamar por otros programadores.

#### 2. Implementar el Método `get_context()`
Este método recibirá la consulta semántica en texto plano, la vectorizará con Ollama, buscará en ChromaDB v2, aplicará filtros y devolverá un array de resultados formateado.

*   **Firma propuesta para el método:**
    ```php
    namespace Poeticsoft\Heart\Babel;

    class API {
            /**
             * Query the sovereign RAG database to retrieve relevant context.
             *
             * @param string $query_text Plain text search query (e.g. "Configurar proxy Nginx").
             * @param array  $args       {
             *     @type int   $limit    Number of results to return (default: 3).
             *     @type array $where    Metadata filter array (e.g. ['source' => 'wordpress', 'author' => 'Alberto']).
             * }
             * @return array|(\WP_Error) Array of results with text and metadata or WP_Error on failure.
             */
            public static function get_context( string $query_text, array $args = [] ) {
                    $limit = $args['limit'] ?? 3;
                    $where = $args['where'] ?? [];

                    $generator = Babel::get( \Poeticsoft\Heart\Babel\Embeddings\OllamaGenerator::class );
                    $vector_db = Babel::get( \Poeticsoft\Heart\Babel\VectorStore\ChromaDatabase::class );
                    $collection = get_option( 'poeticsoft_heart_babel_chromadb_collection', 'poeticsoft-heart-babel' );

                    // 1. Vectorizar la query del usuario
                    $embedding = $generator->get_embedding( $query_text );
                    if ( empty( $embedding ) ) {
                            return new \WP_Error( 'embedding_failed', 'Could not generate vector embedding for search.' );
                    }

                    // 2. Consultar ChromaDB v2 aplicando límites y filtros metadata
                    $raw_results = $vector_db->query_collection( $collection, $embedding, $limit, $where );

                    return $raw_results;
            }
    }
    ```

---

##  FASE C: Diagnóstico y Widget de Conectividad en el Dashboard

**Objetivo:** Dotar a la pantalla principal del plugin (`DashboardPage.php`) de widgets interactivos y dinámicos para comprobar el estado físico de los servidores locales (Ollama y ChromaDB), listar los modelos cargados y mostrar estadísticas de la base de datos vectorial en vivo.

### Tareas y Archivos a Modificar:

#### 1. Ampliar el Dashboard de Administración (`classes/Admin/Pages/DashboardPage.php`)
*   **Modificaciones:** 
    *   Implementar llamadas a `ChromaDatabase::ping()` para comprobar el estado de ChromaDB.
    *   Hacer una llamada HTTP directa a Ollama `GET /api/tags` o similar para verificar la conectividad de Ollama y confirmar que el modelo `nomic-embed-text` está completamente descargado e instalado.
    *   Obtener estadísticas de ChromaDB: realizar una llamada HTTP a `/api/v2/tenants/default_tenant/databases/default_database/collections/{collection_id}/count` para mostrar en vivo **cuántos fragmentos vectoriales (chunks) están indexados en la colección**.
    *   Pasar estos resultados en el array `$data` hacia la vista.

#### 2. Desarrollar la Vista Dinámica (`views/admin/dashboard.php`)
*   **Modificaciones:**
    *   Diseñar tarjetas con indicadores visuales interactivos: luces **verde** (operacional/conectado) y **rojo** (desconectado/error).
    *   **Indicador ChromaDB:** Muestra si el puerto 8000 está activo, la colección seleccionada, y el conteo total de fragmentos semánticos indexados.
    *   **Indicador Ollama:** Muestra si el puerto 11434 responde, y un listado de los modelos instalados (resaltando con un check si `nomic-embed-text` está activo).
    *   **Test de Inferencia Rápida:** Una pequeña caja de entrada de texto donde el administrador pueda escribir un texto corto y hacer una búsqueda de prueba directamente en el Dashboard para ver qué chunks se recuperan semánticamente de manera instantánea.
