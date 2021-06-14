<?php

use EndyJasmi\Cuid;
use Raiz\Bd\Auxiliadores\Migrador;

class HidrateJugadores extends Migrador
{
  private $tabla = 'jugadores';

  public function up()
  {
    $ids = $this->buildIds();
    $sql = "INSERT INTO `$this->tabla` VALUES
    ('{$ids[0]}', 'Sergio Peña', '2000-02-10T00:00:00'),
    ('{$ids[1]}', 'Pamela Sánchez', '2005-12-30T00:00:00'),
    ('{$ids[2]}', 'Yesica Ruano', '2010-06-15T00:00:00'),
    ('{$ids[3]}', 'Jose Miguel Caballero', '1990-03-05T00:00:00'),
    ('{$ids[4]}', 'Ariadna Cantos', '1985-01-25T00:00:00'),
    ('{$ids[5]}', 'Andrés Lema', '2012-04-27T00:00:00'),
    ('{$ids[6]}', 'Judith Ortega', '1999-08-01T00:00:00');";

    $this->run($sql);
  }

  public function down()
  {
    $sql = "TRUNCATE TABLE `$this->tabla`;";

    $this->run($sql);
  }

  /** @return Array<string> */
  private function buildIds(): array
  {
    return [
      Cuid::slug(),
      Cuid::slug(),
      Cuid::slug(),
      Cuid::slug(),
      Cuid::slug(),
      Cuid::slug(),
      Cuid::slug(),
    ];
  }
}
