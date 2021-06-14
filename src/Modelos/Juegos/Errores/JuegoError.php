<?php

namespace Raiz\Modelos\Juegos\Errores;

use Error;
use Raiz\Modelos\Juegos\JuegoAbstracto;

class JuegoError extends Error
{
  public const VER_RESULTADOS_SIN_HABER_COMPLETADO = 200;
  public const RESETEAR_SIN_INICIAR = 201;
  public const REANUDAR_SIN_ESTAR_EN_PAUSA = 202;
  public const RONDA_SIN_ESTAR_EN_PROGRESO = 203;
  public const YA_ESTA_INICIADO = 204;
  public const YA_ESTA_PAUSADO = 205;
  public const VALORES_DE_DADOS_INVALIDOS = 206;
  public const ESTADO_CARGADO_EN_CREACION = 207;
  public const ESTADO_INICIAL_EN_PROGRESO = 208;
  public const CONFIGURACION_FECHAS = 209;
  private const ERROR_DESCONOCIDO = -1;

  public function __construct(JuegoAbstracto $juego, int $codigo)
  {
    switch ($codigo) {
      case self::VER_RESULTADOS_SIN_HABER_COMPLETADO:
        parent::__construct(
          message: "Se intentó leer el resultado del juego#{$juego->id()} cuando aún no se completó.",
          code: $codigo,
        );
        break;
      case self::RESETEAR_SIN_INICIAR:
        parent::__construct(
          message: "Se intentó resetear el juego#{$juego->id()}, pero está en estado sin_iniciar.",
          code: $codigo,
        );
        break;
      case self::REANUDAR_SIN_ESTAR_EN_PAUSA:
        parent::__construct(
          message: "Se intentó reanudar el juego#{$juego->id()}, pero aún no está en pausa.",
          code: $codigo,
        );
        break;
      case self::RONDA_SIN_ESTAR_EN_PROGRESO:
        parent::__construct(
          message: "El juego#{$juego->id()} no está en progreso pero se intentó hacer una ronda.",
          code: $codigo,
        );
        break;
      case self::YA_ESTA_INICIADO:
        parent::__construct(
          message: "El juego#{$juego->id()} está en progreso y no puede ser iniciado. " .
            'Por favor, resetée antes de volver a iniciar.',
          code: $codigo,
        );
        break;
      case self::YA_ESTA_PAUSADO:
        parent::__construct(
          message: "El juego#{$juego->id()} ya se pausó antes y aún sigue estándolo.",
          code: $codigo,
        );
        break;
      case self::VALORES_DE_DADOS_INVALIDOS:
        parent::__construct(
          message: "El juego#{$juego->id()} configuró mal los valores " .
            "mínimo ({$juego->dadoValorMin()}) y máximo ({$juego->dadoValorMax()}).",
          code: $codigo,
        );
      case self::ESTADO_CARGADO_EN_CREACION:
        parent::__construct(
          message: 'Se intentó setear un estado en un juego recién creado. ' .
            'El estado solo se puede preconfigurar si el ID es un valor existente. Por ejemplo, ' .
            'al reinstanciar desde la BD.',
          code: $codigo,
        );
      case self::ESTADO_INICIAL_EN_PROGRESO:
        parent::__construct(
          message: 'Se intentó setear el estado de progreso en un juego recién creado.',
          code: $codigo,
        );
      case self::CONFIGURACION_FECHAS:
        parent::__construct(
          message: "La configuración de fechas para el juego#{$juego->id()} es inválida.",
          code: $codigo,
        );
      default:
        parent::__construct(
          message: "Acción {{$codigo}} desconocida.",
          code: self::ERROR_DESCONOCIDO,
        );
        break;
    }
  }
}
