<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Coordination;
use App\Item;
use App\ItemType;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ItensController extends Controller
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
            'item_type' => 'required|string|max:255',
            'item' => 'required|string|max:255',
            'patrimony_number' => 'required|integer|unique:itens',
            'coordination' => 'required|integer|exists:coordinations,id'
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
        $coordinations = Coordination::all();
        return view('itens/create',['coordinations'=>$coordinations]);
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

        $item = new Item();
        $item->fill($request->only(['patrimony_number','item']));
        $item->coordination()->associate(Coordination::find($data["coordination"]));
        
        //Verifica se o tipo já existe, senão existe insere.
        try {
            $type = ItemType::where("type","=",$data["item_type"])->firstOrFail();
        }catch(ModelNotFoundException $e) {
            $type = new ItemType();
            $type->type = $data["item_type"];
            $type->save();
        }

        $item->type()->associate($type);

        $item->save();

        return redirect('itens/create');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
