<?php

namespace Raiz\Bd\Auxiliadores;

use Phpmig\Api\PhpmigApplication;
use Symfony\Component\Console\Output\NullOutput;

class Rehidratador
{
  private static ?PhpmigApplication $app = null;

  private function __construct()
  {
  }
  public static function ejecutar()
  {
    if (!static::$app) {
      $container = require __DIR__ . '/../../../hidratador.php';
      $output = new NullOutput();
      static::$app = new PhpmigApplication($container, $output);
    }

    static::$app->down();
    static::$app->up();
  }
}
