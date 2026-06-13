BABEL, gestión documental y RAG, Fundamentos

1. Desacoplamiento total de la IA (Independencia del LLM)

poeticsoft.heart.babel tiene una misión clara y agnóstica: gobernar el conocimiento. A este plugin no le importa si el agente que le consulta es un script local en Ollama, un modelo en la nube como Claude 3.5 Sonnet, o un sistema multiagente complejo.

Babel solo entiende de indexar WordPress, trocear Markdown, sincronizar con Chroma y escupir contexto ultra-preciso cuando se lo piden. Si mañana sale un modelo de IA revolucionario, solo tienes que tocar el plugin de agentes; Babel seguirá funcionando exactamente igual.

2. Flexibilidad Multiagente (El Orquestador)

Al dejar la lógica de los agentes en otro plugin independiente, abres la puerta a arquitecturas multiagente complejas. Podrás tener:

Un agente especialista en Redacción SEO Local.

Un agente especialista en Revisión Técnica y Code-Style.

Un agente especialista en Maquetación con Core Blocks.

Cada uno de estos agentes tendrá su propio prompt de sistema, su propia temperatura y su rol. Cuando necesiten "saber algo", simplemente llamarán a la API interna de Babel (mediante funciones PHP o clases compartidas) para que les traiga los chunks relevantes. Los agentes se centran en razonar, mientras que Babel se centra en recordar.

3. Una API interna de WordPress Limpia

Entre tus dos plugins existirá un contrato limpísimo. Tu plugin de agentes solo tendrá que hacer algo como esto:

PHP
// Ejemplo de cómo el plugin de agentes invoca a Babel
$contexto_técnico = \Poeticsoft\Heart\Babel\API::get_context("Configurar Nginx en Debian", [
    'category' => 'devops',
    'limit'    => 3
]);

// El plugin de agentes ahora inyecta ese $contexto_técnico en el prompt del LLM
El Rol de poeticsoft.heart.babel
Para que este planteamiento sea perfecto, Babel debe actuar como una caja negra proveedora de servicios dentro de WordPress, encargándose exclusivamente de:

El ciclo de vida de los datos (save_post, before_delete_post).

El algoritmo de chunking recursivo y solapamiento.

La pasarela HTTP con Ollama (para embeddings) y Chroma (para almacenamiento/búsqueda).

Ofrecer métodos públicos de consulta para que otros plugins (como tu futuro plugin de agentes) puedan extraer los documentos relacionados pasándole un texto plano o un ID de post.