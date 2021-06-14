<?php

use EndyJasmi\Cuid;
use Raiz\Bd\Auxiliadores\Migrador;
use Raiz\Modelos\Juegos\Generala;

class HidrateJuegos extends Migrador
{
  private $tabla = 'juegos';

  public function up()
  {
    $ids = $this->buildIds();
    $tipo = addslashes(Generala::class);
    $sinIniciar = Generala::SIN_INICIAR;
    $completado = Generala::COMPLETADO;
    $enPausa = Generala::EN_PAUSA;
    $sql = "INSERT INTO `$this->tabla` VALUES
    ('{$ids[0]}', '$tipo', '$sinIniciar', 3, 0, '2020-02-10T15:25:30', NULL),
    ('{$ids[1]}', '$tipo', '$completado', 3, 3, '2020-12-30T21:20:59', '2020-12-30T22:30:09'),
    ('{$ids[2]}', '$tipo', '$enPausa',    5, 2, '2021-03-01T10:45:41', NULL);";

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
    return [Cuid::slug(), Cuid::slug(), Cuid::slug()];
  }
}
