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

class ItemTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateItemHappyPath()
    {
        $userAdmin = User::find(1);
        $coordinations = Coordination::limit(2)
                                     ->get();
        
        $coordination = $coordinations->shift();


        $request = ['item_type'=> 'Tipo teste',
                    'item' => 'Item de teste',
                    'patrimony_number' => 333111222,
                    'coordination' => $coordination->id
                    ];
        $response = $this->actingAs($userAdmin)
                         ->post('/itens',$request);
        $response->assertStatus(302);//Verifica se foi redirecionado
        try {
            $item = Item::where('patrimony_number','=',333111222)
                        ->firstOrFail();

            $response->assertRedirect('/itens/'.$item->id.'/edit'); //Verificando se o redirecionamento foi feito para a página de edição
            $this->assertTrue($item->type->type==="Tipo teste");
            $this->assertTrue($item->item==="Item de teste");
            $this->assertTrue($item->coordination->id===$coordination->id);

            //Removendo tudo
            $type = $item->type;
            $item->type()->dissociate();
            $item->delete();
            $type->delete();
            


        }catch(ModelNotFoundException $e) {
            $this->fail('Item não foi criado no banco');
        }


        $this->assertTrue(true);
    }
}
