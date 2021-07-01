<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Raiz\Bd\Auxiliadores\Rehidratador;
use Raiz\Bd\GeneralaDao;
use Raiz\Bd\JugadorDao;
use Raiz\Modelos\Juegos\Generala;
use Raiz\Modelos\Jugador;

final class GeneralaDaoTest extends TestCase
{
  private static string $id;

  public static function setUpBeforeClass(): void
  {
    Rehidratador::ejecutar();
  }

  /** @test */
  public function lista_todos_los_juegos(): void
  {
    $juegos = GeneralaDao::listar();

    $this->assertIsArray($juegos);
    $this->assertGreaterThan(0, sizeof($juegos));
    $this->assertContainsOnlyInstancesOf(Generala::class, $juegos);

    // TODO: verificar los jugadores de cada partida
  }

  /** @test */
  public function persiste_un_nuevo_juego(): void
  {
    $this->expectNotToPerformAssertions();

    $jugadores = JugadorDao::listar();

    $instancia = new Generala(jugadores: [$jugadores[0], $jugadores[1]]);

    self::$id = $instancia->id();

    GeneralaDao::persistir($instancia);
  }

  /** @test */
  public function busca_por_id(): void
  {
    $instancia = GeneralaDao::buscarPorId(self::$id);

    $this->assertInstanceOf(Generala::class, $instancia);
    // TODO: verificar los jugadores de cada partida
  }

  /** @test */
  public function actualiza_juego(): void
  {
    /** @var Generala */
    $antes = GeneralaDao::buscarPorId(self::$id);

    $antes->configurarRondas(6);
    $antes->iniciar();
    $antes->realizarRonda();

    GeneralaDao::actualizar($antes);

    $despues = GeneralaDao::buscarPorId(self::$id);

    $this->assertEquals($antes->estado(), $despues->estado());
  }

  /** @test */
  public function borra_juego(): void
  {
    GeneralaDao::borrar(self::$id);

    $debeSerNulo = GeneralaDao::buscarPorId(self::$id);

    $this->assertNull($debeSerNulo);
    // TODO: verificar que los jugadores de la partida también se borraron
  }
}
