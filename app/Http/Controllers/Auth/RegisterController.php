<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Coordination;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Mail\CadastroColaborador;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

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
            'email' => 'required|string|email|max:80|unique:users',
            'coordination' => 'required|integer|exists:coordinations,id'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function store(Request $request)
    {
        $data = $request->all();
        
        $this->validator($data)->validate();
        
        $pass = str_random(10);

        $newUser = new User();
        $newUser->fill($data);
        $newUser->password = bcrypt($pass);
        $newUser->coordination()->associate(Coordination::find($data["coordination"]));
        $newUser->save();

        /*$novoUsuario = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'coordination' => $data["coordination"],
            'password' => bcrypt($pass)
            
        ]);*/

        Mail::to($data['email'])->send(new CadastroColaborador($pass));
        $request->session()->flash('successMessage','Colaborador criado com sucesso');
        return redirect("/users/$newUser->id/edit");
    }

    public function create() {
        $coordinations = Coordination::all();
        return view('auth.register',['coordinations'=>$coordinations]);
    }
}
