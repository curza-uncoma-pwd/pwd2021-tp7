<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Raiz\Bd\Auxiliadores\Rehidratador;
use Raiz\Bd\JugadorDao;
use Raiz\Modelos\Jugador;

final class JugadorDaoTest extends TestCase
{
  private static string $id;

  public static function setUpBeforeClass(): void
  {
    Rehidratador::ejecutar();
  }

  /** @test */
  public function lista_todos_los_jugadores(): void
  {
    $jugadores = JugadorDao::listar();

    $this->assertIsArray($jugadores);
    $this->assertGreaterThan(0, sizeof($jugadores));
    $this->assertContainsOnlyInstancesOf(Jugador::class, $jugadores);
  }

  /** @test */
  public function persiste_un_nuevo_jugador(): void
  {
    $this->expectNotToPerformAssertions();

    $instancia = new Jugador(nombre: 'Nuevo Jugador');

    self::$id = $instancia->id();

    JugadorDao::persistir($instancia);
  }

  /** @test */
  public function busca_por_id(): void
  {
    $instancia = JugadorDao::buscarPorId(self::$id);

    $this->assertInstanceOf(Jugador::class, $instancia);
  }

  /** @test */
  public function actualiza_jugador(): void
  {
    /** @var Jugador */
    $antes = JugadorDao::buscarPorId(self::$id);

    $antes->setNombre('Cambio de nombre');

    JugadorDao::actualizar($antes);

    /** @var Jugador */
    $despues = JugadorDao::buscarPorId(self::$id);

    $this->assertEquals($antes->nombre(), $despues->nombre());
  }

  /** @test */
  public function borra_jugador(): void
  {
    JugadorDao::borrar(self::$id);

    $debeSerNulo = JugadorDao::buscarPorId(self::$id);

    $this->assertNull($debeSerNulo);
  }
}
