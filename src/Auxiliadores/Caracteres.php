<?php

namespace Raiz\Auxiliadores;

class Caracteres
{
  public static function escapar(string $caracteres): string
  {
    return addslashes($caracteres);
  }
}
