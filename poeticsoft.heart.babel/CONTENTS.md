CONTENIDO.- búsqueda clasificacion, etc...

En este sitema, la búsqueda vectorial pura (por similitud) es solo la mitad del trabajo. La otra mitad se apoya por completo en los metadatos de Chroma.

A esto se le llama técnicamente Filtro Híbrido o Filtrado Metadata-Pre-Query, y es la razón principal por la que poeticsoft.heart.babel va a ser un plugin ultra-eficiente en vez de un sistema lento.

Si solo usáramos el vector, la búsqueda sería ciega. Al usar los metadatos, le pones "gafas de realidad" a la IA. Mira por qué son tan críticos en las consultas:

1. El problema de la búsqueda vectorial pura (Sin metadatos)
Imagina que tienes 5.000 chunks en Chroma sobre toda la documentación de tus clientes, servidores, código y posts de blog.

Si un agente de tu futuro plugin de IA hace una consulta semántica como: "Dame las directivas de seguridad para servidores"...

El vector buscará por significado y te traerá fragmentos que hablen de seguridad.

El problema: Te puede mezclar en el mismo saco un fragmento de código PHP sobre seguridad en WordPress, un fragmento de configuración de SSH en Debian para un servidor de staging, y un post de blog genérico sobre ciberseguridad.

Para la IA que va a redactar un contenido específico, esa mezcla de contextos es un desastre que provoca alucinaciones.

2. El "Súper Poder" del Filtro Híbrido en Chroma
Chroma te permite ejecutar la búsqueda semántica restringiendo el universo de datos mediante los metadatos de WordPress en la misma línea de código.

Cuando tu interfaz de Babel exponga el método get_context(), el plugin de agentes podrá enviarle filtros lógicos basados en los metadatos que guardamos previamente.

Mira estos tres escenarios reales de cómo razonará el sistema en las búsquedas:

Escenario A: Redacción de contenido GEO Local (Foco Quirúrgico)
El agente quiere generar un post sobre Nginx para Valencia. Sabe que no quiere paja de diseño web.

La Query a Babel: \Poeticsoft\Heart\Babel\API::get_context("Optimizar Nginx", ['category' => 'devops']);

Cómo actúa Chroma: Primero fulmina todos los vectores que NO tengan el metatag devops. Después, dentro de ese pequeño grupo exclusivo de servidores, busca la similitud vectorial de "Optimizar Nginx". Eficiencia y precisión del 100%.

Escenario B: Evitar anacronismos (Foco Temporal)
Imagina que guardas un metadato llamado year. Tienes documentación de Debian 10 (antigua) y Debian 12 (actual).

La Query a Babel: API::get_context("Configurar firewall", ['year' => 2026]);

Cómo actúa Chroma: Ignora por completo las configuraciones obsoletas de servidores de hace años, aunque vectorialmente se parezcan un 99% a lo que buscas. Solo te devuelve lo vigente.

Escenario C: Exclusión de seguridad (Foco de Permisos)
Si añades un metadato llamado visibilidad (público o privado).

La Query a Babel: El agente de cara al público consulta algo. Tu plugin de agentes le pasa el filtro ['visibilidad' => 'publico'].

Cómo actúa Chroma: Te aseguras matemáticamente de que la IA jamás recupere un chunk que contenga notas internas o contraseñas de tus servidores VPS, porque Chroma los descarta antes de medir la distancia del vector.

El JSON de consulta desde tu código PHP
Para que veas lo limpio que es, cuando tu PHP consulte a Chroma, el JSON que le enviará incluirá el vector que generó Ollama Y un operador lógico de filtrado tradicional (igual que un WHERE de SQL):

JSON
{
  "query_embeddings": [[0.014, -0.234, 0.891]],
  "n_results": 3,
  "where": {
    "$and": [
      { "category": "devops" },
      { "cpt_type": "documentacion" }
    ]
  }
}
Resumiendo
Los vectores manejan la intuición y el significado (saber de qué trata el texto). Los metadatos manejan la lógica y las reglas de negocio (saber a qué categoría, fecha o cliente pertenece).

