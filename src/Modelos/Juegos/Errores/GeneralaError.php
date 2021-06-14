<?php

namespace Raiz\Modelos\Juegos\Errores;

use Error;
use Raiz\Modelos\Juegos\Generala;

class GeneralaError extends Error
{
  public const FALTA_CONFIGURAR = 300;
  private const ERROR_DESCONOCIDO = -1;

  public function __construct(Generala $juego, int $codigo)
  {
    switch ($codigo) {
      case self::FALTA_CONFIGURAR:
        parent::__construct(
          message: "Faltan configurar las rondas de la generala#{$juego->id()}. " .
            "Invoque el método `->configurarRondas(int \$rondas)`.",
          code: $codigo,
        );
        break;
      default:
        parent::__construct(
          message: "Acción {{$codigo}} desconocida.",
          code: self::ERROR_DESCONOCIDO,
        );
        break;
    }
  }
}
