<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\User;
use App\Coordination;
use App\Item;

class MovingTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testHappyPath()
    {
        $userAdmin = User::find(1);
        
        $request = ['item_type'=> 'Tipo teste movimentação',
                    'item' => 'Item de movimentação',
                    'patrimony_number' => 321456781,
                    'coordination' => $userAdmin->coordination->id
                    ];
        $this->actingAs($userAdmin)
             ->post('/itens',$request); //Insiro item pra testes
        
        try {
            $item = Item::where('patrimony_number',321456781)->firstOrFail(); //Pego o item inserido
            $this->assertTrue($item->user===null); //Não deve possuir colaborador
            $request = ['user'=> $userAdmin->id,
                    'itens' => $item->id
                    ];
            $response = $this->actingAs($userAdmin)
                ->put('/itens/move',$request); //Movo o item para o usuário
            
            $response->assertSessionHas('correct_itens'); //Verifica se foi passado array com itens movidos

            $item->refresh();
            $item->load('user'); //Recarrego o item

            $this->assertTrue($item->user->id === $userAdmin->id);//Colaborador deve ter o item que foi movido

            //Apagando tudo criado pra testes
            $type = $item->type;
            $response = $this->actingAs($userAdmin)
                         ->delete('/itens/'.$item->id);
            $type->delete();

        }catch(ModelNotFoundException $e) {
            $this->fail('Item não foi criado no banco');
        }

    }
}
