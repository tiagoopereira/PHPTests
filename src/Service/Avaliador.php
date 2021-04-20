<?php
namespace App\Service;

use App\Model\Lance;
use App\Model\Leilao;

class Avaliador
{
    private float $maiorValor = 0;
    private float $menorValor = INF;
    /** @var Lance[] */
    private array $maioresLances = [];

    public function avalia(Leilao $leilao): void
    {   
        if (empty($leilao->getLances())) {
            throw new \DomainException('Não é possível avaliar um leilão sem lances.');
        }

        if ($leilao->getStatus()) {
            throw new \DomainException('Leilão já finalizado.');
        }

        $lances = $leilao->getLances();

        foreach ($lances as $lance) {
            $valorLance = $lance->getValor();

            if ($valorLance > $this->maiorValor) {
                $this->maiorValor = $valorLance;
            }

            if ($valorLance < $this->menorValor) {
                $this->menorValor = $valorLance;
            }
        }

        usort($lances, function(Lance $lance1, Lance $lance2) {
            return $lance2->getValor() - $lance1->getValor();
        });

        $this->maioresLances = array_slice($lances, 0, 3);
    }

    public function getMaiorValor(): float
    {
        return $this->maiorValor;
    }

    public function getMenorValor(): float
    {
        return $this->menorValor;
    }

    /** @return Lance[] */
    public function getMaioresLances(): array
    {
        return $this->maioresLances;
    }
}