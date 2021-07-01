<?php

namespace Raiz\Modelos\Juegos;

use Brick\DateTime\LocalDateTime;
use Raiz\Auxiliadores\FechaHora;
use Raiz\Modelos\Cubilete;
use Raiz\Modelos\Juegos\Errores\JuegoError;
use Raiz\Modelos\Jugador;
use Raiz\Modelos\ModeloBase;

abstract class JuegoAbstracto extends ModeloBase
{
  public const SIN_INICIAR = 'sin_iniciar';
  public const EN_PROGRESO = 'en_progreso';
  public const EN_PAUSA = 'en_pausa';
  public const COMPLETADO = 'completado';

  private string $estado;
  /** @param Array<Jugador> $jugadores */
  private array $jugadores;
  private Cubilete $cubilete;

  private int $cantidadDeDados;
  private int $dadoValorMin;
  private int $dadoValorMax;

  private LocalDateTime $inicio;
  private ?LocalDateTime $fin;

  /** @param Array<Jugador> $jugadores */
  public function __construct(
    ?string $id,
    ?string $estado,
    ?string $inicio,
    ?string $fin,
    int $cantidadDeDados,
    int $dadoValorMin,
    int $dadoValorMax,
    array $jugadores
  ) {
    parent::__construct($id);

    $this->validarEstado($estado, $id);
    $this->validarFechas($inicio, $fin, $estado, $id);

    $this->cantidadDeDados = $cantidadDeDados;
    $this->dadoValorMin = $dadoValorMin;
    $this->dadoValorMax = $dadoValorMax;

    $this->inicio = FechaHora::deserializar($inicio);
    $this->fin = FechaHora::deserializarOpcional($fin);

    if ($dadoValorMin < 0 || $dadoValorMax <= $dadoValorMin) {
      throw new JuegoError(
        juego: $this,
        codigo: JuegoError::VALORES_DE_DADOS_INVALIDOS,
      );
    }

    $this->cubilete = new Cubilete(juego: $this);
    $this->jugadores = $jugadores;

    $this->estado = is_null($estado) ? self::SIN_INICIAR : $estado;
  }

  final public function cantidadDeDados(): int
  {
    return $this->cantidadDeDados;
  }
  final public function dadoValorMin(): int
  {
    return $this->dadoValorMin;
  }
  final public function dadoValorMax(): int
  {
    return $this->dadoValorMax;
  }
  final public function estado(): string
  {
    return $this->estado;
  }

  public function serializar(): array
  {
    return [
      'id' => $this->id(),
      'estado' => $this->estado,
      'inicio' => FechaHora::serializar($this->inicio),
      'fin' => FechaHora::serializar($this->fin),
      'jugadores' => array_map(
        fn($jugador) => $jugador->serializar(),
        $this->jugadores,
      ),
    ];
  }

  public function iniciar(): void
  {
    if ($this->estado !== self::SIN_INICIAR) {
      throw new JuegoError(juego: $this, codigo: JuegoError::YA_ESTA_INICIADO);
    }
    $this->estado = self::EN_PROGRESO;
  }

  public function resetear(): void
  {
    if ($this->estado === self::SIN_INICIAR) {
      throw new JuegoError(
        juego: $this,
        codigo: JuegoError::RESETEAR_SIN_INICIAR,
      );
    }
    $this->estado = self::SIN_INICIAR;
  }

  public function pausar(): void
  {
    if ($this->estado === self::EN_PAUSA) {
      throw new JuegoError(juego: $this, codigo: JuegoError::YA_ESTA_PAUSADO);
    }
    $this->estado = self::EN_PAUSA;
  }

  public function reanudar(): void
  {
    if ($this->estado !== self::EN_PAUSA) {
      throw new JuegoError(
        juego: $this,
        codigo: JuegoError::REANUDAR_SIN_ESTAR_EN_PAUSA,
      );
    }
    $this->estado = self::EN_PROGRESO;
  }

  public function realizarRonda(): void
  {
    if ($this->estado !== self::EN_PROGRESO) {
      throw new JuegoError(
        juego: $this,
        codigo: JuegoError::RONDA_SIN_ESTAR_EN_PROGRESO,
      );
    }
    $this->procesarRonda();

    if ($this->verificarSiSeCompleto()) {
      $this->estado = self::COMPLETADO;
    }
  }

  abstract public function verResultado(): void;
  abstract protected function procesarRonda(): void;
  abstract protected function verificarSiSeCompleto(): bool;

  final protected function cubilete(): Cubilete
  {
    return $this->cubilete;
  }

  /**
   * @return Array<Jugador>
   */
  final protected function jugadores(): array
  {
    return $this->jugadores;
  }

  private function validarEstado(?string $estado, ?string $id): void
  {
    $estadoEsNulo = is_null($estado);

    if (is_null($id) && !$estadoEsNulo) {
      throw new JuegoError(
        juego: $this,
        codigo: JuegoError::ESTADO_CARGADO_EN_CREACION,
      );
    }
    if ($estadoEsNulo || !is_null($id)) {
      return;
    }
    if ($estado === self::EN_PROGRESO) {
      throw new JuegoError(
        juego: $this,
        codigo: JuegoError::ESTADO_CARGADO_EN_CREACION,
      );
    }
  }

  private function validarFechas(
    ?string $inicio,
    ?string $fin,
    ?string $estado,
    ?string $id
  ): void {
    $inicioEsNulo = is_null($inicio);
    $finEsNulo = is_null($fin);
    $idEsNulo = is_null($id);

    if ($idEsNulo && (!$inicioEsNulo || !$finEsNulo)) {
      throw new JuegoError(
        juego: $this,
        codigo: JuegoError::CONFIGURACION_FECHAS,
      );
    }
    if ($idEsNulo) {
      return;
    }
    if (
      !$finEsNulo &&
      ($estado === self::SIN_INICIAR || $estado === self::EN_PAUSA)
    ) {
      throw new JuegoError(
        juego: $this,
        codigo: JuegoError::CONFIGURACION_FECHAS,
      );
    }
    if ($finEsNulo && $estado === self::COMPLETADO) {
      throw new JuegoError(
        juego: $this,
        codigo: JuegoError::CONFIGURACION_FECHAS,
      );
    }
  }
}
