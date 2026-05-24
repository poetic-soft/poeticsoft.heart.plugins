# Poeticsoft Heart Base - Especificaciones de Arquitectura y Rendimiento

Este documento detalla la arquitectura, estándares y estrategias de optimización del boilerplate **Poeticsoft Heart Base**. El objetivo es proporcionar un marco de trabajo de alto rendimiento para el desarrollo de la familia de plugins de Poeticsoft Heart.

---

## 1. Pilares de la Arquitectura

### 1.1 Service Container (Contenedor de Servicios)
El plugin utiliza un patrón **Service Container** simplificado en la clase raíz `Poeticsoft\Heart\Base`. 
- **Método central:** `Base::get( Clase::class )`.
- **Propósito:** Garantiza que cada clase se instancie **solo una vez** (Singleton) y **solo cuando sea estrictamente necesaria**.

### 1.2 Identidad Dinámica y Marca Corporativa
Para facilitar la creación de nuevos plugins manteniendo la identidad de Poeticsoft, se han centralizado las constantes de identidad en `Base.php`:
- **`PLUGIN_SLUG`**: Define el text-domain y los slugs de los menús (ej: `poeticsoft-heart-base`).
- **`PREFIX`**: Define el prefijo para todas las opciones, meta-keys, nombres de tablas y nonces (ej: `poeticsoft_heart_base_`).
- **Objetivo**: Asegurar que la marca sea visible en toda la base de datos y evitar colisiones entre plugins de la misma familia.

### 1.3 Patrón Orchestrator
Cada módulo (Admin, Rest, Frontend, Database, View, Languages, Validation) tiene una clase principal que se llama exactamente igual que su carpeta, actuando como el orquestador de ese contexto.

---

## 2. Componentes del Núcleo

### 2.1 Gestión de Base de Datos (`Database`)
Orquestador encargado de las migraciones mediante `dbDelta`.
- **Prefijo Automático**: Todas las tablas y versiones de DB usan `Base::PREFIX`.
- **Hook**: Se ejecuta en `activation_hook` y se verifica en `admin_init` para actualizaciones.

### 2.2 Abstracción de Vistas (`View`) - MVC
Sistema de plantillas para separar la lógica de negocio del HTML.
- **Directorio**: Las vistas residen en `/views`.
- **Uso**: `Base::get( View::class )->render( 'ruta/al/archivo', $datos )`.

### 2.3 Refuerzo de Seguridad Centralizado
La clase `AbstractPage` integra una capa de seguridad automática para todas las páginas de administración:
- **Nonces**: Generación y verificación automática basada en el slug de la página y el prefijo global.
- **Actions**: Interceptación segura de parámetros `action` vía `admin_init`.
- **Capacidades**: Verificación de permisos integrada antes de cualquier renderizado o acción.

### 2.4 Motor de Validación y Sanitización (`Validation`)
Centraliza la limpieza de datos entrantes.
- **Sanitización**: Métodos específicos para cada tipo de dato (email, url, text, int, etc.).
- **Validación por Esquemas**: Permite validar arrays completos de datos (ej: `$_POST`) mediante reglas definidas.

### 2.5 Internacionalización (`Languages`)
Carga dinámica del text-domain basada en `Base::PLUGIN_SLUG`.
- Todas las cadenas deben usar funciones de traducción (`__()`, `_e()`, etc.) con el dominio dinámico.

---

## 3. Estrategia de Rendimiento

### 3.1 Carga Condicional (Context Routing)
El sistema segmenta la ejecución para cargar solo lo necesario:
- **Admin Context**: Solo si `is_admin()`.
- **REST Context**: Solo durante `REST_REQUEST`.
- **Frontend Context**: Solo en la carga pública.
- **Global Context**: Solo para orquestadores transversales (Languages, Database).

### 3.2 Sistema de Logs
Uso de `Base::log()` para depuración controlada. Los logs se guardan en el root como `debug.log`. El archivo debe tener permisos de escritura (664) para el servidor web.

---

## 4. Guía de Desarrollo para Nuevos Plugins

1. **Clonar Scaffolding**: Copiar la estructura a la nueva carpeta del plugin.
2. **Configurar Identidad**: Modificar `PLUGIN_SLUG` y `PREFIX` en `classes/Base.php`.
3. **Namespace**: Actualizar el namespace raíz si fuera necesario (por defecto `Poeticsoft\Heart`).
4. **Desarrollo MVC**: 
   - Crear lógica en `classes/Admin/Pages/`.
   - Crear presentación en `views/admin/`.
   - Usar `handle_action()` en el controlador para lógica de formularios.

---

**Desarrollado por:** Poeticsoft  
**Versión del Scafolding:** 1.0.0
