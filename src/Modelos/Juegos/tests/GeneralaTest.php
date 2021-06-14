<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Raiz\Modelos\Juegos\Errores\GeneralaError;
use Raiz\Modelos\Juegos\Errores\JuegoError;
use Raiz\Modelos\Juegos\Generala;
use Raiz\Modelos\Jugador;

final class GeneralaTest extends TestCase
{
  /** @test */
  public function el_proceso_normal_funciona_correctamente(): void
  {
    $jugadores = [new Jugador(nombre: 'Jugador 1')];
    $generala = new Generala(jugadores: $jugadores);

    $generala->configurarRondas(2);

    $this->assertEquals(Generala::SIN_INICIAR, $generala->estado());

    $generala->iniciar();

    $this->assertEquals(Generala::EN_PROGRESO, $generala->estado());

    $generala->realizarRonda();

    $this->assertEquals(Generala::EN_PROGRESO, $generala->estado());

    $generala->pausar();

    $this->assertEquals(Generala::EN_PAUSA, $generala->estado());

    $generala->reanudar();

    $this->assertEquals(Generala::EN_PROGRESO, $generala->estado());

    $generala->realizarRonda();

    $this->assertEquals(Generala::COMPLETADO, $generala->estado());

    $generala->verResultado();

    $generala->resetear();

    $this->assertEquals(Generala::SIN_INICIAR, $generala->estado());
  }

  /** @test */
  public function falla_al_iniciar_sin_configurar(): void
  {
    $this->expectException(GeneralaError::class);
    $this->expectExceptionCode(GeneralaError::FALTA_CONFIGURAR);

    $jugadores = [new Jugador(nombre: 'Jugador 1')];
    $generala = new Generala(jugadores: $jugadores);

    $generala->iniciar();
  }

  /** @test */
  public function falla_al_intentar_cambiar_la_configuracion_en_medio_de_una_partida(): void
  {
    $this->expectException(JuegoError::class);
    $this->expectExceptionCode(JuegoError::YA_ESTA_INICIADO);

    $jugadores = [new Jugador(nombre: 'Jugador 1')];
    $generala = new Generala(jugadores: $jugadores);

    $generala->configurarRondas(2);
    $generala->iniciar();
    $generala->configurarRondas(4);
  }

  /** @test */
  public function falla_al_intentar_iniciar_dos_veces(): void
  {
    $this->expectException(JuegoError::class);
    $this->expectExceptionCode(JuegoError::YA_ESTA_INICIADO);

    $jugadores = [new Jugador(nombre: 'Jugador 1')];
    $generala = new Generala(jugadores: $jugadores);

    $generala->configurarRondas(2);
    $generala->iniciar();
    $generala->iniciar();
  }

  /** @test */
  public function falla_al_pausar_dos_veces(): void
  {
    $this->expectException(JuegoError::class);
    $this->expectExceptionCode(JuegoError::YA_ESTA_PAUSADO);

    $jugadores = [new Jugador(nombre: 'Jugador 1')];
    $generala = new Generala(jugadores: $jugadores);

    $generala->configurarRondas(2);
    $generala->iniciar();

    $generala->pausar();
    $generala->pausar();
  }

  /** @test */
  public function falla_al_reanudar_sin_pausar(): void
  {
    $this->expectException(JuegoError::class);
    $this->expectExceptionCode(JuegoError::REANUDAR_SIN_ESTAR_EN_PAUSA);

    $jugadores = [new Jugador(nombre: 'Jugador 1')];
    $generala = new Generala(jugadores: $jugadores);

    $generala->configurarRondas(2);
    $generala->iniciar();

    $generala->reanudar();
  }

  /** @test */
  public function falla_al_realizar_ronda_mientras_esta_pausado(): void
  {
    $this->expectException(JuegoError::class);
    $this->expectExceptionCode(JuegoError::RONDA_SIN_ESTAR_EN_PROGRESO);

    $jugadores = [new Jugador(nombre: 'Jugador 1')];
    $generala = new Generala(jugadores: $jugadores);

    $generala->configurarRondas(2);
    $generala->iniciar();
    $generala->pausar();

    $generala->realizarRonda();
  }

  /** @test */
  public function falla_al_ver_resultados_cuando_no_puede(): void
  {
    $this->expectException(JuegoError::class);
    $this->expectExceptionCode(JuegoError::VER_RESULTADOS_SIN_HABER_COMPLETADO);

    $jugadores = [new Jugador(nombre: 'Jugador 1')];
    $generala = new Generala(jugadores: $jugadores);

    $generala->configurarRondas(2);
    $generala->iniciar();

    $generala->verResultado();
  }

  /** @test */
  public function falla_al_resetear_sin_haber_iniciado(): void
  {
    $this->expectException(JuegoError::class);
    $this->expectExceptionCode(JuegoError::RESETEAR_SIN_INICIAR);

    $jugadores = [new Jugador(nombre: 'Jugador 1')];
    $generala = new Generala(jugadores: $jugadores);

    $generala->configurarRondas(2);

    $generala->resetear();
  }
}
