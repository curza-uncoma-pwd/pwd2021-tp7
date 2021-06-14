<?php

namespace Raiz\Bd;

use Raiz\Auxiliadores\Serializador;

interface DaoInterface
{
  public static function listar(): array;
  public static function buscarPorId(string $id);
  public static function persistir(Serializador $instancia): void;
  public static function actualizar(Serializador $instancia): void;
  public static function borrar(string $id): void;
}
