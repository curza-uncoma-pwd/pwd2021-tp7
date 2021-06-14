<?php

namespace Raiz\Bd;

use Closure;
use PdoDebugger;
use Raiz\Utilidades\Logger;

final class ConexionBd
{
  private static ?\PDO $conexion = null;

  private function __construct()
  {
  }

  /**
   * Ejemplo de uso:
   * ```php
   * static::escribir(
   *   sql: "SELECT * FROM jugadores",
   *   // Opcional
   *   params: [],
   * )
   * ```
   *
   * @param string $sql Consulta SQL.
   * @param array $params Arreglo asociativo con los
   *  parámetros utilizados en la consulta.
   */
  public static function escribir(string $sql, array $params = []): void
  {
    $conexion = self::conectar();
    $consulta = $conexion->prepare($sql);

    Logger::info(mensaje: PdoDebugger::show($sql, $params));

    $consulta->execute($params);

    $consulta->closeCursor();
  }
  /**
   * Ejemplo de uso:
   * ```php
   * static::leer(
   *   sql: "SELECT * FROM jugadores",
   *   // Opcional
   *   params: [],
   *   procesador: function (PDOStatement $consulta) {
   *     return $consulta->fetch(PDO::FETCH_ASSOC);
   *   },
   * )
   * ```
   *
   * @param string $sql Consulta SQL.
   * @param array $params Arreglo asociativo con los
   *  parámetros utilizados en la consulta.
   * @param Closure $transformador Es una función que se
   * utiliza para procesar los datos de la db.
   */
  public static function leer(
    string $sql,
    array $params = [],
    Closure $transformador
  ): mixed {
    $conexion = self::conectar();
    $consulta = $conexion->prepare($sql);

    Logger::info(mensaje: PdoDebugger::show($sql, $params));

    $consulta->execute($params);

    $data = $transformador($consulta);

    $consulta->closeCursor();

    return $data;
  }

  public static function conectar(): \PDO
  {
    if (is_null(static::$conexion)) {
      static::$conexion = new \PDO(
        "mysql:host=127.0.0.1;dbname={$_ENV['DB_NAME']};charset=utf8mb4",
        $_ENV['DB_USER'],
        $_ENV['DB_PASS'],
        [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION],
      );
    }

    return static::$conexion;
  }

  public function desconectar(): void
  {
    static::$conexion = null;
  }

  public function __clone()
  {
  }

  public function __wakeup()
  {
  }
}
