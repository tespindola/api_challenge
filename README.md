# Requerimientos
Composer
PHP > 7.3
Node (nvm) > 7.1.1

# Instalacion
Tener instalado los paquetes de [composer](https://getcomposer.org/) y [npm](https://www.npmjs.com/) para poder ejecutar los siguientes comandos (instalan todas las dependencias del proyecto):

```bash
composer install && npm install
```

## Configuracion
En las variables de entorno debe configurar los datos para la base de datos correctamente. (DB_DATABASE, DB_USERNAME, DB_PASSWORD)

Ejecutar los siguientes comandos:
```bash
php artisan migrate
php artisan passport:install
```

# Rutas disponibles
Todas las rutas tienen el prefijo /api/

## Rutas para register y login
```bash
POST /auth/register (Registra un nuevo usuario)
    - Parametros: name, email y password
POST /auth/login (Inicia la sesion)
    - Parametros: email y password
    - La key access_token es el token que deben enviar para la verificacion de sesion en el sistema. Header Authorization Bearer <token>
POST /auth/logout (Cierra la sesion)
```

## Rutas de productos
```bash
GET /productos (Obtiene los productos)
    - Parametros: limit (opcional, elementos por pagina), order (opcional, columna para ordenar), order_type (opcional, desc o asc), q (opcional, busca coincidencias en nombre y descripcion)
    - Return: Array de productos
GET /productos/{id} (Obtiene producto por id)
    - Parametros: id
POST /productos (Crea un producto)
    - Parametros: nombre, descripcion, precio_compra, precio_venta
    - Return: El producto creado
POST /productos/{id} (Edita un producto)
    - Parametros: nombre, descripcion, precio_compra, precio_venta, id
    - Return: El producto editado
DELETE /productos/delete/{id} (Elimina un producto)
    - Parametros: id,
    - Return: delete = true
```

## Rutas de pokemons
```bash
GET /pokemons/save (Obtiene los pokemons de la api y los guarda en caso de que no exista)
    - Return: message = 'Save success'
GET /pokemons (Obtiene los pokemons)
    - Parametros: limit (opcional, elementos por pagina)
    - Return: Array de pokemons
POST /pokemons (Crea un pokemon)
    - Parametros: nombre, detalle_url
    - Return: El pokemon creado
```