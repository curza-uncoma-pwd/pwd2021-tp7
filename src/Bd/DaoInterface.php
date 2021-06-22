<?php

namespace Raiz\Bd;

use Raiz\Auxiliadores\Serializador;
use Raiz\Modelos\ModeloBase;

interface DaoInterface
{
  /** @return Array<ModeloBase> */
  public static function listar(): array;
  public static function buscarPorId(string $id): ?ModeloBase;

  public static function persistir(Serializador $instancia): void;
  public static function actualizar(Serializador $instancia): void;
  public static function borrar(string $id): void;
}
