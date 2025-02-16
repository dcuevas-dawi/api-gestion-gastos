Aquí tienes la documentación para tu API de gestión de gastos:

# API de Gestión de Gastos

Esta API permite gestionar los gastos de los usuarios, incluyendo la creación, actualización, eliminación y obtención de gastos.
Cada usuario solo tiene acceso a sus propios gastos.

Cada gasto tiene los siguientes campos:
- `id`: ID del gasto.
- `description`: descripción del gasto.
- `amount`: cantidad gastada.
- `date`: fecha del gasto.
- `created_at`: fecha de creación del gasto.
- `updated_at`: fecha de actualización del gasto.
- `user_id`: ID del usuario que creó el gasto.
- `category`: categoría del gasto.

Las únicas categorías permitidas son: 'comestibles', 'ocio', 'electronica', 'utilidades', 'ropa', 'salud', 'coleccionables', 'transporte', 'juegos', 'otros'.

Para cada acción a realizar en la API, la haremos a través de Postman.

Dejaré una petición de prueba lista para ser copiada/pegada en Postman. Lo único a tener en cuenta será cambiar el token de autenticación.

## Instalación

Para instalar la API, sigue estos pasos:

1. Clona el repositorio:
```bash
git clone https://github.com/SwagDAWnDual/api-gestion-gastos-dcuevas-dawi.git
o
git clone https://github.com/dcuevas-dawi/api-gestion-gastos.git

Dependiendo del repositorio al que se tenga acceso.
```
2. Instala las dependencias:
```bash
cd api-gestion-gastos
php composer install (El proyecto ya tiene composer instalado y se puede ejecutar con el 'php' delante, pero si falla y tienes composer en tu máquina, simplemente ejecuta 'composer install')
npm install (Puede tardar un poco)
npm run build
```

3. Creamos el archivo de configuración de entorno .env:
```bash
cp .env.example .env
```

4. Generamos la clave de la aplicación y la clave secreta de JWT:
```bash
php artisan key:generate
php artisan jwt:secret
```
5. Migrar la base de datos:
```bash
php artisan migrate
```
> **Nota**: Quizás de una advertencia de que no se ha encontrado la base de datos, pero nos preguntará si deseamos crearla, así que seleccionamos 'yes'.

6. Ejecutar el servidor:
```bash
php artisan serve
```

En este punto ya tenemos la api funcionando lista para recibir peticiones.

## Uso en Postman

Al abrirlo, podremos copiar las solicitudes que se encuentran en la documentación y directamente en el campo de la url.

Más adelante se explica en ams detalle, pero solo mencionar que, aunque las peticiones están listas pare copiar/pegar, se debe tener en cuenta que en el header se debe cambiar el token que recibamos para nuestro usuario.

![Postman](storage/postman.png)

## Autenticación

La API utiliza autenticación JWT. 

### Registro

Para registrarte como usuario, esta es la solicitud que debes enviar:

```bash
curl --location 'http://127.0.0.1:8000/api/register' \
--header 'Content-Type: application/json' \
--data-raw '{
"name": "Ejemplo",
"email": "ejemplo@ejemplo.com",
"password": "ejemplo1234",
"password_confirmation": "ejemplo1234"
}'
```

La respuesta será:

```json
{
    "message": "Usuario registrado con éxito"
}
```

### Autenticación

Para autenticarte, la solicitud será:

```bash
curl --location 'http://127.0.0.1:8000/api/login' \
--header 'Content-Type: application/json' \
--data-raw '{
"email": "dani@dani.com",
"password": "danidani"
}'
```

Obtendremos una respuesta de este estilo:

```bash
{
"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.
      eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2xvZ2luIi
      wiaWF0IjoxNzM5NjQ2NzY4LCJleHAiOjE3Mzk2NTAzNjgsIm5iZiI6
      MTczOTY0Njc2OCwianRpIjoiN0cwMGJWcnFiQk05RTNUWSIsInN1Yi
      I6IjEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4
      NzJkYjdhNTk3NmY3In0.
      iJ2adlUyi3OScqsIqSt_5azcSw_hBquWPsSKFtSQQH8"
}
```

Este token es necesario para autenticar las solicitudes a la API y tiene una caducidad de 1 hora.

Debes incluir este token en el encabezado `Authorization` de cada solicitud.

![Token](storage/token.png)

En el cambo 'value' solo debemos cambiar el token sin quitar el 'Bearer '.

También es importante incluir en el header: 'Content-Type: application/json' para evitar errores.


## Endpoints

### Obtener todos los gastos

**GET** `/api/expenses`

Obtiene todos los gastos del usuario autenticado.

**Petición:**
```bash
curl --location 'http://127.0.0.1:8000/api/expenses' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNzM5NjQ1MDY5LCJleHAiOjE3Mzk2NDg2NjksIm5iZiI6MTczOTY0NTA2OSwianRpIjoiQWdFenhLNWVoZFE2S1FxdCIsInN1YiI6IjEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.sWMRmBqXb6c2exhGu0pYZ0jQUQSjFA-C9iVrFX6_VdA' \
--header 'Content-Type: application/json'
``` 

**Respuesta:**
- `200 OK` con la lista de gastos.

```json
[
    {
        "id": 1,
        "description": "Compra de figura coleccionable",
        "amount": 79.99,
        "date": "2024-02-08",
        "created_at": "2025-02-15T18:44:43.000000Z",
        "updated_at": "2025-02-15T18:44:43.000000Z",
        "user_id": 1,
        "category": "coleccionables"
    },
    {
        "id": 2,
        "description": "Compra de oficina",
        "amount": 150.75,
        "date": "2023-10-16",
        "created_at": "2025-02-15T18:45:00.000000Z",
        "updated_at": "2025-02-15T18:45:00.000000Z",
        "user_id": 1,
        "category": "otros"
    }
]
```
    

