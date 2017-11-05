<?php

namespace App\Http\Controllers;

use App\MoveRequest;
use App\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MoveRequestController extends Controller
{
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
        $itens = Item::where('user_id',Auth::user()->id)
                    ->get();
        return view('requests.create',['itens'=>$itens]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
