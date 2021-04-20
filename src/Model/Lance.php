<?php
namespace App\Model;

class Lance
{
    public function __construct(
        private Usuario $usuario, 
        private float $valor
    )
    {
        if ($this->valor < 0) {
            throw new \DomainException('O valor de lance deve ser positivo.');
        }
    }

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function getValor(): float
    {
        return $this->valor;
    }
}