### Crear un gasto

**POST** `/api/expenses`

Crea un nuevo gasto para el usuario autenticado.

**Petición:**

```bash
curl --location 'http://127.0.0.1:8000/api/expenses' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNzM5NjQ1MDY5LCJleHAiOjE3Mzk2NDg2NjksIm5iZiI6MTczOTY0NTA2OSwianRpIjoiQWdFenhLNWVoZFE2S1FxdCIsInN1YiI6IjEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.sWMRmBqXb6c2exhGu0pYZ0jQUQSjFA-C9iVrFX6_VdA' \
--header 'Content-Type: application/json' \
--data '{
  "description": "Compra de figura coleccionable",
  "amount": 79.99,
  "date": "2024-02-08",
  "category": "coleccionables"
}'
```

**Respuesta:**
- `201 Created` con el mensaje de éxito.
- `422 Unprocessable Entity` si hay errores de validación.

### Obtener un gasto por ID

**GET** `/api/expenses/{id}`

Obtiene un gasto específico por su ID.

**Petición:**

```bash
curl --location 'http://127.0.0.1:8000/api/expenses' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNzM5NjQ1MDY5LCJleHAiOjE3Mzk2NDg2NjksIm5iZiI6MTczOTY0NTA2OSwianRpIjoiQWdFenhLNWVoZFE2S1FxdCIsInN1YiI6IjEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.sWMRmBqXb6c2exhGu0pYZ0jQUQSjFA-C9iVrFX6_VdA' \
--header 'Content-Type: application/json' \
--data '{
  "description": "Compra de figura coleccionable",
  "amount": 79.99,
  "date": "2024-02-08",
  "category": "coleccionables"
}'
```

**Respuesta:**
- `200 OK` con los detalles del gasto.
- `404 Not Found` si el gasto no existe.

### Obtener gastos por categoría

**GET** `/api/expenses/category/{category}`

Obtiene todos los gastos de una categoría específica.

**Petición:**

```bash
curl --location 'http://127.0.0.1:8000/api/expenses/category/transporte' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNzM5NjQwMzE1LCJleHAiOjE3Mzk2NDM5MTUsIm5iZiI6MTczOTY0MDMxNSwianRpIjoidGJoaEs4RDZESzhPNlRndyIsInN1YiI6IjMiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.tyFHV2AMCeJQxQQ330q56C3ZDybxQ_-OzaYuDcysB7k' \
--header 'Content-Type: application/json'
```

**Respuesta:**
- `200 OK` con la lista de gastos.
- `422 Unprocessable Entity` si la categoría es inválida.

### Categorías

Las categorías válidas para los gastos son:
- `comestibles`
- `ocio`
- `electronica`
- `utilidades`
- `ropa`
- `salud`
- `coleccionables`
- `transporte`
- `juegos`
- `otros`

### Actualizar un gasto

**PUT** `/api/expenses/{id}`

Actualiza un gasto existente.

**Petición**

```bash
curl --location --request PUT 'http://127.0.0.1:8000/api/expenses/7' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNzM5NjQ4OTM4LCJleHAiOjE3Mzk2NTI1MzgsIm5iZiI6MTczOTY0ODkzOCwianRpIjoib1p3MnFIT21kR2FDdk00UyIsInN1YiI6IjEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.DomQQbEqfg8RmCHC7mCAHivC6567GssNY1yrvs1HZok' \
--header 'Content-Type: application/json' \
--data '{
  "category": "ocio"
}'
```
Podemos poner tantos campos como queramos actualizar.


**Respuesta:**
- `200 OK` con el mensaje de éxito.
- `404 Not Found` si el gasto no existe.
- `422 Unprocessable Entity` si hay errores de validación.

### Eliminar un gasto

**DELETE** `/api/expenses/{id}`

Elimina un gasto existente.

**Petición:**

```bash
curl --location --request DELETE 'http://127.0.0.1:8000/api/expenses/5' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNzM5NjQ4OTM4LCJleHAiOjE3Mzk2NTI1MzgsIm5iZiI6MTczOTY0ODkzOCwianRpIjoib1p3MnFIT21kR2FDdk00UyIsInN1YiI6IjEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.DomQQbEqfg8RmCHC7mCAHivC6567GssNY1yrvs1HZok' \
--header 'Content-Type: application/json' \
--data ''
```

**Respuesta:**
- `200 OK` con el mensaje de éxito.
- `404 Not Found` si el gasto no existe.

## Ejemplos para la base de datos

Si deseamos hacer pruebas con la base de datos, podemos ejecutar un seeder que dejo preparado con 10 gastos. Para evitar problemas, primero creamos un usuario y luego ejecutamos el seeder.

Para ello, ejecutamos el siguiente comando: 

```bash
    php artisan db:seed
```

Con ello, tendremos 10 gastos de prueba para poder hacer pruebas con la API.


## Testing

**Precaución:** Los tests eliminan los datos de la base de datos, por lo que es recomendable hacer una copia de seguridad antes de ejecutarlos. Esto se debe a que se utilizan factories para generar gastos y usuarios ficticios en los test y se elimina todo al finalizar. 

Para ejecutar las pruebas, ejecutamos el siguiente comando:

```bash
    php artisan test
```

Los test están en el archivo `ExpenseTest.php` en la carpeta `tests/Feature`.

Hay un total de 8 test que testean: 

- Crear un gasto
- Actualizar un gasto
- Eliminar un gasto
- Obtener todos los gastos
- Obtener gastos por categoría
- Obtener un gasto por ID
- Comprobar que una categoría es incorrecta
