<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\User;
use App\Coordination;

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
       
        $user->fill($request->only(['name']));
       
        $user->coordination()->associate(Coordination::find($data["coordination"]));

        $user->save();
        
        $coordinations = Coordination::all();
        return view('auth.edituser',['user' => $user,'coordinations'=>$coordinations,'successMessage'=>'Usuário atualizado com sucesso']);
    }
}
