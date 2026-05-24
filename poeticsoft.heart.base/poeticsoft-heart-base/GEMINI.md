# Poeticsoft Heart Project Mandates

Este archivo contiene las directrices fundamentales para el desarrollo del ecosistema de plugins Poeticsoft Heart.

## Estándares de Arquitectura
- **Namespace Raíz:** `Poeticsoft\Heart\`
- **Service Container:** Siempre usar `Base::get( Clase::class )` para instanciar servicios. Nunca instanciar clases manualmente fuera del contenedor.
- **Context Routing:** Mantener la separación estricta entre Admin, Frontend y REST.
- **Patrón Orchestrator (Option A):** La clase principal de cada módulo debe llamarse igual que su carpeta (ej. `Admin\Admin`, `Rest\Rest`, `Frontend\Frontend`).

## Convenciones de Código
- **Dependencias Explícitas:** Declarar siempre los `use` al principio del archivo, incluso para clases en el mismo namespace.
- **Nomenclatura de Archivos:** El nombre del archivo debe coincidir exactamente con el nombre de la clase (PSR-4).
- **Hooks:** 
  - Usar closures para el arranque del plugin en el archivo principal.
  - Usar `[$this, 'metodo']` dentro de las clases para permitir extensibilidad.

## Seguridad
- **Niveles REST:** Configurar siempre el nivel de `auth` en los endpoints (Public, User, Admin).
- **Logs:** Usar `Base::log()` para depuración en desarrollo. Los logs se guardan en el root del plugin como `debug.log`.

## Rendimiento
- **Lazy Loading:** Los sub-controladores y páginas deben instanciarse solo si el contexto actual de WordPress lo requiere.
