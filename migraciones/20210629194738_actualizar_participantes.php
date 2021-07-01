<?php

use Raiz\Bd\Auxiliadores\Migrador;

class ActualizarParticipantes extends Migrador
{
  private $tabla = 'participantes';

  public function up()
  {
    $sql = "ALTER TABLE `$this->tabla` DROP INDEX `PRIMARY`;";

    $this->run($sql);

    $sql = "ALTER TABLE `$this->tabla` DROP COLUMN `id`;";

    $this->run($sql);
  }

  public function down()
  {
    $sql = "ALTER TABLE `$this->tabla` ADD COLUMN (`id` varchar(256) NOT NULL);";

    $this->run($sql);

    $sql = "ALTER TABLE `$this->tabla` ADD PRIMARY KEY(`id`);";

    $this->run($sql);
  }
}
