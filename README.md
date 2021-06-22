# PWD2021 TP7: Data Access Objects (DAOs)

Los Mapeadores de datos a objetos, o DAOs, son clases intermedias entre el sistema de almacenamiento de datos (nuestras bases de datos) y nuestro código escrito en POO. Dado que las bases de datos no están escritas en ese paradgima, necesitamos de clases asistentes o intermedias que contengan de forma exclusiva toda la interacción necesaria. Así, dejamos bien separado el código propio de los modelos orientados a objetos, de las consultas a bases de datos.

## Comandos necesarios

Todas las recomendaciones para manejar el trabajo las pueden encontrar en el archivo de [AYUDA.md](AYUDA.md).

## Requisitos para la entrega y evaluación del trabajo

- Resolver todos los objetivos.
- Verificar que los tests pasen.
- Respetar las reglas definidas en la teoría respecto a los namespaces. Ignorar el uso correcto de mayúsculas y minúsculas será motivo para pedir rehacer el trabajo.
- La nota del TP tendrá un valor aprobado/desaprobado que se tendrá en cuenta para la promoción de la materia.
- Manejar bien los errores esperados.
- Completar todos los objetivos del TP.

## Aspectos a considerar

- Todas las clases DAO deben implementar la interfaz **DaoInterface**.
- Las clases DAO deben ser las únicas clases que ejecuten código SQL.
- Pasar parámetros utilizando el sistema de argumentos nominales. Documentación: https://www.php.net/releases/8.0/en.php#named-arguments. Ejemplo:

  ```php
  function metodo(int $edad, string $nombre)
  {
  }

  // esto es más entendible
  metodo(edad: 24, nombre: 'Norberto');
  // que esto otro (ambos son válidos)
  metodo(24, 'Norberto');
  ```

  Esta forma de pasar argumentos en la invocación de una función o método ayuda a saber qué significa cada parámetro en vez de estar memorizando el significado de cada uno de ellos según la posición.

- Se agregó una nueva interfaz: `Raiz\Auxiliadores\Serializador` que describe la firma de los métodos `serializar` y `deserializar` necesarios para implementar en las clases del Modelo que deban ser persistidas.
- Se agregó una nueva clase `Raiz\Auxiliadores\FechaHora` que se encarga de transformar strings a fechas y viceversa. Útil para cuando se deban inicializar los atributos de tipo fecha de las clases del Modelo como también para cuando deban serializarse.
- La clase **ModeloBase** ahora implementa la interfaz `Serializador` con los métodos implementados sin ninguna operación, para que cada clase específica decida si implementar o no la serialización. Esto es porque no todas las clases del modelo necesitan guardar su información en la base de datos.
- Se agregó la clase `Raiz\Bd\ConexionBd` necesaria para poder leer y escribir a la base de datos a través de los DAO.
- Se agregó la interface `Raiz\Bd\DaoInterface` que describe los métodos que todos los DAO deberán implementar.
- Se implementó el método `serializar` en la clase `JuegoAbstracto` que se encarga de serializar sus propios atributos.
- Se implementó el método `serializar` en la clase `Generala` que utiliza el método padre y le agrega sus propios atributos.
- Se implementó el método `deserializar` en la clase `Generala`.
- Se agregaron los atributos `LocalDateTime $inicio` y `?LocalDateTime $fin` en `JuegoAbstracto`.
- Se implementaron los métodos `validarEstado` y `validarFechas` que se encargan de asegurarse de que no se setean datos erróneos.

## Objetivos principales del práctico

- Actualizar la clase **Jugador** para que implemente lo siguiente:
  - Un nuevo atributo de tipo **LocalDateTime** con nombre **$ingreso**. Para usar la clase **LocalDateTime** deben importarla (no es nativa de PHP, sino parte de la librería **Brick\DateTime**). Docs: https://github.com/brick/date-time
  - Agregar método setter para el atributo `$nombre`.
  - Implementar los métodos `serializar` y `deserializar` para devolver los atributos como arreglo asociativo.
- Implementar la clase **JugadorDAO** para persistir y consultar datos de la base de datos:
  - Debe implementar la interface **DatoInterface**.
  - Debe pasar todos los tests.

## Objetivos secundarios

- Si alguna de las clases que deben persistirse sufrieron cambios por los objetivos secundarios, deberán realizar una migración para alterar la tabla de la base de datos, como así también modificar (a mano) los archivos de hidratación asociados a esa tabla.
