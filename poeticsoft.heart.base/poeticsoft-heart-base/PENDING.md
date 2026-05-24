# Tareas Pendientes y Futuras Mejoras - Poeticsoft Heart Base

Este archivo contiene las propuestas de mejora identificadas al finalizar la fase inicial de arquitectura. Sirve como hoja de ruta para las próximas sesiones de desarrollo.

---

## 0. Revisar errores de composer dump-autoload -o

---

## 1. Gestión de Base de Datos [COMPLETADO]
- **Objetivo:** Crear un sistema de migraciones robusto.
- **Tarea:** Implementar `classes/Database/Database.php` para gestionar la creación y actualización de tablas personalizadas mediante `dbDelta`.
- **Resultado:** Implementada clase `Database` (Orchestrator) con soporte para `dbDelta`, control de versiones en `wp_options` y hook de activación en el archivo principal.

## 2. Refuerzo de Seguridad (Admin & AJAX) [COMPLETADO]
- **Objetivo:** Centralizar la validación de seguridad en formularios.
- **Tarea:** Integrar la verificación automática de *nonces* y *capabilities* dentro de `AbstractPage`.
- **Resultado:** Implementado en `AbstractPage` un sistema de validación automática que intercepta `action` en `admin_init`, verifica `capabilities` y valida `nonces` de forma centralizada antes de delegar la ejecución al método `handle_action()`.

## 3. Abstracción de Vistas (Frontend & Admin) [COMPLETADO]
- **Objetivo:** Separar la lógica de negocio del HTML (Patrón MVC).
- **Tarea:** Crear un sistema simple de carga de plantillas (`View::render( 'template-name', $data )`).
- **Resultado:** Implementada clase `View` (Orchestrator) que permite cargar plantillas desde la carpeta `/views`. Se han refacturado las páginas de `Dashboard` y `SMTP` para usar este sistema, eliminando el HTML directo de los controladores.

## 4. Internacionalización (i18n) [COMPLETADO]
- **Objetivo:** Preparar el plugin para múltiples idiomas.
- **Tarea:** Implementar una clase que cargue el `text-domain` y estandarizar el uso de funciones `__()`, `_e()`, etc.
- **Resultado:** Implementada clase `Languages` (Orchestrator) que carga el text-domain `poeticsoft-heart-base` desde la carpeta `/languages`. Se han actualizado las cadenas en `DashboardPage` y su vista para soportar traducciones.

## 5. Validación de Datos (Validation Engine) [COMPLETADO]
- **Objetivo:** Centralizar la limpieza de inputs.
- **Tarea:** Crear una utilidad o clase base para sanitizar y validar datos de entrada.
- **Resultado:** Implementada clase `Validation` (Orchestrator) que centraliza la sanitización (`sanitize`) y validación (`validate`) de datos. Incluye soporte para validación por esquemas (`validate_schema`), facilitando la limpieza masiva de datos en la API REST y formularios.

## 6. Consideraciones de seguridad

   1. Para el estado actual de desarrollo:
      La configuración 664/775 es la correcta. Es lo que se necesita para que Base::log() funcione (como dice tu GEMINI.md) y para que se pueda editar por SSH sin problemas.

   2. Para seguridad máxima (Producción crítica):
      Una vez que el plugin esté terminado y no se necesite que WordPress escriba en él (excepto para logs específicos), hay que cerrarlo:
       * Archivos: 644 (El grupo www-data solo lee, no escribe).
       * Directorios: 755.
       * Excepción: Solo el archivo debug.log debería permanecer con escritura para el grupo.


---
**Última actualización:** Sábado, 23 de Mayo de 2026

## 7. Mejoras de Arquitectura Senior (Post-Compilado de Assets)
- **Detección de Entorno (Dev vs Prod):**
  - Implementar constante `ENVIRONMENT` y método `Base::is_dev()`.
  - Cargar versiones `.min` de assets en producción.
  - Ajustar nivel de logging basado en el entorno.
- **Registro Inteligente de Assets (Asset Map):**
  - Centralizar el mapa de dependencias y versiones de assets en un archivo de configuración o clase dedicada.
  - Facilitar la actualización de versiones y nombres de archivos sin tocar los controladores.
