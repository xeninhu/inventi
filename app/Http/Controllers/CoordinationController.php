<?php

namespace App\Http\Controllers;

use App\Coordination;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
       return Validator::make($data, [
           'name' => 'required',
           'coordinator' => 'required|integer'
       ]);
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //TODO filtrar apenas usuários que não são coordenadores
        return view("coordination.create",
            ['users'=>User::all()]);
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
        if($data['coordinator_id']!=0) //Id para sem coordenador | validador garante ser apenas numero
            $coord->coordinator_id = $data['coordinator_id'];
        $coord->save();

        $request->session()->flash('successMessage','Item cadastrado com sucesso');
        
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Coordination  $coordination
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coordination $coordination)
    {
        //
    }
}
