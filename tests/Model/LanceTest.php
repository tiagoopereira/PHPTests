<?php
namespace App\Tests\Model;

use App\Model\Lance;
use App\Model\Usuario;
use PHPUnit\Framework\TestCase;

class LanceTest extends TestCase
{
    public function testLanceComValorNegativo(): void
    {   
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('O valor de lance deve ser positivo.');

        $usuario = new Usuario('João');
        new Lance($usuario, -1500);
    }
}