<?php

namespace App\Http\Controllers;

use App\Coordination;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CoordinationController extends Controller
{

    /**
    * Get a validator for an incoming registration request.
    *
    * @param  array  $data
    * @return \Illuminate\Contracts\Validation\Validator
    */
   protected function validator(array $data)
   {
        $rules = ['name' => 'required'];

        /**
         * Só checo coordenação se enviou id(Edição). 
         * Criação não adiciona coordenação
         */
        if(isset($data['id'])) { 
            $rules['coordinator_id'] = [
                'required',
                'integer'
            ];
            
            /**
             * Checa se o coordenador é da mesma coordenação.
             * Caso o coord for 0, deixa passar pois é para coordenação sem coordenador
             */
            
            if($data['coordinator_id']!=0) {
                $rules['coordinator_id'][] = 
                    Rule::exists('users','id')->where(function($query) use($data) {
                        $query->where('coordination_id','=',$data['id']);
                    });
            }
        }

        return Validator::make($data, $rules);
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coordinations = Coordination::paginate(10);
        return view("coordination.index",
            ['coordinations'=>$coordinations]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //TODO filtrar apenas usuários que não são coordenadores
        return view("coordination.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $this->validator($data)->validate();

        $coord = new Coordination();
        $coord->name = $data['name'];
        $coord->save();

        $request->session()->flash('successMessage','Coordenação cadastrada com sucesso');
        
        return redirect("coordinations/$coord->id/edit");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Coordination  $coordination
     * @return \Illuminate\Http\Response
     */
    public function show(Coordination $coordination)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Coordination  $coordination
     * @return \Illuminate\Http\Response
     */
    public function edit(Coordination $coordination)
    {
        return view("coordination.edit",
            [ 
                'coord'=>$coordination,
                'users'=>User::where("coordination_id","=",$coordination->id)->get()
            ]);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Coordination  $coordination
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coordination $coordination)
    {
           $data = $request->all();
           $this->validator($data)->validate();

           $coord = Coordination::find($data['id']);
           $coord->name = $data['name'];
           if( isset($data['coordinator_id']) && $data['coordinator_id']!=0 ) //Id 0 para sem coordenador | validador garante ser apenas numero
                $coord->coordinator_id = $data['coordinator_id'];
            else
                $coord->coordinator_id = null;
           $coord->save();
   
           $request->session()->flash('successMessage','Coordenação alterada com sucesso');
           return redirect("coordinations/$coord->id/edit");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Coordination  $coordination
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coordination $coordination, Request $request)
    {
        Coordination::destroy($coordination->id);
        $request->session()->flash('from-remove',true);
        return redirect('/coordinations');
    }
}
