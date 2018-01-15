<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use App\Coordination;
use App\Jobs\SendUsersItens;

class SendUsersListTest extends TestCase
{    
    use DatabaseTransactions;
    
    public function testHappyPath() {
        $userAdmin = User::find(1);
        $coordination = factory(Coordination::class)
                            ->create();
        $request = [
            "coordinations" => [
                $coordination->id,$userAdmin->coordination->id
                ]
            ];
        
        $response = $this->actingAs($userAdmin)
            ->post('/users/send-itens-message',$request); //Solicito o envio de email para a coordenação do admin e mais uma nova criada
        
        $this->expectsJobs(SendUsersItens::class);
        $response->assertSessionHas('success');//Verifica se enviou com sucesso
        $response->assertRedirect("/users/send-itens-message");//Verifica se foi redirecionado
    }

    public function testOnlyAdminOrCoordinatorHasAccess() {
        $user =  factory(User::class)
            ->create();
        $request = [
            "coordinations" => [ $user->coordination->id ]
            ];

        $response = $this->actingAs($user)
            ->post('/users/send-itens-message',$request); //Solicito o envio de email para a coordenação do usuário

        $response->assertStatus(403);//Verifica se foi redirecionado com mensagem de não permitido

        $user->coordination->coordinator()->associate($user);
        $user->coordination->save();//Setando o usuário como coordenador da coordenação dele.

        $new_coord = factory(Coordination::class)->create();

        $response = $this->actingAs($user)
            ->post('/users/send-itens-message',$request); //Solicito o envio de email para coordenação com outro coordenador

        $this->expectsJobs(SendUsersItens::class);
        $response->assertSessionHas('success');//Verifica se enviou com sucesso, pois agora ele é coordenador
        $response->assertRedirect("/users/send-itens-message");//Verifica se foi redirecionado

        $request = [
            "coordinations" => [ $new_coord->id ]
            ];
        
        $response = $this->actingAs($user)
            ->post('/users/send-itens-message',$request); //Solicito o envio de email para coordenação com outro coordenador

        $response->assertStatus(403);//Verifica se foi redirecionado com mensagem de não permitido
    }

    
}
