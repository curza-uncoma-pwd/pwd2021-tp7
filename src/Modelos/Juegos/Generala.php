<?php

namespace Raiz\Modelos\Juegos;

use Raiz\Modelos\Cubilete;
use Raiz\Modelos\Juegos\Errores\GeneralaError;
use Raiz\Modelos\Juegos\Errores\JuegoError;
use Raiz\Modelos\Jugador;
use Raiz\Utilidades\Logger;

final class Generala extends JuegoAbstracto
{
  private int $rondas;

  /**
   * Si el estado es SIN_INICIAR: debe ser -1.
   * Si el estado es COMPLETADO: debe ser igual a la cantidad
   * de rondas.
   * Si es cualquier otro estado: debe ser un valor mayor a 0 y
   * menor a la cantidad de rondas.
   */
  private int $rondaActual;

  /** @param Array<Jugador> $jugadores */
  public function __construct(
    ?string $id = null,
    ?string $estado = null,
    ?string $inicio = null,
    ?string $fin = null,
    int $rondas = -1,
    int $rondaActual = -1,
    array $jugadores
  ) {
    parent::__construct(
      id: $id,
      estado: $estado,
      inicio: $inicio,
      fin: $fin,
      cantidadDeDados: 5,
      dadoValorMin: 1,
      dadoValorMax: 6,
      jugadores: $jugadores,
    );

    $this->rondas = $rondas;
    $this->rondaActual = $rondaActual;
  }

  public function serializar(): array
  {
    $datos = parent::serializar();

    $datos['tipo'] = self::class;
    $datos['rondas'] = $this->rondas;
    $datos['rondaActual'] = $this->rondaActual;

    return $datos;
  }

  public static function deserializar(array $datos): self
  {
    return new self(
      id: $datos['id'],
      estado: $datos['estado'],
      inicio: $datos['inicio'],
      fin: $datos['fin'],
      rondas: $datos['rondas'],
      rondaActual: $datos['rondaActual'],
      // NOTA: el mapeo transforma datos. Toma el
      // valor de entrada y devuelve otra cosa.
      jugadores: array_map(
        // NOTA: Función lambda
        fn($jugador) => Jugador::deserializar($jugador),
        $datos['jugadores'],
      ),
    );
  }

  public function configurarRondas(int $rondas)
  {
    if ($this->estado() !== self::SIN_INICIAR) {
      throw new JuegoError(juego: $this, codigo: JuegoError::YA_ESTA_INICIADO);
    }
    $this->rondas = $rondas;
  }

  public function iniciar(): void
  {
    if ($this->rondas < 0) {
      throw new GeneralaError(
        juego: $this,
        codigo: GeneralaError::FALTA_CONFIGURAR,
      );
    }
    $this->rondaActual = 0;

    parent::iniciar();
  }

  protected function procesarRonda(): void
  {
    $this->rondaActual++;

    Logger::info(modelo: $this, mensaje: "Ronda nº{$this->rondaActual}:");

    foreach ($this->jugadores() as $jugador) {
      $jugador->realizarTurno(cubilete: $this->cubilete());

      Logger::info(modelo: $jugador, mensaje: "{$jugador->nombre()} obtuvo:");

      $this->calcularResultadoDeJugada(cubilete: $this->cubilete());
    }
  }

  protected function verificarSiSeCompleto(): bool
  {
    return $this->rondaActual === $this->rondas;
  }

  public function verResultado(): void
  {
    if ($this->estado() !== self::COMPLETADO) {
      throw new JuegoError(
        juego: $this,
        codigo: JuegoError::VER_RESULTADOS_SIN_HABER_COMPLETADO,
      );
    }

    Logger::info(
      mensaje: 'En esta generala no hay ganadores (faltó hacer 🙃).',
    );
  }

  private function calcularResultadoDeJugada(Cubilete $cubilete)
  {
    [$valores, $cantidades] = $cubilete->resultado();

    Logger::info(mensaje: '- Por número:');

    Logger::info(mensaje: "  - As:\t\t\t1 * {$valores[1]} = {$valores[1]}");
    Logger::info(mensaje: "  - Dos:\t\t2 * {$valores[2]} = $cantidades[2]");
    Logger::info(mensaje: "  - Tres:\t\t3 * {$valores[3]} = $cantidades[3]");
    Logger::info(mensaje: "  - Cuatro:\t\t4 * {$valores[4]} = $cantidades[4]");
    Logger::info(mensaje: "  - Cinco:\t\t5 * {$valores[5]} = $cantidades[5]");
    Logger::info(mensaje: "  - Seis:\t\t6 * {$valores[6]} = $cantidades[6]");

    Logger::info(mensaje: '- Por jugada:');

    Logger::info(mensaje: "- ¿Hay escalera?:\t{$this->esEscalera($valores)}");
    Logger::info(mensaje: "- ¿Hay full?:\t\t{$this->esFullHouse($valores)}");
    Logger::info(mensaje: "- ¿Hay póker?:\t\t{$this->esPoker($valores)}");
    Logger::info(mensaje: "- ¿Hay generala?:\t{$this->esGenerala($valores)}");
  }

  /**
   * @param Array<int> $dados
   */
  private function esEscalera(array $valores): string
  {
    $escaleraDelUnoAlCinco =
      $valores[1] === 1 &&
      $valores[2] === 1 &&
      $valores[3] === 1 &&
      $valores[4] === 1 &&
      $valores[5] === 1;
    $escaleraDelDosAlSeis =
      $valores[2] === 1 &&
      $valores[3] === 1 &&
      $valores[4] === 1 &&
      $valores[5] === 1 &&
      $valores[6] === 1;
    $escaleraDelTresAlUno =
      $valores[3] === 1 &&
      $valores[4] === 1 &&
      $valores[5] === 1 &&
      $valores[6] === 1 &&
      $valores[1] === 1;

    return $escaleraDelUnoAlCinco ||
      $escaleraDelDosAlSeis ||
      $escaleraDelTresAlUno
      ? 'sí'
      : 'no';
  }

  /**
   * @param Array<int> $dados
   */
  private function esGenerala(array $valores): string
  {
    foreach ($valores as $valor) {
      if ($valor === 5) {
        return 'sí';
      }
    }

    return 'no';
  }

  /**
   * @param Array<int> $dados
   */
  private function esPoker(array $valores): string
  {
    foreach ($valores as $valor) {
      if ($valor === 4) {
        return 'sí';
      }
    }

    return 'no';
  }

  /**
   * @param Array<int> $dados
   */
  private function esFullHouse(array $valores): string
  {
    $hayDoble = false;
    $hayTripe = false;
    foreach ($valores as $valor) {
      if ($valor === 2) {
        $hayDoble = true;
      }
      if ($valor === 3) {
        $hayTripe = true;
      }
    }

    return $hayDoble && $hayTripe ? 'sí' : 'no';
  }
}
