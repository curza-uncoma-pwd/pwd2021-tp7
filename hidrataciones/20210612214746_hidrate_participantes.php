<?php

use EndyJasmi\Cuid;
use Raiz\Bd\Auxiliadores\Migrador;

class HidrateParticipantes extends Migrador
{
  private $tabla = 'participantes';

  public function up()
  {
    $jugadores = $this->read('SELECT id FROM jugadores');
    $juegos = $this->read('SELECT id FROM juegos');
    $ids = $this->buildIds();
    $ids1 = $ids[0];
    $ids2 = $ids[1];
    $ids3 = $ids[2];

    $sql = "INSERT INTO `$this->tabla` VALUES
    ('{$ids1[0]}', '{$jugadores[0]['id']}', '{$juegos[0]['id']}'),
    ('{$ids1[1]}', '{$jugadores[1]['id']}', '{$juegos[0]['id']}'),
    ('{$ids1[2]}', '{$jugadores[2]['id']}', '{$juegos[0]['id']}'),

    ('{$ids2[0]}', '{$jugadores[3]['id']}', '{$juegos[1]['id']}'),
    ('{$ids2[1]}', '{$jugadores[4]['id']}', '{$juegos[1]['id']}'),

    ('{$ids3[0]}', '{$jugadores[5]['id']}', '{$juegos[2]['id']}'),
    ('{$ids3[1]}', '{$jugadores[6]['id']}', '{$juegos[2]['id']}');";

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
      [Cuid::slug(), Cuid::slug(), Cuid::slug()],
      [Cuid::slug(), Cuid::slug()],
      [Cuid::slug(), Cuid::slug()],
    ];
  }
}
