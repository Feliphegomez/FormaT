# PHP-REST-API (v2)

Script PHP 7 de un solo archivo que agrega una API REST a una base de datos InnoDB MySQL 5.6. PostgreSQL 9.1 y MS SQL Server 2012 son totalmente compatibles.

NB: Esta es la implementación de referencia de [TreeQL](https://treeql.org) en PHP.

NB: ¿Estás buscando v1? Está aquí: https://github.com/Feliphegomez/api-rest-php/tree/v1.0.0

## Requerimientos

  - PHP 7.0 o superior con controladores PDO para MySQL, PgSQL o SqlSrv habilitados
  - MySQL 5.6 / MariaDB 10.0 o superior para características espaciales en MySQL
  - PostGIS 2.0 o superior para características espaciales en PostgreSQL 9.1 o superior
  - SQL Server 2012 o superior (2017 para soporte de Linux)

##  Problemas conocidos

- ¿Ver los enteros como cadenas de texto? Asegúrese de habilitar la extensión nd_pdo_mysql y deshabilite pdo_mysql .

## Instalación

Esta es una aplicación de un solo archivo! Cargue "`api.php`" en algún lugar y disfrute!

Para el desarrollo local, puede ejecutar el servidor web incorporado de PHP:

    php -S localhost:8080/api/

Pruebe el script abriendo la siguiente URL:

    http://localhost:8080/api/api.php/records/posts/1
    http://localhost:8080/api/posts/1 (si se incluye el archivo .htaccess)

No olvide modificar la configuración en la parte inferior del archivo.

## Configuración

Edite las siguientes líneas en la parte inferior del archivo "`api.php`":

    $config = new Config([
        'username' => 'xxx',
        'password' => 'xxx',
        'database' => 'xxx',
    ]);

Estas son todas las opciones de configuración y su valor predeterminado entre paréntesis:

"driver": mysql , pgsql o sqlsrv ( mysql )
"address": Nombre de host del servidor de base de datos ( localhost )
"port": puerto TCP del servidor de la base de datos (predeterminado en el controlador predeterminado)
"username": nombre de usuario del usuario que se conecta a la base de datos (no predeterminado)
"password": contraseña del usuario que se conecta a la base de datos (no predeterminada)
"database": Base de datos a la que se realiza la conexión (no predeterminada)
"middlewares": Lista de middlewares para cargar ( cors )
"controllers": lista de controladores para cargar ( records,openapi )
"openApiBase": información de OpenAPI ( {"info":{"title":"PHP-CRUD-API","version":"1.0.0"}} )
"cacheType": TempFile , Redis , Memcache , Memcached o NoCache ( TempFile )
"cachePath": ruta / dirección del caché (por defecto al directorio temporal del sistema)
"cacheTime": número de segundos que la memoria caché es válida ( 10 )
"debug": muestra los errores en el encabezado "X-Debug-Info" ( false )

## Compilacion

El código reside en el directorio "`src`". Puedes acceder a ella en la URL:

    http://localhost:8080/api/src/records/posts/1

Puede compilar todos los archivos en un solo archivo "`api.php`" usando:

    php build.php

NB: La secuencia de comandos agrega las clases en orden alfabético (directorios primero).

## Limitaciones

Estas limitaciones también estaban presentes en v1:

  - Las claves primarias deben ser de incremento automático (de 1 a 2 ^ 53) o UUID
  - Las claves primarias o externas compuestas no son compatibles
  - No se admiten escrituras complejas (transacciones)
  - Las consultas complejas que llaman a funciones (como "concat" o "suma") no son compatibles
  - La base de datos debe soportar y definir restricciones de clave externa

## Caracteristicas

Estas características coinciden con las características en v1 (ver rama "v1"):

  - [x] Un solo archivo PHP, fácil de implementar.
  - [x] Muy poco código, fácil de adaptar y mantener.
  - [ ] ~~Transmisión de datos, bajo consumo de memoria.~~
  - [x] Admite variables POST como entrada (x-www-form-urlencoded)
  - [x] Admite un objeto JSON como entrada
  - [x]  Admite una matriz JSON como entrada (inserción por lotes)
  - [ ] ~~Admite la carga de archivos desde formularios web (multipart / form-data)~~
  - [ ] ~~Salida JSON condensada: la primera fila contiene nombres de campo~~
  - [x] Desinfectar y validar la entrada utilizando devoluciones de llamada
  - [x] Sistema de permisos para bases de datos, tablas, columnas y registros.
  - [x] Los diseños de bases de datos multiusuario son compatibles
  - [x] Soporte CORS multi-dominio para peticiones de dominio cruzado
  - [x] Soporte para la lectura de resultados combinados de varias tablas.
  - [x] Soporte de búsqueda en múltiples criterios
  - [x] Paginación, búsqueda, clasificación y selección de columnas.
  - [x] Detección de relaciones con resultados anidados (belongsTo, hasMany y HABTM)
  - [ ] ~~Relación "transforma" (de JSON condensado) para PHP y JavaScript~~
  - [x] Soporte de incremento atómico mediante PATCH (para contadores)
  - [x] Campos binarios soportados con codificación base64
  - [x] Campos y filtros espaciales / SIG compatibles con WKT
  - [ ] ~~Soporte de datos no estructurados a través de JSON / JSONB~~
  - [x] Generar documentación de API utilizando herramientas OpenAPI
  - [x] Autenticación a través de token JWT o nombre de usuario / contraseña
  - [ ] ~~Soporte de SQLite~~

 NB: Sin marca significa: aún no implementado. Striken significa: no será implementado.

### Características adicionales

Estas características son nuevas y no se incluyeron en v1.

  - No refleja en cada solicitud (mejor rendimiento)
  - Los filtros complejos (con "y" y "o") son compatibles
  - Soporte para salida de estructura de base de datos en JSON
  - Soporte para datos booleanos y binarios en todos los motores de bases de datos
  - Soporte para datos relacionales en lectura (no solo en operación de lista)
  - Soporte para middleware para modificar todas las operaciones (también lista)
  - Informe de errores en JSON con el estado HTTP correspondiente
  - Soporte para autenticación básica y vía proveedor de autenticación (JWT)
  - Soporte para funcionalidad básica de firewall.

## Middleware

Puede habilitar el siguiente middleware utilizando el parámetro de configuración "middlewares":

- "firewall": limita el acceso a direcciones IP específicas
- "cors": soporte para solicitudes CORS (habilitado por defecto)
- "xsrf": Bloquee los ataques XSRF usando el método 'Double Submit Cookie'
- "ajaxOnly": restringe las solicitudes que no sean AJAX para evitar ataques XSRF
- "jwtAuth": soporte para "autenticación JWT"
- "basicAuth": Soporte para "Autenticación básica"
- "authorization": restringe el acceso a ciertas tablas o columnas
- "validation": devuelve errores de validación de entrada para reglas personalizadas
- "sanitation": aplicar saneamiento de entrada en crear y actualizar
- "multiTenancy": restringe el acceso de los inquilinos en un escenario con múltiples inquilinos
- "customization": proporciona controladores para la personalización de solicitudes y respuestas

El parámetro de configuración "middlewares" es una lista separada por comas de middlewares habilitados. Puede ajustar el comportamiento del middleware utilizando parámetros de configuración específicos del middleware:

- "firewall.reverseProxy": se establece en "true" cuando se utiliza un proxy inverso ("")
- "firewall.allowedIpAddresses": lista de direcciones IP que pueden conectarse ("")
- "cors.allowedOrigins": los orígenes permitidos en los encabezados CORS ("*")
- "cors.allowHeaders": los encabezados permitidos en la solicitud CORS ("Content-Type, X-XSRF-TOKEN")
- "cors.allowMethods": los métodos permitidos en la solicitud CORS ("OPTIONS, GET, PUT, POST, DELETE, PATCH")
- "cors.allowCredentials": para permitir credenciales en la solicitud CORS ("true")
- "cors.exposeHeaders": encabezados de lista blanca a los que los navegadores pueden acceder ("")
- "cors.maxAge": el tiempo que la concesión de CORS es válida en segundos ("1728000")
- "xsrf.excludeMethods": los métodos que no requieren la protección XSRF ("OPTIONS, GET")
- "xsrf.cookieName": el nombre de la cookie de protección XSRF ("XSRF-TOKEN")
- "xsrf.headerName": el nombre del encabezado de protección XSRF ("X-XSRF-TOKEN")
- "ajaxOnly.excludeMethods": los métodos que no requieren AJAX ("OPTIONS, GET")
- "ajaxOnly.headerName": el nombre del encabezado requerido ("X-Requested-With")
- "ajaxOnly.headerValue": el valor del encabezado requerido ("XMLHttpRequest")
- "jwtAuth.mode": configúrelo como "opcional" si desea permitir el acceso anónimo ("requerido")
- "jwtAuth.header": nombre del encabezado que contiene el token JWT ("X-Autorización")
- "jwtAuth.leeway": el número aceptable de segundos de inclinación del reloj ("5")
- "jwtAuth.ttl": el número de segundos que el token es válido ("30")
- "jwtAuth.secret": el secreto compartido utilizado para firmar el token JWT con ("")
- "jwtAuth.algorithms": los algoritmos permitidos, vacío significa 'todos' ("")
- "jwtAuth.audiences": las audiencias permitidas, vacío significa "todos" ("")
- "jwtAuth.issuers": los emisores que están permitidos, vacío significa 'todos' ("")
- "basicAuth.mode": configúrelo como "opcional" si desea permitir el acceso anónimo ("requerido")
- "basicAuth.realm": texto que se le solicitará al mostrar el inicio de sesión ("Se requieren nombre de usuario y contraseña")
- "basicAuth.passwordFile": el archivo a leer para las combinaciones de nombre de usuario / contraseña (".htpasswd")
- "permission.tableHandler": controlador para implementar las reglas de autorización de tablas ("")
- "permission.columnHandler": controlador para implementar las reglas de autorización de columna ("")
- "permission.recordHandler": controlador para implementar reglas de filtro de autorización de registros ("")
- "validation.handler": controlador para implementar reglas de validación para valores de entrada ("")
- "sanitation.handler": controlador para implementar reglas de saneamiento para valores de entrada ("")
- "multiTenancy.handler": controlador para implementar reglas simples de tenencia múltiple ("")
- "customization.beforeHandler": Controlador para implementar la personalización de la solicitud ("")
- "customization.afterHandler": controlador para implementar la personalización de la respuesta ("")

Si no especifica estos parámetros en la configuración, se utilizan los valores predeterminados (entre paréntesis).

##  TreeQL, un GraphQL pragmático

TreeQL le permite crear un "árbol" de objetos JSON en función de la estructura (relaciones) de la base de datos SQL y su consulta.

Se basa libremente en el estándar REST y también está inspirado en json: api.

### CRUD + Lista

La tabla de publicaciones de ejemplo tiene solo unos pocos campos:

    posts  
    =======
    id     
    title  
    content
    created

Las siguientes operaciones de la lista CRUD + actúan en esta tabla.

#### Crear

Si desea crear un registro, la solicitud se puede escribir en formato de URL como:

    POST /records/posts

Tienes que enviar un cuerpo que contiene:

    {
        "title": "Black is the new red",
        "content": "This is the second post.",
        "created": "2018-03-06T21:34:01Z"
    }

Y devolverá el valor de la clave principal del registro recién creado:

    2

#### Leer

Para leer un registro de esta tabla, la solicitud se puede escribir en formato de URL como:

    GET /records/posts/1

Donde "1" es el valor de la clave principal del registro que desea leer. Volverá

    {
        "id": 1
        "title": "Hello world!",
        "content": "Welcome to the first post.",
        "created": "2018-03-05T20:12:56Z"
    }

En las operaciones de lectura puede aplicar uniones.

#### Actualizar

Para actualizar un registro en esta tabla, la solicitud se puede escribir en formato de URL como:

    PUT /records/posts/1

Donde "1" es el valor de la clave principal del registro que desea actualizar. Enviar como un cuerpo:

    {
        "title": "Adjusted title!"
    }

Esto ajusta el título del post. Y el valor de retorno es el número de filas que se establecen:

    1

#### Borrar

Si desea eliminar un registro de esta tabla, la solicitud se puede escribir en formato de URL como:

    DELETE /records/posts/1

Y devolverá el número de filas eliminadas:

    1

#### Lista

Para listar los registros de esta tabla, la solicitud se puede escribir en formato de URL como:

    GET /records/posts

Volverá

    {
        "records":[
            {
                "id": 1,
                "title": "Hello world!",
                "content": "Welcome to the first post.",
                "created": "2018-03-05T20:12:56Z"
            }
        ]
    }

En las operaciones de lista puede aplicar filtros y uniones.

### Filtros

Los filtros proporcionan la funcionalidad de búsqueda, en las llamadas de lista, utilizando el parámetro "filtro". Debe especificar el nombre de la columna, una coma, el tipo de coincidencia, otra coma y el valor que desea filtrar. Estos son tipos de concordancia soportados:

  - "cs": contiene cadena (cadena contiene valor)
  - "sw": comienza con (la cadena comienza con el valor)
  - "ew": termina con (final de cadena con valor)
  - "eq": igual (la cadena o el número coinciden exactamente)
  - "lt": menor que (el número es menor que el valor)
  - "le": menor o igual (el número es menor o igual al valor)
  - "ge": mayor o igual (el número es mayor o igual que el valor)
  - "gt": mayor que (el número es mayor que el valor)
  - "bt": entre (el número está entre dos valores separados por comas)
  - "in": in (el número o la cadena está en una lista de valores separados por comas)
  - "is": es nulo (el campo contiene el valor "NULL")

Puede negar todos los filtros al anteponer un carácter "n", de modo que "eq" se convierta en "neq". Ejemplos de uso del filtro son:

    GET /records/categories?filter=name,eq,Internet
    GET /records/categories?filter=name,sw,Inter
    GET /records/categories?filter=id,le,1
    GET /records/categories?filter=id,ngt,2
    GET /records/categories?filter=id,bt,1,1

Salida:

    {
        "records":[
            {
                "id": 1
                "name": "Internet"
            }
        ]
    }

En la siguiente sección profundizaremos en cómo puede aplicar varios filtros en una sola llamada de lista.

### Filtros múltiples

Los filtros pueden aplicarse aplicando el parámetro "filtro" en la URL. Por ejemplo la siguiente URL:

    GET /records/categories?filter=id,gt,1&filter=id,lt,3

solicitará todas las categorías "donde id> 1 e id <3". Si quieres "where id = 2 o id = 4" debes escribir:

    GET /records/categories?filter1=id,eq,2&filter2=id,eq,4
    
Como ve, agregamos un número al parámetro "filtro" para indicar que se debe aplicar "OR" en lugar de "AND". Tenga en cuenta que también puede repetir "filter1" y crear un "AND" dentro de un "OR". Ya que también puede ir un nivel más profundo agregando una letra (af) puede crear casi cualquier árbol de condición razonablemente complejo.

NB: solo puede filtrar en la tabla solicitada (no incluida en ella) y los filtros solo se aplican en la lista de llamadas.

### Selección de columna

Por defecto, todas las columnas están seleccionadas. Con el parámetro "incluir" puede seleccionar columnas específicas. Puede usar un punto para separar el nombre de la tabla del nombre de la columna. Las columnas múltiples deben estar separadas por comas. Se puede utilizar un asterisco ("*") como comodín para indicar "todas las columnas". Similar a "incluir" puede usar el parámetro "excluir" para eliminar ciertas columnas:

```
GET /records/categories/1?include=name
GET /records/categories/1?include=categories.name
GET /records/categories/1?exclude=categories.id
```

Salida:

```
    {
        "name": "Internet"
    }
```

NB: las columnas que se utilizan para incluir entidades relacionadas se agregan automáticamente y no se pueden dejar fuera de la salida.

### Ordenando

Con el parámetro "orden" se puede ordenar. Por defecto, la clasificación está en orden ascendente, pero al especificar "desc" esto se puede revertir:

```
GET /records/categories?order=name,desc
GET /records/categories?order=id,desc&order=name
```

Salida:

```
    {
        "records":[
            {
                "id": 3
                "name": "Web development"
            },
            {
                "id": 1
                "name": "Internet"
            }
        ]
    }
```

NB: Puede ordenar en múltiples campos utilizando múltiples parámetros de "orden". No se puede ordenar en columnas "unidas".

### Paginación

El parámetro "página" contiene la página solicitada. El tamaño de página predeterminado es 20, pero se puede ajustar (por ejemplo, a 50):

```
GET /records/categories?order=id&page=1
GET /records/categories?order=id&page=1,50
```

Salida:

```
    {
        "records":[
            {
                "id": 1
                "name": "Internet"
            },
            {
                "id": 3
                "name": "Web development"
            }
        ],
        "results": 2
    }
```

NB: las páginas que no están ordenadas no pueden paginarse.

### Uniones / JOIN

Digamos que usted tiene una tabla de publicaciones que tiene comentarios (realizados por los usuarios) y que las publicaciones pueden tener etiquetas.

    posts    comments  users     post_tags  tags
    =======  ========  =======   =========  ======= 
    id       id        id        id         id
    title    post_id   username  post_id    name
    content  user_id   phone     tag_id
    created  message

Cuando desee enumerar las publicaciones con sus usuarios y etiquetas de comentarios, puede solicitar dos rutas de "árbol":

    posts -> comments  -> users
    posts -> post_tags -> tags

Estas rutas tienen la misma raíz y esta solicitud se puede escribir en formato de URL como:

    GET /records/posts?join=comments,users&join=tags

Aquí puede omitir la tabla intermedia que vincula las publicaciones a las etiquetas. En este ejemplo, verá los tres tipos de relaciones de tabla (hasMany, belongsTo y hasAndBelongsToMany) en efecto:

- "post" has many "comments"
- "comment" belongs to "user"
- "post" has and belongs to many "tags"

Esto puede llevar a los siguientes datos JSON:

    {
        "records":[
            {
                "id": 1,
                "title": "Hello world!",
                "content": "Welcome to the first post.",
                "created": "2018-03-05T20:12:56Z",
                "comments": [
                    {
                        id: 1,
                        post_id: 1,
                        user_id: {
                            id: 1,
                            username: "mevdschee",
                            phone: null,
                        },
                        message: "Hi!"
                    },
                    {
                        id: 2,
                        post_id: 1,
                        user_id: {
                            id: 1,
                            username: "mevdschee",
                            phone: null,
                        },
                        message: "Hi again!"
                    }
                ],
                "tags": []
            },
            {
                "id": 2,
                "title": "Black is the new red",
                "content": "This is the second post.",
                "created": "2018-03-06T21:34:01Z",
                "comments": [],
                "tags": [
                    {
                        id: 1,
                        message: "Funny"
                    },
                    {
                        id: 2,
                        message: "Informational"
                    }
                ]
            }
        ]
    }

Verá que se detectan las relaciones "belongsTo" y el valor al que se hace referencia reemplaza el valor de la clave externa. En el caso de "hasMany" y "hasAndBelongsToMany", el nombre de la tabla se utiliza una nueva propiedad en el objeto.

NB: debe crear una restricción de clave externa para que la unión funcione.

### Operaciones por lotes

Cuando desee crear, leer, actualizar o eliminar, puede especificar varios valores de clave principal en la URL. También debe enviar una matriz en lugar de un objeto en el cuerpo de la solicitud para crear y actualizar. 

Para leer un registro de esta tabla, la solicitud se puede escribir en formato de URL como:

    GET /records/posts/1,2

El resultado puede ser:

    [
            {
                "id": 1,
                "title": "Hello world!",
                "content": "Welcome to the first post.",
                "created": "2018-03-05T20:12:56Z"
            },
            {
                "id": 2,
                "title": "Black is the new red",
                "content": "This is the second post.",
                "created": "2018-03-06T21:34:01Z"
            }
    ]

De forma similar, cuando desea realizar una actualización por lotes, la solicitud en formato de URL se escribe como:

    PUT /records/posts/1,2

Donde "1" y "2" son los valores de las claves primarias de los registros que desea actualizar. El cuerpo debe contener el mismo número de objetos, ya que hay claves primarias en la URL:

    [   
        {
            "title": "Adjusted title for ID 1"
        },
        {
            "title": "Adjusted title for ID 2"
        }        
    ]

This adjusts the titles of the posts. And the return values are the number of rows that are set:

    1,1

Esto ajusta los títulos de los mensajes. Y los valores de retorno son el número de filas que se establecen:

### Lo que significa que había dos operaciones de actualización y cada una de ellas había establecido una fila. Las operaciones por lotes utilizan las transacciones de la base de datos, por lo que todas tienen éxito o todas fallan (las exitosas se devuelven).

Para el soporte espacial hay un conjunto adicional de filtros que se pueden aplicar en las columnas de geometría y que comienzan con una "s":

- "sco": contiene espacial (la geometría contiene otra)
- "scr": cruces espaciales (la geometría cruza otra)
- "sdi": separación espacial (la geometría es diferente de otra)
- "seq": espacial igual (la geometría es igual a otra)
- "pecado": intersecciones espaciales (la geometría se interseca con otra)
- "sov": superposiciones espaciales (la geometría se superpone a otra)
- "sto": toques espaciales (la geometría toca a otra)
- "swi": espacial dentro (la geometría está dentro de otra)
- "sic": el espacio está cerrado (la geometría es cerrada y simple)
- "sis": espacial es simple (la geometría es simple)
- "siv": el espacio es válido (la geometría es válida)

Estos filtros se basan en los estándares OGC y también lo es la especificación WKT en la que se representan las columnas de geometría.

### Autenticación

La autenticación se realiza mediante el envío de un encabezado de "Autorización". Identifica al usuario y lo almacena en el $_SESSION super global. Esta variable se puede usar en los controladores de autorización para decidir si o no sombeody debería tener acceso de lectura o escritura a ciertas tablas, columnas o registros. Actualmente hay dos tipos de autenticación admitidos: "Básico" y "JWT". Esta funcionalidad se habilita agregando el middleware 'basicAuth' y / o 'jwtAuth'.

#### utenticación básica

El tipo Básico es compatible con un archivo que contiene a los usuarios y sus contraseñas (con hash) separadas por dos puntos (':'). Cuando las contraseñas se ingresan en texto sin formato, se llenan automáticamente. El nombre de usuario autenticado se almacenará en la $_SESSION['username'] . Debe enviar un encabezado de "Autorización" que contenga un nombre de usuario y una contraseña codificados en base64 url ??y separados por dos puntos después de la palabra "Básico".

    Authorization: Basic dXNlcm5hbWUxOnBhc3N3b3JkMQ

Este ejemplo envía la cadena "username1:password1".

#### Autenticación JWT

El tipo JWT requiere que otro servidor (SSO / Identity) firme un token que contiene notificaciones. Ambos servidores comparten un secreto para que puedan firmar o verificar que la firma es válida. Las reclamaciones se almacenan en la $_SESSION['claims'] . Debe enviar un encabezado de "Autorización X" que contenga un encabezado, cuerpo y firma de token con codificación url base64, cuerpo y firma después de la palabra "Portador" ( lea más sobre JWT aquí ). El estándar dice que necesita usar el encabezado "Autorización", pero esto es problemático en Apache y PHP.

    X-Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWUsImlhdCI6IjE1MzgyMDc2MDUiLCJleHAiOjE1MzgyMDc2MzV9.Z5px_GT15TRKhJCTHhDt5Z6K6LRDSFnLj8U5ok9l7gw

Este ejemplo envía las reclamaciones firmadas:

    {
      "sub": "1234567890",
      "name": "John Doe",
      "admin": true,
      "iat": "1538207605",
      "exp": 1538207635
    }

NB: la implementación de JWT solo admite los algoritmos basados ??en hash HS256, HS384 y HS512.
