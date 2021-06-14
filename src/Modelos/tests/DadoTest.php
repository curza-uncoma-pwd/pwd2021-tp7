<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use Raiz\Modelos\Dado;
use Raiz\Modelos\DadoEstadoError;
use Raiz\Utilidades\Logger;

final class DadoTest extends TestCase
{
  /** @test */
  public function se_crea_en_estado_inicial(): void
  {
    $dado = new Dado(valorMinimo: 1, valorMaximo: 6);

    $this->assertEquals(Dado::INICIAL, $dado->estado());
  }

  /** @test */
  public function el_proceso_normal_funciona_correctamente(): void
  {
    $dado = new Dado(valorMinimo: 1, valorMaximo: 6);

    $dado->rodar();
    $dado->reposar();

    $valor = $dado->caraVisible();

    $this->assertIsInt(actual: $valor);

    Logger::info(modelo: $dado, mensaje: "Obtuvo el valor {$valor}");
  }

  /** @test */
  public function error_al_leer_valor_mientras_rueda(): void
  {
    $this->expectException(DadoEstadoError::class);
    $this->expectExceptionCode(DadoEstadoError::INTENTAR_LEER_MIENTRAS_RUEDA);

    $dado = new Dado(valorMinimo: 1, valorMaximo: 6);

    $dado->rodar();
    $dado->caraVisible();
  }

  /** @test */
  public function error_al_reposar_mientras_esta_en_reposo(): void
  {
    $this->expectException(DadoEstadoError::class);
    $this->expectExceptionCode(DadoEstadoError::INTENTAR_REPOSAR_EN_REPOSO);

    $dado = new Dado(valorMinimo: 1, valorMaximo: 6);

    $dado->reposar();
    $dado->reposar();
  }
}
