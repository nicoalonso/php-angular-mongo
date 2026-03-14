# Portafolio

Este proyecto es un portafolio usando las siguientes tecnologías:

- PHP con el Framework Symfony
- Angular como frontal
- MongoDB como BD
- RabbitMQ como capa de transporte
- Docker para desarrollo en local
- Arquitectura Hexagonal

# Descripción y casos de uso

Este portafolio se basa en una aplicación de biblioteca, con un catálogo de libros, clientes y proveedores.

Entidades que forman parte de esta aplicación:
- Libro: entidad principal, se podrá adquirir, prestar o comprar un libro.
- Autor: cada libro tiene un autor, y cada autor puede tener varios libros.
- Editorial: cada libro tiene una editorial, y cada editorial puede tener varios libros.
- Cliente: puede solicitar un préstamo de un libro, o comprar un libro.
- Proveedor: puede suministrar libros a la biblioteca.

Casos de Uso:
1. Un cliente puede solicitar un préstamo de un libro, siempre y cuando el libro esté disponible. Tendrá un plazo de devolución de 14 días. Si el libro no se devuelve a tiempo, se le aplicará una multa.
2. Un cliente puede comprar un libro, siempre y cuando el libro esté disponible. El cliente pagará el precio del libro.
3. Un libro estará disponible para ser prestado cuando haya al menos una copia disponible en la biblioteca.
4. Un libro estará disponible para ser comprado cuando este habilitado para la venta, que haya como mínimo 3 libros en stock, y haya al menos una copia disponible en la biblioteca.
5. Un proveedor puede suministrar libros a la biblioteca, aumentando el stock de un libro.
6. Inventario: cuando se adquiera o venda un libro, se lanzará un evento de inventario que actualizará el stock del libro en la biblioteca.
7. A las 12:00 am de cada día, se ejecutará un proceso que revisará los préstamos vencidos y aplicará las multas correspondientes a los clientes.
   (el cron que lanzará este proceso se ha omitido por simplicidad, pero se podría implementar usando un contenedor adicional usando el bundle [symfony/scheduler](https://symfony.com/doc/current/scheduler.html))

Otros casos de uso:
1. Cuando se da de alta un cliente, se le asociará un número de membresía, que se generará automáticamente. Cada número de membresía es único y se compone de un prefijo "SN" seguido de un número secuencial de 5 dígitos (ejemplo: SN00001, SN00002, etc.). No se puede modificar el número de membresía una vez asignado.

## Get Started

### Clonar repositorio

```bash
git clone <this repo> .
```

### Actualizar el fichero `/etc/hosts`

Mejorar la experiencia de desarrollo en local añadiendo las siguientes líneas al fichero `/etc/hosts`:
Si usas Windows, el fichero se encuentra en `C:\Windows\System32\drivers\etc\hosts`

```bash
# Portafolio library
127.0.0.1 library-core.portafolio.loc
127.0.0.1 library.portafolio.loc
```

### Copiar y modificar el fichero `.env`

Copia el fichero de distribución .env.dist a .env en caso de que no exista

```bash
cp .env.dist .env
```

Revisa y modifica las variables que hacen referencia al identificador de usuario y grupo.
Están al principio del fichero. Estas variables son importantes, ya que se usan para compilar las imágenes de docker.
Si son incorrectas tendrías que borrar tanto los contendores como las imágenes y volver a construirlas.

Ejecuta el siguiente comando para comprobar cuál es tu usuario y el grupo con sus correspondientes identificadores.
```bash
id
```

Ejemplo: `uid=100(nico) gid=1000(nico)`

### Crear la carpeta data para los volúmenes de Docker

Para evitar perdida de datos, el contenedor de MongoDB usará un volumen que se montará en la carpeta `data` del proyecto. Es importante crear esta carpeta antes de levantar los contenedores, para evitar problemas de permisos.

```bashbash 
mkdir -p data/mongo/db
mkdir -p data/mongo/config
```

### Levantar los contenedores

Levantamos el proyecto con el comando.
El modificador `-d` detach es opcional.

```bash
docker compose up -d
```
