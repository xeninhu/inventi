<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\User;
use App\Coordination;
use App\Mail\CadastroColaborador;
use Illuminate\Support\Facades\Mail;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateUserHappyPath() {   

        Mail::fake(); //Iniciando o mail fake

        //Testando e-mail required
        $userAdmin = User::find(1);
        $coordinations = Coordination::limit(2)
                                     ->get();
        
        $coordination = $coordinations->shift();

        $request = ['name'=> 'TestCase',
                    'email' => 'testeautomatico@testando.com.br',
                    'coordination' => $coordination->id
                    ];
        $response = $this->actingAs($userAdmin)
                         ->post('/register',$request);
        $response->assertStatus(302);//Verifica se foi redirecionado para página de alterar
        try {
            $user = User::where('email','testeautomatico@testando.com.br')
                        ->firstOrFail();//Verifica se cadastrou
            
            $response->assertRedirect('/user/'.$user->id.'/edit');

            $coordination = $coordinations->shift();
            $request = [ 'id' => $user->id,
                    'name'=> 'TestCase2',
                    'coordination' => $coordination->id
                    ];
            $response = $this->actingAs($userAdmin)
                         ->put('/user',$request); 
            $user->refresh();

            $response->assertSuccessful();
            $this->assertTrue($user->name==="TestCase2");
            $this->assertTrue($user->coordination->id===$coordination->id);

            $user->delete();
        }catch(ModelNotFoundException $e) {
            $this->fail('Usuário não foi criado no banco');
        }

        Mail::assertSent(CadastroColaborador::class, function ($mail) {//Verificando envio de email
            return $mail->hasTo("testeautomatico@testando.com.br");
        });

    }

    public function testCreateUserWrongInputData() {
        $userAdmin = User::find(1);
        $coordination = Coordination::all()
                                    ->first();
        $request = [
                    'email' => 'emailforadeformato',
                    ]; //email fora de formato, sem nome nem coordenação
        $response = $this->actingAs($userAdmin)
                         ->post('/register',$request);
        $response->assertSessionHasErrors(["name","email","coordination"]);


        $request = ['name'=> 'TestCase',
                    'coordination' => $coordination->id
                    ]; //Sem email
        $response = $this->actingAs($userAdmin)
                         ->post('/register',$request);
        //$response->assertRedirect('/register');//Verifica se foi redirecionado para página de cadastro
        $response->assertSessionHasErrors(["email"]);

        $request = ['name'=> 'TestCase',
                    'email' => 'testeautomatico@testando.com.br',
                    'coordination' => 'texto'
                    ];//Coordenação precisa ser um id
        $response = $this->actingAs($userAdmin)
                         ->post('/register',$request);
  //      $response->assertRedirect('/register');//Verifica se foi redirecionado para página de cadastro
        $response->assertSessionHasErrors(["coordination"]);

        $request = ['name'=> 'TestCase',
                    'email' => 'testeautomatico@testando.com.br',
                    'coordination' => -1
                    ];//Coordenação com id inválido
        $response = $this->actingAs($userAdmin)
                         ->post('/register',$request);
  //      $response->assertRedirect('/register');//Verifica se foi redirecionado para página de cadastro
        $response->assertSessionHasErrors(["coordination"]);
    }

    public function testEditUserWrongData() {
        $userAdmin = User::find(1);
        $coordination = Coordination::all()
                                    ->first();
        $request = ['name'=> 'TestCase',
                    'email' => 'testeautomatico2@testando.com.br',
                    'coordination' => $coordination->id
                    ];
        $this->actingAs($userAdmin)
             ->post('/register',$request); //Criando usuário para edição
            
         try {
            $user = User::where('email','testeautomatico2@testando.com.br')
                        ->firstOrFail();//Verifica se cadastrou

            $request = ['id' => $user->id,
                        ];//sem nome nem coordenação
            $response = $this->actingAs($userAdmin)
                            ->put('/user',$request);
            $response->assertSessionHasErrors(["name","coordination"]);

            $request = ['id' => $user->id,
                        'name'=> 'TestCase',
                        'coordination' => 'texto'
                        ];//Coordenação precisa ser um id
            $response = $this->actingAs($userAdmin)
                            ->put('/user',$request);

            $response->assertSessionHasErrors(["coordination"]);

            $request = ['id' => $user->id,
                        'name'=> 'TestCase',
                        'email' => 'testeautomatico@testando.com.br',
                        'coordination' => -1
                        ];//Coordenação com id inválido
            
            $response = $this->actingAs($userAdmin)
                            ->put('/user',$request);

            $response->assertSessionHasErrors(["coordination"]);
            $request = ['id' => $user->id,
                        'name'=> 'TestCase2',
                        'email' => 'testemudandoemail@testando.com.br',
                        'coordination' => $user->coordination->id
                        ];//Mudando e-mail
            
            $response = $this->actingAs($userAdmin)
                            ->put('/user',$request);
            
            $user->refresh();
            
            $this->assertTrue($user->email==='testeautomatico2@testando.com.br');//O e-mail não deve jamais ser alterado.
            $this->assertTrue($user->name==='TestCase2');//as outras informações devem ser alteradas

            $user->delete();
         }catch(ModelNotFoundException $e) {
            $this->fail('Usuário não foi criado no banco');
        }
    }

}
