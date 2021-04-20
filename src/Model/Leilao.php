<?php
namespace App\Model;

class Leilao
{
    /** @var Lance[] */
    private array $lances;
    private bool $finalizado;

    public function __construct(
        private string $descricao
    )
    {
        $this->lances = [];
        $this->finalizado = false;
    }

    public function recebeLance(Lance $lance): void
    {
        if ($this->getStatus()) {
            throw new \DomainException('Leilão já finalizado.');
        }

        if (!empty($this->lances) && $this->verificaLanceRepetido($lance)) {
            throw new \DomainException('Usuário não pode propor 2 lances seguidos.');
        }

        $totalLancesPorUsuario = $this->verificaLancesPorUsuario($lance->getUsuario());

        if ($totalLancesPorUsuario >= 5) {
            throw new \DomainException('Usuário não pode fazer mais de 5 lances em um mesmo Leilão.');
        }

        $this->lances[] = $lance;
    }

    /** @return Lance[] */
    public function getLances(): array
    {
        return $this->lances;
    }

    public function finalizar(): void
    {
        if ($this->getStatus()) {
            throw new \DomainException('Leilão já finalizado.');
        }

        $this->finalizado = true;
    }

    public function getStatus(): bool
    {
        return $this->finalizado;
    }

    private function verificaLanceRepetido(Lance $lance): bool
    {
        $ultimoLance = $this->lances[array_key_last($this->lances)];
        return $lance->getUsuario() == $ultimoLance->getUsuario();
    }

    private function verificaLancesPorUsuario(Usuario $usuario): int
    {
        $totalLancesPorUsuario = array_reduce(
            $this->lances, 
            function (int $totalAcumulado, Lance $lanceAtual) use ($usuario) {
                if ($lanceAtual->getUsuario() == $usuario) {
                    return $totalAcumulado + 1;
                }

                return $totalAcumulado;
            }, 
            0
        );

        return $totalLancesPorUsuario;
    }
}