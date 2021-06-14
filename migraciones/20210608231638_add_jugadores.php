<?php

use Raiz\Bd\Auxiliadores\Migrador;

class AddJugadores extends Migrador
{
  private $tabla = 'jugadores';

  public function up()
  {
    $sql = "CREATE TABLE `$this->tabla` (
      `id` varchar(256) NOT NULL,
      `nombre` varchar(256) NOT NULL,
      `ingreso` DATETIME NOT NULL,
      PRIMARY KEY (`id`)
    );";

    $this->run($sql);
  }

  public function down()
  {
    $sql = "DROP TABLE `$this->tabla`;";

    $this->run($sql);
  }
}
