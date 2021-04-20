<?php
namespace App\Tests\Model;

use App\Model\Lance;
use App\Model\Leilao;
use App\Model\Usuario;
use PHPUnit\Framework\TestCase;

class LeilaoTest extends TestCase
{   
    public function gerarLances(): array
    {
        $joao = new Usuario("João");
        $marcos = new Usuario("Marcos");

        $leilaoCom2Lances = new Leilao('Fiat 147');
        $leilaoCom2Lances->recebeLance(new Lance($joao, 5000));
        $leilaoCom2Lances->recebeLance(new Lance($marcos, 6000));

        $leilaoCom1Lance = new Leilao('Fusca 1972');
        $leilaoCom1Lance->recebeLance(new Lance($marcos, 7000));

        return [
            "2 Lances" => [2, $leilaoCom2Lances, [5000, 6000]],
            "1 Lance" => [1, $leilaoCom1Lance, [7000]]
        ];
    }

    /**
     * @dataProvider gerarLances
     */
    public function testRecebeLances(int $qtdLances, Leilao $leilao, array $lances): void
    {
        self::assertCount($qtdLances, $leilao->getLances());

        foreach ($lances as $i => $lance) {
            self::assertEquals($lance, $leilao->getLances()[$i]->getValor());
        }
    }

    public function testLancesRepetidos(): void
    {   
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Usuário não pode propor 2 lances seguidos.');

        $leilao = new Leilao('Kombi 1976');

        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($ana, 15000));

        // Segundo lance seguido do mesmo usuário
        $leilao->recebeLance(new Lance($ana, 15500));
    }

    public function testLimiteDeLancesPorUsuario(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Usuário não pode fazer mais de 5 lances em um mesmo Leilão.');

        $leilao = new Leilao('Brasília Amarela');

        $joao = new Usuario('João');
        $carla = new Usuario('Carla');

        $leilao->recebeLance(new Lance($joao, 4000));
        $leilao->recebeLance(new Lance($carla, 4500));
        $leilao->recebeLance(new Lance($joao, 5000));
        $leilao->recebeLance(new Lance($carla, 5200));
        $leilao->recebeLance(new Lance($joao, 5300));
        $leilao->recebeLance(new Lance($carla, 5500));
        $leilao->recebeLance(new Lance($joao, 5900));
        $leilao->recebeLance(new Lance($carla, 6000));
        $leilao->recebeLance(new Lance($joao, 6100));
        $leilao->recebeLance(new Lance($carla, 6500));

        // Lance além do permitido por usuário
        $leilao->recebeLance(new Lance($joao, 7000));
    }

    public function testFinalizarLeilaoFinalizado(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Leilão já finalizado.');

        $leilao = new Leilao('Monitor 27"');
        $usuario = new Usuario('Tiago');

        $leilao->recebeLance(new Lance($usuario, 1500));
        $leilao->finalizar();

        // Finalização repetida
        $leilao->finalizar();
    }

    public function testLanceEmLeilaoFinalizado(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Leilão já finalizado.');

        $leilao = new Leilao('Relâmpago Marquinhos');
        $lucas = new Usuario('Lucas');

        $leilao->recebeLance(new Lance($lucas, 350000));
        $leilao->finalizar();

        // Lance pós leilão finalizado
        $leilao->recebeLance(new Lance($lucas, 15000));
    }
}