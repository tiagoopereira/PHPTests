<?php
namespace App\Tests\Service;

use App\Model\Lance;
use App\Model\Leilao;
use App\Model\Usuario;
use PHPUnit\Framework\TestCase;
use App\Service\Avaliador;

class AvaliadorTest extends TestCase
{
    private Avaliador $avaliador;

    protected function setUp(): void
    {
        $this->avaliador = new Avaliador();
    }

    private function leilaoEmOrdemCrescente(): Leilao
    {
        // Arrange - Given
        $leilao = new Leilao('Camaro Amarelo');

        $joao = new Usuario('João');
        $maria = new Usuario('Maria');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($maria, 50000));
        $leilao->recebeLance(new Lance($joao, 60000));
        $leilao->recebeLance(new Lance($ana, 75000));
        $leilao->recebeLance(new Lance($joao, 80000));
        $leilao->recebeLance(new Lance($ana, 90000));
        $leilao->recebeLance(new Lance($maria, 100000));

        return $leilao;
    }

    private function leilaoEmOrdemDecrescente(): Leilao
    {
        // Arrange - Given
        $leilao = new Leilao('Camaro Amarelo');

        $joao = new Usuario('João');
        $maria = new Usuario('Maria');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($maria, 100000));
        $leilao->recebeLance(new Lance($ana, 90000));
        $leilao->recebeLance(new Lance($joao, 80000));
        $leilao->recebeLance(new Lance($ana, 75000));
        $leilao->recebeLance(new Lance($joao, 60000));
        $leilao->recebeLance(new Lance($maria, 50000));

        return $leilao;
    }

    private function leilaoEmOrdemAleatoria(): Leilao
    {
        // Arrange - Given
        $leilao = new Leilao('Camaro Amarelo');

        $joao = new Usuario('João');
        $maria = new Usuario('Maria');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($joao, 60000));
        $leilao->recebeLance(new Lance($maria, 100000));
        $leilao->recebeLance(new Lance($ana, 75000));
        $leilao->recebeLance(new Lance($joao, 80000));
        $leilao->recebeLance(new Lance($maria, 50000));
        $leilao->recebeLance(new Lance($ana, 90000));

        return $leilao;
    }

    /** @return Leilao[] */
    public function retornaLeiloes(): array
    {
        return [
            "Ordem Crescente" => [$this->leilaoEmOrdemCrescente()],
            "Ordem Descrescente" => [$this->leilaoEmOrdemDecrescente()],
            "Ordem Aleatória" => [$this->leilaoEmOrdemAleatoria()]
        ];
    }

    /** 
     * @dataProvider retornaLeiloes 
     */
    public function testEncontraMaiorLance(Leilao $leilao): void
    {
        // Act - When
        $this->avaliador->avalia($leilao);
        $maiorValor = $this->avaliador->getMaiorValor();

        // Assert - Then
        self::assertEquals(100000, $maiorValor);
    }

    /** 
     * @dataProvider retornaLeiloes 
     */
    public function testEncontraMenorLance(Leilao $leilao): void
    {
        // Act - When
        $this->avaliador->avalia($leilao);
        $menorValor = $this->avaliador->getMenorValor();

        // Assert - Then
        self::assertEquals(50000, $menorValor);
    }

    /** 
     * @dataProvider retornaLeiloes 
     */
    public function testBusca3MaioresLances(Leilao $leilao): void
    {   
        // Act - When
        $this->avaliador->avalia($leilao);

        $maiores = $this->avaliador->getMaioresLances();

        self::assertCount(3, $maiores);
        self::assertEquals(100000, $maiores[0]->getValor());
        self::assertEquals(90000, $maiores[1]->getValor());
        self::assertEquals(80000, $maiores[2]->getValor());
    }

    public function testLeilaoSemLances(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Não é possível avaliar um leilão sem lances.');
        
        $leilao = new Leilao('Fiat Uno');
        $this->avaliador->avalia($leilao);
    }

    public function testAvaliacaoEmLeilaoFinalizado(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Leilão já finalizado.');

        $leilao = new Leilao('Relâmpago Marquinhos');
        $lucas = new Usuario('Lucas');

        $leilao->recebeLance(new Lance($lucas, 350000));
        $leilao->finalizar();

        $this->avaliador->avalia($leilao);
    }
}