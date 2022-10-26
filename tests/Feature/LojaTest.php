<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Loja;

class LojaTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_see_lojas()
    {
        $loja = Loja::factory()->create();
        $response = $this->get('/api/loja');
        $response->assertJsonStructure([ 
            '*' => [
                'id',
                'nome',
                'email'
            ]
        ]);
    }

    public function test_user_can_see_loja()
    {
        $loja = Loja::factory()->create();
        $response = $this->get('/api/loja/' . $loja->id);
        $response->assertJsonStructure([
            'id',
            'nome', 
            'email',
            'produtos' => [
                '*' => [
                    'nome',
                    'valor',
                    'loja_id',
                    'ativo'
                ]
            ]
        ]);
    }

    public function test_user_can_save_loja()
    {
        $loja = Loja::factory()->make();
        $response = $this->post('/api/loja/', $loja->toArray());
        $this->assertEquals(1,Loja::all()->count());
    }

    public function test_error_when_saving_invalid_loja_input()
    {
        $loja = Loja::factory()->make(['nome' => null]);
        $response = $this->post('/api/loja/', $loja->toArray());
        $response->assertStatus(400);
        $loja = Loja::factory()->make(['nome' => 'abcdeabcdeabcdeabcdeabcdeabcdeabcdeabcdea']);
        $response = $this->post('/api/loja/', $loja->toArray());
        $response->assertStatus(400);
        $loja = Loja::factory()->make(['nome' => 'ab']);
        $response = $this->post('/api/loja/', $loja->toArray());
        $response->assertStatus(400);

        $loja = Loja::factory()->make(['email' => null]);
        $response = $this->post('/api/loja/', $loja->toArray());
        $response->assertStatus(400);
        $loja = Loja::factory()->make(['email' => 'invalid_email']);
        $response = $this->post('/api/loja/', $loja->toArray());
        $response->assertStatus(400);
    }

    public function test_error_when_saving_loja_same_email()
    {
        $loja = Loja::factory()->make();
        $loja2 = Loja::factory()->make(["email" => $loja->email]);
        $response = $this->post('/api/loja/', $loja->toArray());
        $response->assertStatus(200);
        $response = $this->post('/api/loja/', $loja2->toArray());
        $response->assertStatus(400);
    }

    public function test_user_can_update_loja()
    {
        $loja = Loja::factory()->create();
        $new = "abcd";
        $response = $this->put('/api/loja/'.$loja->id, [ "nome" => $new ]);
        $this->assertDatabaseHas('lojas', ['id' => $loja->id, 'nome' => $new]);

        $new = (Loja::factory()->make())->email;
        $response = $this->put('/api/loja/'.$loja->id, [ "email" => $new ]);
        $this->assertDatabaseHas('lojas', ['id' => $loja->id, 'email' => $new]);
    }

    public function test_error_when_updating_invalid_loja_input()
    {
        $loja = Loja::factory()->create();
        $response = $this->put('/api/loja/'.$loja->id, [ "nome" => null ]);
        $response->assertStatus(400);
        $response = $this->put('/api/loja/'.$loja->id, [ "nome" => "z" ]);
        $response->assertStatus(400);
        $response = $this->put('/api/loja/'.$loja->id, [ "nome" => "abcdeabcdeabcdeabcdeabcdeabcdeabcdeabcdeaz" ]);
        $response->assertStatus(400);

        $response = $this->put('/api/loja/'.$loja->id, [ "email" => null ]);
        $response->assertStatus(400);
        $response = $this->put('/api/loja/'.$loja->id, [ "email" => "invalid_email" ]);
        $response->assertStatus(400);
    }

    public function test_error_when_updating_loja_same_email()
    {
        $loja = Loja::factory()->create();
        $loja2 = Loja::factory()->create();
        $response = $this->put('/api/loja/'.$loja->id, [ "email" => $loja2->email ]);
        $response->assertStatus(400);
    }

    public function test_user_can_delete_loja()
    {
        $loja = Loja::factory()->create();
        $response = $this->delete('/api/loja/'.$loja->id);
        $this->assertDatabaseMissing('lojas',['id'=> $loja->id]);
    }
    
}
