<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Item;
use App\User;
use App\MoveRequest;
use App\Coordination;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MoveRequestTest extends TestCase
{

    use DatabaseTransactions;

    
    private function startDatabase($coordination = false,$user = false) {
        $itens = factory(Item::class, 2)
            ->create()
            ->each(function ($u) use ($user) {
                if(!$user)
                    $u->user()->associate(factory(User::class)->make());
                else
                    $u->user()->associate($user);
                
                $u->save();
            });

         $users =  factory(User::class,1)
            ->create()
            ->each(function ($u) use ($coordination) {
                if($coordination) {
                    $u->coordination()->associate($coordination);
                    $u->save();
                }
            });
        
         return [
            "itens" => $itens,
            "users" => $users,
         ];
    }
    
    public function testErrorRequestingItemFromOtherUser() {
        $userAdmin = User::find(1);
        $models = $this->startDatabase($userAdmin->coordination);

        $itens = $models["itens"];
        $users = $models["users"];

        $request = [
            'itens' => [
                $itens[0]->id,
                $itens[1]->id
            ],
            'user_to' => $users[0]->id,
            'description' => 'testando'
        ];
        
        $response = $this->actingAs($userAdmin)
            ->post('/move-requests',$request); //Insiro solicitação pra testes
        
        $response->assertSessionHasErrors(["forbidden_itens"]); //Verifica se na sessão o erro foi adicionado
        $response->assertRedirect("/move-requests/create");//Verifica se foi redirecionado

    }

    public function testErrorRequestingMovingToUserFromOtherCoord() {
        $userAdmin = User::find(1);
        $models = $this->startDatabase(false,$userAdmin);

        $itens = $models["itens"];
        $users = $models["users"];

        $request = [
            'itens' => [
                $itens[0]->id,
                $itens[1]->id
            ],
            'user_to' => $users[0]->id,
            'description' => 'testando'
        ];

        $response = $this->actingAs($userAdmin)
            ->post('/move-requests',$request); //Insiro solicitação pra testes
        
        $response->assertSessionHasErrors(["user_from_other_coord"]); //Verifica se na sessão o erro foi adicionado
        $response->assertRedirect("/move-requests/create");//Verifica se foi redirecionado

    }

    public function testCreateMoveRequestToUser()
    {
        $userAdmin = User::find(1);

        $models = $this->startDatabase($userAdmin->coordination,$userAdmin);
        $itens = $models["itens"];
        $users = $models["users"];
        $request = [
            'itens' => [
                $itens[0]->id,
                $itens[1]->id
            ],
            'user_to' => $users[0]->id,
            'description' => 'testando'
        ];

        $response = $this->actingAs($userAdmin)
            ->post('/move-requests',$request); //Insiro solicitação pra testes

        try {
            $move_request = MoveRequest::where('user_to_id','=',$users[0]->id)
                ->where('description','=','testando')
                ->where('item_id','=',$itens[0]->id)
                ->firstOrFail(); //Pego o move_request inserido
            $move_request = MoveRequest::where('user_to_id','=',$users[0]->id)
                ->where('description','=','testando')
                ->where('item_id','=',$itens[1]->id)
                ->firstOrFail(); //Pego o move_request inserido
            
            $response->assertStatus(302);//Verifica se foi redirecionado
            $response->assertSessionHas('success'); //Verifica se enviou sucesso para o front-end

        }catch(ModelNotFoundException $e) {
            $this->fail('Um ou mais solicitações não foram criadas no banco');
        }
    }
}
