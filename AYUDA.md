# Configuración del entorno y comandos útiles

> Volver al [**Documento inicial**](README.md).

## Comandos útiles

Todos deben correrse en una consola en la carpeta raíz de este proyecto.

- `composer run migrar` para recrear las tablas.
- `composer run hidratar` para llenar de datos las tablas.

## Cómo correr los tests

Recuerden que para ejecutar los tests, al menos en VSCode, deben:

1. Ir al ícono del "bichito + el de play".
2. Seleccionar cualquiera de las dos opciones ("Test archivo actual" o "Todos los tests").
3. Abrir el archivo de tests que quieran ejecutar.
4. Darle clic al botón verde de play o apretar F5.

## Configuración inicial

Antes de poder programar cualquier cosa, necesitaremos inicializar las distintas herramientas. Para ello se debe hacer lo siguiente:

#### Inicializar composer y yarn

1. Acceder a la terminal.
2. Ir a la carpeta del proyecto.
3. Ejecutar `composer install`. Este comando se encargará de instalar todas las dependencias definidas en el archivo `composer.json`. Sin este paso, nada funcionará.
4. Ejecutar `yarn install`.

#### Crear archivo de variables de entorno

1. Copiar el archivo `.env.ejemplo` y renombrarlo a `.env`.
2. Acceder al archivo `.env` y editar los valores de las variables `DB_USER` y `DB_PASS`. Estas dos variables serán utilizadas en la aplicación para conectarse a la Base de Datos.
   - `DB_USER` deberá tener asignado el nombre del usuario con el que se conectan a la Base de Datos.
   - `DB_PASS` deberá tener la contraseña que utilizan para conectarse a la BD con el usuario anterior. Si no configuraron contraseña, dejarla como string vacío.

#### Crear la base de datos

1. Acceder a la consola de MariaDB desde la terminal. Para ello deben ejecutar el siguiente comando:
   - Si tienen un usuario con contraseña:
     ```sh
     mysql -u nombre_del_usuario -p
     ```
   - Si tienen un usuario sin contraseña:
     ```sh
     mysql -u nombre_del_usuario
     ```
   - Si no crearon un usuario específico, pueden utilizar el usuario **root**.
2. Crear la base de datos. Para ello, pueden copiar el código SQL que hay en el archivo `creacion-db.sql`, pegarlo en la consola SQL y ejecutarlo.

#### Crear las tablas y los datos

1. Acceder a la terminal.
2. Ir a la carpeta del proyecto.
3. Ejecutar `composer run migrar`. Este comando se de crear todas las tablas.
4. Ejecutar `composer run hidratar`. Este comando se de crear todos los datos iniciales.

#### Crear una migración

> NOTA: ante la duda de qué o cómo resolver la migración, consultar en el canal **#dudas-unidad-3** del discord.

1. Ejecutar `composer run crear:migracion <nombre_de_la_migracion>`, donde `<nombre_de_la_migracion>` debe estar en minúsculas y separado por guiones bajos. Debe describir la acción de la migración. Ejemplos:
   - **agrega_tabla_competencias**
   - **modifica_tabla_jugadores**
   - **actualiza_columna_XXXXXXX_en_jugadores**
2. Acceder al nuevo archivo y escribir el código SQL para la operción de hacer (**up**) y deshacer (**down**) la migración.

## Nota de interés

Este esqueleto está construido con las siguientes tecnologías:

- Diseño parcial en MVC.
- [Brick\DateTime](https://github.com/brick/date-time): librería para manejo de fechas inmutables. Lo de inmutabilidad es super importante porque significa que las instancias no cambian su contenido interno al realizar una operación sobre ellas, sino que crean una nueva instancia.
- [Dotenv](https://github.com/vlucas/phpdotenv): librería para leer archivos `.env` y similares y cargar los datos en las variables globales.
- [EndyJasmi\Cuid](https://github.com/endyjasmi/cuid): es una librería generar ids únicos a nivel profesional/global. Esta librería permite que la BD delegue el trabajo de generar IDs únicos al servidor o hasta al cliente. Se utilizó para mantener el diseño orientado a objetos lo más correcto posible. Esto se debe a que son los objetos realmente los responsables de generar su código de identificación único.

Todas estas librerías son herramientas profesionales que podrían llegar a utilizar en algún desarrollo personal o laboral.
