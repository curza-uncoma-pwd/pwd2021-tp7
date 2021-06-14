<?php

use Raiz\Bd\Auxiliadores\Migrador;

class AddParticipantes extends Migrador
{
  private $tabla = 'participantes';

  public function up()
  {
    $sql = "CREATE TABLE `$this->tabla` (
      `id` varchar(256) NOT NULL,
      `jugador_id` varchar(256) NOT NULL,
      `juego_id` varchar(256) NOT NULL,

      PRIMARY KEY (`id`),
      KEY `participantes_jugador_fk` (`jugador_id`),
      KEY `participantes_juego_fk` (`juego_id`),
      CONSTRAINT `participantes_jugador_fk` FOREIGN KEY (`jugador_id`) REFERENCES `jugadores` (`id`),
      CONSTRAINT `participantes_juego_fk` FOREIGN KEY (`juego_id`) REFERENCES `juegos` (`id`)
    );";

    $this->run($sql);
  }

  public function down()
  {
    $sql = "DROP TABLE `$this->tabla`;";

    $this->run($sql);
  }
}
