# Library Frontend

Este es el frontend de la aplicación de biblioteca.

## Tecnologías

- Angular v19
- PrimeNG como biblioteca de componentes UI
- PrimeFlex como biblioteca de utilidades CSS

## Testing

In progress...

## Gestor de paquetes

En el proyecto se está usando pnpm como gestor de paquetes.

Tiene muchas más ventajas que npm o yarn, como una instalación más rápida, un mejor manejo de dependencias y un menor uso de espacio en disco.
Pero sobre todo lo he elegido por seguridad.

`pnpm` usa un store interno para almacenar las dependencias.
Esto significa que para poder instalar dependencias debemos acceder al contenedor y ejecutar el comando `pnpm install` dentro del contenedor.
Desde fuera nos dará errores.

Instalar dependencias usando `pnpm`:

```bash
docker compose exec editor pnpm install {package-name}
```

O si quieres entrar al contenedor:

```bash
docker compose exec editor bash
pnpm install {package-name}
```

## Variables de entorno

Angular usa los ficheros environment para configurar las variables de entorno.

Para dar soporte a ficheros `.env` he creado un script que se encarga de cargar las variables de entorno.
Lo puedes encontrar dentro de la carpeta `bin` con el nombre `set-envs.mjs`.
