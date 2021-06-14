<?php

namespace Raiz\Modelos;

use EndyJasmi\Cuid;
use Error;
use Raiz\Auxiliadores\Serializador;

abstract class ModeloBase implements Serializador
{
  private string $id;

  public function __construct(?string $id)
  {
    $this->id = $id ? $id : Cuid::slug();
  }

  public function id(): string
  {
    return $this->id;
  }

  public function esIgual(ModeloBase $instancia): bool
  {
    return $this->id === $instancia->id();
  }

  public function serializar(): array
  {
    throw new Error('Serialización no implementada.');
  }

  public static function deserializar(array $datos): ModeloBase
  {
    throw new Error('Deserialización no implementada.');
  }
}
