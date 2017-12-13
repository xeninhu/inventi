<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\User;
use App\Coordination;
use App\Util\SearchObject;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
    * Get a validator for an incoming registration request.
    *
    * @param  array  $data
    * @return \Illuminate\Contracts\Validation\Validator
    */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'coordination' => 'required|integer|exists:coordinations,id'
        ]);
    }

    public function edit($idUser) {
        $user = User::findOrFail($idUser);
        $coordinations = Coordination::all();
        return view('auth.edituser',['user' => $user,'coordinations'=>$coordinations]);
    }

    public function destroy($id, Request $request) {
        if($id==1) {
            $request->session()->flash('error','O usuário de id 1 não pode ser removido.');
            return redirect('/users');
        }
        User::destroy($id);
        $request->session()->flash('from-remove',true);
        return redirect('/users');
    }

    public function index() {
        $users = User::paginate(10);

        return view('auth.index', ['users' => $users]);
    }
    
    public function update(Request $request) {
        $data = $request->all();
        $this->validator($data)->validate();
        $user = User::findOrFail($data["id"]);
       
        $user->fill($request->only(['name','admin']));
       
        $user->coordination()->associate(Coordination::find($data["coordination"]));

        $user->save();
        
        $coordinations = Coordination::all();
        $request->session()->flash('successMessage','Colaborador atualizado com sucesso');
        return redirect("/users/$user->id/edit");

    }

    /**
    *   Lista usuários para administradores e coordenadores. Caso o usuário logado seja coordenador,
    *   retorna apenas usuários da coordenação.
    */
    public function search($name='%') {
        $user_admin = Auth::user();
        $coordination = $user_admin->coordinator; //Pega a coordenação ou false se não for coordenador
        if(!$user_admin->admin && !$user_admin->coordinator)
            return response([
                "message" => "Você precisa ser adminstrador ou coordenador para acessar essa busca"
                ],403);
        if($user_admin->admin)        
            $users = User::where('name','like',"%$name%")->get();
        else
            $users = User::where('name','like',"%$name%")
            ->where('coordination_id',$coordination->id)
            ->get();
        
        $search_object = new SearchObject($users,'name','id');
        return response()->json($search_object);
    }

    public function pageSendItensMessages() {
        if((Auth::user())->admin)  //Administrador pega todas
            $coordinations = Coordination::all();
        elseif(!($coordination = (Auth::user())->coordinator)) //Não coordenador nem admin, proibido.
            return response([
                    "message" => "Você precisa ser adminstrador ou coordenador para acessar essa página"
                ],403);
        else 
            $coordinations = [$coordination];//Coordenador pega apenas pagina de sua coordenação

        return view('users.messages',['coordinations'=>$coordinations]);
    }

}
