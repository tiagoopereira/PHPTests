<?php
namespace App\Model;

class Usuario
{
    public function __construct(
        private string $nome
    )
    {
        $this->nome = $nome;
    }

    public function getNome(): string
    {
        return $this->nome;
    }
}
