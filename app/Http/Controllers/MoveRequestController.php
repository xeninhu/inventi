<?php

namespace App\Http\Controllers;

use App\MoveRequest;
use App\Item;
use App\User;
use App\Coordination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            'user'  => 'required_without_all:my_coord,other_coord'
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
        $this->validator($request->all())->validate();
        return $request->all();
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
