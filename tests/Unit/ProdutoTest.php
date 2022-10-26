<?php

namespace Tests\Unit;

use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProdutoTest extends TestCase
{
    use RefreshDatabase;

    public function test_produto_valor_mutator()
    {
        $produto = Produto::factory()->create(['valor' => 1]);
        $this->assertEquals('R$ 00,01', $produto->valor);
        $produto = Produto::factory()->create(['valor' => 12]);
        $this->assertEquals('R$ 00,12', $produto->valor);
        $produto = Produto::factory()->create(['valor' => 123]);
        $this->assertEquals('R$ 01,23', $produto->valor);
        $produto = Produto::factory()->create(['valor' => 1234]);
        $this->assertEquals('R$ 12,34', $produto->valor);
        $produto = Produto::factory()->create(['valor' => 12345]);
        $this->assertEquals('R$ 123,45', $produto->valor);
        $produto = Produto::factory()->create(['valor' => 123456]);
        $this->assertEquals('R$ 1234,56', $produto->valor);
    }
}
