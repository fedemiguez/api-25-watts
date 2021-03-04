# Prueba Tecnica Backend Developer
## _25 watts Federico Miguez_

[![N|Solid](https://www.25watts.com.ar/assets/img/logoN.png)](https://www.25watts.com.ar/)

Prueba Técnica para formar parte del equipo de trabajo.

#### Prueba Tecnica
- Crear un servicio API REST para listar, paginar, agregar y actualizar tareas.
- El servicio debe manejar token para validar las request.
- Integrar y documentar con Swagger para mostrar cada uno de los endpoints.
- Crear migraciones para generar estructura y datos de prueba de base de datos.

#### Forma de Entrega
- Entregar el resultado de la prueba en un repositorio público (preferentemente Github).
- Incluir un archivo README con los pasos necesarios para levantar el proyecto y testear.

# Instalacion
- ` composer install`
- crear una base de datos
- reemplazar los datos de conexion a DB en el archivo .env 
- `php artisan migrate --seed`
- `php artisan passport:install`
- `php artisan key:generate`

# Correr proyecto
- `php artisan serve`
- Documentacion : http://localhost:8000/api/documentation

Cuando se corren las migraciones se crea un usuario de prueba para que haga el login desde la documentacion y asi obtener el access_token, aqui los datos:

email: juanperez@gmail.com,
password: Password123456