Al diseñar la interfaz de poeticsoft.heart.babel, debemos asegurarnos de que la función de búsqueda acepte un array de filtros para que el plugin de agentes pueda acotar el pajar antes de que Ollama y Chroma busquen la aguja. ¡Es una arquitectura redonda!

Estrategia de Metadatas en un entorno heterogéneo en cuanto a contenido

Para resolver la heterogeneidad de tus contenidos en poeticsoft.heart.babel sin complicar el motor de chunking, la mejor estrategia de ingeniería de software es aplicar un Patrón de Metadatos en Tres Capas.

El Protocolo de Metadatos en 3 Capas (De lo Fijo a lo Dinámico)
Cuando tu motor de chunking procese un post, construirá el array de metadatos juntando tres niveles de información:

Capa 1: Los Metadatos Universales (Fijos y Obligatorios)
Son los que hereda cualquier contenido de WordPress, sea lo que sea. Tu código los genera automáticamente sin pensar.

post_id (Integer) -> Clave para la sincronización y el borrado.

cpt_type (String) -> post, page, documentacion, etc.

chunk_index y total_chunks (Integers) -> Para la reconstrucción posicional.

hash_version (String) -> Para control de cambios.

Capa 2: Los Metadatos del CMS (Taxonomías Nativas)
WordPress ya resuelve la heterogeneidad mediante categorías y tags. Tu plugin solo tiene que "leer" lo que ya está ahí y meterlo como texto plano separado por comas.

category (String) -> Slugs de categorías asignadas

tags (String) -> Slugs de las etiquetas asignadas.

Si estás en un post de desarrollo, tu categoría será devops. Si estás en uno de SEO, será marketing. Chroma se traga lo que le eches.

Capa 3: Los Metadatos Dinámicos (Filtrado por Hooks)
Aquí es donde desacoplamos por completo el motor de chunking para que no se complique. En lugar de meter lógica específica dentro del generador de chunks, creas un hook personalizado (un apply_filters) de WordPress.

Así, si mañana creas un CPT específico para servidores que usa campos personalizados (ACF) como sistema_operativo o php_version, el motor de chunking simplemente lanza el hook y añade lo que otros componentes le digan:

PHP
// Dentro del motor de chunking de Babel:
$metadatos_base = [
    'post_id'   => $post_id,
    'cpt_type'  => get_post_type($post_id),
    'category'  => $categoria_slug,
    // ... los universales
];

// Abrimos la puerta a que cualquier otra parte del sistema inyecte metadatos
$metadatos_finales = apply_filters('poeticsoft_babel_chunk_metadata', $metadatos_base, $post_id);
¿Cómo se ve esto en la práctica?
Si dejas este sistema funcionando en Babel, mira lo limpio que queda el JSON para dos contenidos completamente distintos:

Caso A: Un post técnico de tu CPT de documentación
JSON
{
  "documents": ["## Configuración de Nginx..."],
  "metadatas": [{
    "post_id": 101,
    "cpt_type": "documentacion",
    "category": "sysadmin",
    "tags": "nginx,debian",
    "so_vps": "debian-12"  // <- Inyectado dinámicamente por hook
  }]
}
Caso B: Un post de tu blog sobre copywriter o SEO local
JSON
{
  "documents": ["## Cómo redactar títulos atractivos..."],
  "metadatas": [{
    "post_id": 205,
    "cpt_type": "post",
    "category": "seo",
    "tags": "copywriting,valencia",
    "target_city": "valencia" // <- Inyectado dinámicamente por hook
  }]
}
Conclusión: Babel no necesita entender el contenido
Al diseñar la arquitectura así, el motor de chunking de poeticsoft.heart.babel sigue siendo súper simple: solo se encarga de trocear texto y empaquetar lo básico de WordPress.

Si un contenido necesita metadatos muy locos o específicos para que el plugin de agentes filtre mejor, esa responsabilidad recaerá en el plugin de agentes o en funciones externas que se enganchen al filtro poeticsoft_babel_chunk_metadata.

Babel se mantiene agnóstico, ligero y preparado para cualquier tipo de contenido heterogéneo que le lances en el futuro.