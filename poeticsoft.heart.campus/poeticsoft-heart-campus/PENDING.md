# Tareas Pendientes y Futuras Mejoras - Poeticsoft Heart Campus

## 0. Consideraciones de seguridad

   1. Para el estado actual de desarrollo:
      La configuración 664/775 es la correcta. Es lo que se necesita para que Campus::log() funcione (como dice tu GEMINI.md) y para que se pueda editar por SSH sin problemas.

   2. Para seguridad máxima (Producción crítica):
      Una vez que el plugin esté terminado y no se necesite que WordPress escriba en él (excepto para logs específicos), hay que cerrarlo:
       * Archivos: 644 (El grupo www-data solo lee, no escribe).
       * Directorios: 755.
       * Excepción: Solo el archivo debug.log debería permanecer con escritura para el grupo.

## 1. Mejoras de Arquitectura Senior (Post-Compilado de Assets)
- **Detección de Entorno (Dev vs Prod):**
  - Implementar constante `ENVIRONMENT` y método `Campus::is_dev()`.
  - Cargar versiones `.min` de assets en producción.
  - Ajustar nivel de logging basado en el entorno.
- **Registro Inteligente de Assets (Asset Map):**
  - Centralizar el mapa de dependencias y versiones de assets en un archivo de configuración o clase dedicada.
  - Facilitar la actualización de versiones y nombres de archivos sin tocar los controladores.

## 2. Unificar acceso a campus root id
## 3. QUick edits view
