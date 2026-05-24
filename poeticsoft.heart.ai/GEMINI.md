# Entorno de Desarrollo (Frontend & Bloques)

Guía técnica para el desarrollo de componentes React y compilación de assets para el ecosistema Poeticsoft Heart.

## Toolchain
- **Bundler:** Webpack 5.
- **Transpilación:** React (JSX/Babel), SCSS (Dart Sass).
- **Configuración:** `dev/webpack.config.js`.

## Desarrollo de Bloques (Webpack)
Los bloques se desarrollan en `dev/src/blocks/[nombre-del-bloque]/`.

El sistema de compilación ha migrado de Vite a **Webpack**.
Para entender el flujo completo de creación, scaffolding y compilación de bloques, **es obligatorio leer la documentación técnica en**:
`dev/src/blocks/BLOCK_DEVELOPMENT.md`

## Convenciones de UI
- Utilizar los **Design Tokens** definidos en el `theme.json` mediante variables CSS de WordPress (`var(--wp--preset--*)`).
- Asegurar compatibilidad con el sistema de tipografía fluida.
