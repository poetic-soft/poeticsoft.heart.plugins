Actúa como un desarrollador experto en WordPress, especializado en rendimiento, arquitectura de software limpia y desarrollo moderno utilizando Composer.

Quiero que crees el código completo para un plugin base de WordPress (un boilerplate) llamado "poeticsoft-heart-base". El objetivo principal de este plugin es evitar el "arranque pesado" (heavy bootstrapping) cargando condicionalmente las clases PHP y los assets (JS/CSS) según el contexto exacto de la petición (Admin, Front-end, REST API, o Editor de bloques). 

### Requisitos Tecnológicos y de Arquitectura:
1. **Gestión de Dependencias y Autoload:** Utilizaremos Composer para la carga automática de clases. Proporcióname la configuración del archivo `composer.json` utilizando el estándar PSR-4 bajo el namespace `Poeticsoft\HeartBase\`. El archivo principal del plugin debe requerir el `vendor/autoload.php` de forma segura.
2. **Clase Principal Core (Enrutador de Contexto):** Esta clase debe usar un sistema de carga condicional basado en hooks de WordPress. No debe instanciar nada en cascada dentro del constructor. Debe separar la inicialización en métodos limpios y condicionales como `init_admin()`, `init_frontend()`, e `init_rest()`.
3. **Optimización de Assets Admin:** Una clase específica para assets del admin que intercepte el `$hook_suffix` y use `get_current_screen()` para asegurar que los scripts de una funcionalidad (por ejemplo, un metabox o una página de opciones) solo se registren y encolen si estamos en la pantalla o Custom Post Type correcto.
4. **Optimización de Assets Front-end:** Una clase específica para el front-end que use etiquetas condicionales (`is_singular()`, `is_page()`, etc.) para encolar recursos bajo demanda.
6. **Sistema de Actualizaciones Propias (Self-Hosted Updates):** Implementa el mecanismo necesario utilizando los filtros nativos de WordPress `site_transient_update_plugins` y `plugins_api`. El sistema debe comprobar la versión contra un archivo JSON remoto ficticio ('https://api.poeticsoft.com/update.json') para que WordPress muestre de forma nativa el aviso "Hay una nueva versión de este plugin" en la lista de plugins si la versión remota es mayor.

### Estructura de carpetas deseada (Adaptada a PSR-4 con Composer):
poeticsoft-heart-base/
├── composer.json (Configuración PSR-4)
├── poeticsoft-heart-base.php (Archivo principal del plugin que carga el autoload de Composer)
├── blocks/
├── classes/
│   ├── Base.php (Clase principal de este plugin plugin en otros puede llamarse distinto)
│   ├── Updater.php (Mecanismo de actualizaciones remotas)
│   ├── Admin/
│   │   ├── AdminController.php (Controlador admin)
│   │   └── AdminAssets.php (Carga condicional de assets admin)
│   ├── Frontend/
│   │   ├── FrontendController.php (Controlador front)
│   │   └── FrontendAssets.php (Carga condicional de assets front)
│   └── Rest/
│       └── RestController.php (Controlador de la API REST)
└── assets/
    ├── admin/
    └── front/

Por favor, escribe el código de cada uno de estos archivos de forma completa, respetando el namespace de Composer, sin resúmenes ni marcadores de posición del tipo '// tu código aquí' en las partes estructurales, para que pueda copiar, ejecutar 'composer install' y tener un plugin funcional inmediatamente.

Puesto que esto será el scafolding para otras clases, el espacio de nombres ha de ser Poeticsoft\Heart con la clase raiz Poeticsoft\Heart\Base, en el futuro habrá otros plugins que serán por ejemplo poeticsoft-heart-campus etc

EL directorio para la clase raiz y estructura de clases se debe llamar classes/

En lugar de instanciar el Updater directamente en la clase Raiz , haz que se ejecute de manera tardía. La comprobación de actualizaciones a servidores externos (incluso con transitorios) puede ralentizar el panel de administración si se hace en el momento incorrecto, la clase Updater solo se inicialice dentro del entorno is_admin(), y idealmente enganchada al hook admin_init, nunca en el flujo global del plugin.

El patrón "Service Container" (Contenedor de Servicios)
Actualmente, la clase Raiz va a actuar como un enrutador: si es admin, hace un new AdminController().
Si en el futuro la clase FrontendController necesita datos de la clase AdminController (o de una futura clase de base de datos o configuración), empezarás a tener problemas para pasar las instancias de un lado a otro.
Para evitar caer en el uso de variables globales (global $mi_plugin;), lo ideal es usar un Contenedor de Servicios muy simple en la clase raiz. un ejemplo proporcioneado por consuLta a gemini web es:

class bASE {
    private static $services = [];

    public static function get( $service ) {
        if ( ! isset( self::$services[ $service ] ) ) {
            // Aquí se instancia solo cuando se pide por primera vez
            self::$services[ $service ] = new $service();
        }
        return self::$services[ $service ];
    }
}

Usalo si te parece correcto o crea algo mejor si lo crees conveniente

Crea el directorio y los archivos 

