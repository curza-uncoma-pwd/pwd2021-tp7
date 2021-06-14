<?php

use Raiz\Bd\Auxiliadores\Migrador;

class AddJuegos extends Migrador
{
  private $tabla = 'juegos';

  public function up()
  {
    $sql = "CREATE TABLE `$this->tabla` (
      `id` varchar(256) NOT NULL,
      `tipo` varchar(124) NOT NULL,
      `estado` varchar(124) NOT NULL,
      `rondas` int NOT NULL,
      `rondaActual` int NOT NULL,

      `inicio` DATETIME NOT NULL,
      `fin` DATETIME NULL DEFAULT NULL,
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
