<?php

namespace Raiz\Utilidades;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;
use Raiz\Modelos\ModeloBase;

final class Logger
{
  private static MonologLogger $instancia;

  public static function info(?ModeloBase $modelo = null, string $mensaje): void
  {
    $logger = self::instancia();

    if (!is_null($modelo)) {
      $nombreDelaClase = get_class($modelo);
    }

    $logger->info(
      is_null($modelo)
        ? $mensaje
        : "[$nombreDelaClase#{$modelo->id()}] $mensaje",
    );
  }

  private static function instancia(): MonologLogger
  {
    if (isset(self::$instancia)) {
      return self::$instancia;
    }
    $formato = "%message%\n";
    $formateador = new LineFormatter($formato, 'Y-m-d H:i:s');
    $manejadorDeStreamDeTexto = new StreamHandler(
      'php://stdout',
      MonologLogger::INFO,
    );
    $manejadorDeStreamDeTexto->setFormatter($formateador);

    self::$instancia = new MonologLogger('principal');

    self::$instancia->pushHandler($manejadorDeStreamDeTexto);

    return self::$instancia;
  }
}
