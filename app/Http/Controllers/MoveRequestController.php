<?php

namespace App\Http\Controllers;

use App\MoveRequest;
use App\Item;
use App\User;
use App\Coordination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class MoveRequestController extends Controller
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
            'itens' => 'required',
            'user_to'  => 'required_without_all:my_coord,other_coord'
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
        $itens = Item::select('id','item','patrimony_number')
            ->where('user_id',Auth::user()->id)
            ->get();
        $users = User::select('id','name')
            ->where('coordination_id',Auth::user()->coordination->id)
            ->where('id','<>',Auth::user()->id)
            ->get();
        $coordinations = Coordination::select('id','name')
            ->where('id','<>',Auth::user()->coordination->id)
            ->get();
        return view('requests.create',['itens'=>$itens,'users'=>$users,'coordinations'=>$coordinations]);
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
        DB::beginTransaction();
        foreach($data["itens"] as $item) {
            $move_request = new MoveRequest();
            $move_request->user_from_id = (Auth::user())->id;
            if(array_key_exists("user_to",$data))
                $move_request->user_to_id = $data["user_to"];
            if(array_key_exists("my_coord",$data)){
                $move_request->coordination_id = (Auth::user())->coordination->id;
            }
            else if(array_key_exists("other_coord",$data)) {
                $move_request->coordination_id = $data["other_coord"];
            }
            $move_request->description = $data["description"];
            $move_request->item_id = $item;
            $move_request->save();
        }
        DB::commit();
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MoveRequest  $moveRequest
     * @return \Illuminate\Http\Response
     */
    public function show(MoveRequest $moveRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MoveRequest  $moveRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(MoveRequest $moveRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MoveRequest  $moveRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MoveRequest $moveRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MoveRequest  $moveRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(MoveRequest $moveRequest)
    {
        //
    }
}
