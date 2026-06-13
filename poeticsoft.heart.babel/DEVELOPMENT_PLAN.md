# Plan Global de Desarrollo: poeticsoft.heart.babel

## 1. Visión y Propósito
`poeticsoft.heart.babel` es un plugin de WordPress diseñado para ser el cerebro de conocimiento y el motor RAG (Retrieval-Augmented Generation) del ecosistema Poeticsoft. Su misión es gobernar el conocimiento de forma agnóstica al LLM, permitiendo que cualquier agente de IA (local o en la nube) consulte contexto ultra-preciso mediante una API interna limpia.

### Objetivos Clave:
- **Desacoplamiento total de la IA:** Independencia del modelo (Ollama, Claude, GPT, etc.).
- **Flexibilidad Multiagente:** Soporte para diversos agentes especializados (SEO, Técnico, Maquetación).
- **Gestión Soberana de Datos:** Uso de Ollama (Embeddings) y ChromaDB (Vectores) en entorno local/propio.

---

## 2. Modelo Arquitectónico (poeticsoft.heart.base)
Siguiendo el modelo de `poeticsoft.heart.base`, el plugin se estructurará bajo los siguientes principios:

- **Patrón Singleton / Service Container:** Para la gestión centralizada de dependencias y servicios.
- **Enrutamiento Context-Aware:** Separación clara entre lógica de Administración, Frontend y REST API.
- **Arquitectura de Servicios:** Desacoplamiento de las piezas funcionales (Chunking, Conectividad IA, Orquestación de Vectores).
- **Hooks & Filters:** Uso extensivo del sistema de filtros de WordPress para permitir la extensibilidad (especialmente en la capa de metadatos).

---

## 3. Estrategia de Datos y Contenido
La eficiencia del sistema radica en el **Filtro Híbrido** (Vectorial + Metadatos).

### Protocolo de Metadatos en 3 Capas:
1. **Capa Universal (Fija):** `post_id`, `cpt_type`, `chunk_index`, `total_chunks`, `hash_version`.
2. **Capa CMS (Taxonomías):** Categorías y etiquetas nativas de WordPress.
3. **Capa Dinámica (Hooks):** Metadatos específicos inyectados mediante el filtro `poeticsoft_babel_chunk_metadata` (ej: `so_vps`, `target_city`).

Este enfoque permite que Babel sea agnóstico al contenido, delegando la especificidad a los agentes o CPTs específicos.

---

## 4. Componentes del Stack Tecnológico
- **Ollama:** Motor de Embeddings (`nomic-embed-text`).
- **ChromaDB:** Base de datos vectorial (vía Docker o instalación nativa en Debian).
- **Guzzle HTTP:** Cliente para comunicación asíncrona con servicios externos/locales.
- **PHP 8.x:** Base de desarrollo del plugin.

---

## 5. Implementación Técnica
El desarrollo se divide en clases especializadas:
- **`ContentChunker`:** Segmentación recursiva de Markdown con solapamiento (overlap).
- **`OllamaConnector`:** Generación de vectores a partir de fragmentos de texto.
- **`ChromaOrchestrator`:** Sincronización de lotes (batch) hacia la base de datos vectorial, incluyendo control de idempotencia (borrado preventivo por `post_id`).

---

## 6. Fases de Desarrollo y Validación
El proceso seguirá un ciclo de **Desarrollo Basado en Tests (TDD)** en consola antes de la integración final en el core de WordPress:

1. **Fase 1: Fragmentación (Chunking):** Validación del algoritmo de división y solapamiento.
2. **Fase 2: Generación de Vectores:** Validación de la respuesta y dimensiones de Ollama.
3. **Fase 3: Persistencia en Chroma:** Validación del ciclo CRUD completo y prevención de duplicados.
4. **Fase 4: Recuperación Semántica (RAG):** Validación de la precisión de las consultas y relevancia de resultados.

---

## 7. API Interna de Referencia
El plugin expondrá métodos públicos para ser consumidos por otros componentes (como el futuro plugin de agentes):

```php
$contexto = \Poeticsoft\Heart\Babel\API::get_context("Consulta semántica", [
    'category' => 'devops',
    'limit'    => 3,
    'where'    => [ /* Filtros adicionales */ ]
]);
```
