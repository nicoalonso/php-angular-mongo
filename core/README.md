# Library Backend

Este es el backend de la aplicación de biblioteca.

## Tecnologías

- PHP con el Framework Symfony
- MongoDB como BD
- RabbitMQ como capa de transporte
- Arquitectura Hexagonal

## Swagger

La documentación de la API se encuentra disponible en la ruta `/v1/doc` una vez que el proyecto esté en marcha.

Si has configurado el fichero `/etc/hosts` como se indica en el README principal, podrás acceder a la documentación de la API en la siguiente URL: [http://library-core.portafolio.loc/v1/doc](http://library-core.portafolio.loc/v1/doc)

## Tests Unitarios

Para ejecutar los tests unitarios, puedes usar el siguiente comando:

```bash
docker compose exec core php bin/phpunit
```

Y si entras en el container, puedes ejecutar los tests unitarios con el siguiente comando:

```bash
bin/phpunit
```
