<?php

namespace Raiz\Bd\Auxiliadores;

use PDO;
use Phpmig\Migration\Migration as Base;

class Migrador extends Base
{
  protected function run(string $sql)
  {
    $container = $this->getContainer();
    /** @var PDO */
    $db = $container['db'];

    $db->query('SET FOREIGN_KEY_CHECKS = 0;');
    $statement = $db->query($sql);
    $db->query('SET FOREIGN_KEY_CHECKS = 1;');

    $statement->closeCursor();
  }

  protected function read(string $sql)
  {
    $container = $this->getContainer();
    /** @var PDO */
    $db = $container['db'];

    $statement = $db->query($sql);

    $datos = $statement->fetchAll(PDO::FETCH_ASSOC);

    $statement->closeCursor();

    return $datos;
  }
}
