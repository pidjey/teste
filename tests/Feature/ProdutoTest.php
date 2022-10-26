<?php

namespace Tests\Feature;

use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProdutoTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_see_produtos()
    {
        $produto = Produto::factory()->create();
        $response = $this->get('/api/produto');
        $response->assertJsonStructure([ 
            '*' => [
                'id',
                'nome',
                'valor',
                'loja_id',
                'ativo',
            ]
        ]);
    }

    public function test_user_can_see_produto()
    {
        $produto = Produto::factory()->create();
        $response = $this->get('/api/produto/' . $produto->id);
        $response->assertJsonStructure([
            'id',
            'nome',
            'valor',
            'loja_id',
            'ativo'
        ]);
    }

    public function test_user_can_save_produto()
    {
        $produto = Produto::factory()->make(['valor' => 12]);
        $produto = $produto->toArray();
        $produto['valor'] = 12;
        $response = $this->post('/api/produto/', $produto);
        $this->assertEquals(1,Produto::all()->count());
    }

    public function test_error_when_saving_invalid_produto_input()
    {
        $produto = Produto::factory()->make(['nome' => null]);
        $response = $this->post('/api/produto/', $produto->toArray());
        $response->assertStatus(400);
        $produto = Produto::factory()->make(['nome' => str_repeat('a', 61)]);
        $response = $this->post('/api/produto/', $produto->toArray());
        $response->assertStatus(400);
        $produto = Produto::factory()->make(['nome' => 'ab']);
        $response = $this->post('/api/produto/', $produto->toArray());
        $response->assertStatus(400);

        $produto = Produto::factory()->make(['valor' => null]);
        $response = $this->post('/api/produto/', $produto->toArray());
        $response->assertStatus(400);
        $produto = Produto::factory()->make(['valor' => '1234567']);
        $response = $this->post('/api/produto/', $produto->toArray());
        $response->assertStatus(400);
        $produto = Produto::factory()->make(['valor' => '1']);
        $response = $this->post('/api/produto/', $produto->toArray());
        $response->assertStatus(400);

        $produto = Produto::factory()->make(['ativo' => null]);
        $response = $this->post('/api/produto/', $produto->toArray());
        $response->assertStatus(400);
    }

    public function test_user_can_update_produto()
    {
        $produto = Produto::factory()->create();
        $new = "abcd";
        $response = $this->put('/api/produto/'.$produto->id, [ "nome" => $new ]);
        $this->assertDatabaseHas('produtos', ['id' => $produto->id, 'nome' => $new]);

        $new = 12;
        $new_mask = "R$ 00,12";
        $response = $this->put('/api/produto/'.$produto->id, [ "valor" => $new ]);
        $this->assertDatabaseHas('produtos', ['id' => $produto->id, 'valor' => $new]);

        $new = (Produto::factory()->make())->ativo;
        $response = $this->put('/api/produto/'.$produto->id, [ "ativo" => $new ]);
        $this->assertDatabaseHas('produtos', ['id' => $produto->id, 'ativo' => $new]);
    }

    public function test_error_when_updating_invalid_produto_input()
    {
        $produto = Produto::factory()->create();
        $response = $this->put('/api/produto/'.$produto->id, [ "nome" => null ]);
        $response->assertStatus(400);
        $response = $this->put('/api/produto/'.$produto->id, [ "nome" => "z" ]);
        $response->assertStatus(400);
        $response = $this->put('/api/produto/'.$produto->id, [ "nome" => str_repeat('a', 61) ]);
        $response->assertStatus(400);

        $response = $this->put('/api/produto/'.$produto->id, [ "valor" => null ]);
        $response->assertStatus(400);
        $response = $this->put('/api/produto/'.$produto->id, [ "valor" => 1 ]);
        $response->assertStatus(400);
        $response = $this->put('/api/produto/'.$produto->id, [ "valor" => 1234567 ]);
        $response->assertStatus(400);

        $response = $this->put('/api/produto/'.$produto->id, [ "ativo" => null ]);
        $response->assertStatus(400);
    }

    public function test_user_can_delete_produto()
    {
        $produto = Produto::factory()->create();
        $response = $this->delete('/api/produto/'.$produto->id);
        $this->assertDatabaseMissing('produtos',['id'=> $produto->id]);
    }
}
