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
        //Pego apenas itens que não tem solicitações, ou que tenha apenas solicitações resolvidas.
        $itens = Item::select('itens.id','itens.item','itens.patrimony_number')
            ->leftJoin('move_requests',function($join) {
                $join->on("itens.id","=",'move_requests.item_id')
                    ->where("move_requests.resolved","=","0");
            })
            ->where('user_id',Auth::user()->id)
            ->whereNull('move_requests.id')
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

    private function throwError($errors) {
        DB::rollback();
        return redirect('move-requests/create')
            ->withErrors(
                $errors
            )
            ->withInput();
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

        //Verifico se os itens são do usuário logado
        $itens_from_user = Item::whereIn("id",$data["itens"])
            ->where( function($query) {
                $query->whereNull("user_id")
                    ->orWhere("user_id","<>",(Auth::user())->id);
            })
            ->first();
        
        if($itens_from_user) 
            return $this->throwError(
                ['forbidden_itens'=>'Pelo menos um dos itens não pertence ao usuário logado']
            );


        foreach($data["itens"] as $item) {
            
            //Verifico se já existe solicitação para o item.
            $move_exists = MoveRequest::where("item_id",$item)
                ->where("move_requests.resolved","=","0")
                ->first();
            if($move_exists)
                return $this->throwError(
                    ['item_exists'=>'Já existe uma solicitação para o item '.$move_exists->item->item]
                );

            //Insiro a solicitação
            $move_request = new MoveRequest();
            $move_request->user_from_id = (Auth::user())->id;
            //O validator garante que ou vem user, ou um dos pares my_coord|other_coord
            if(array_key_exists("user_to",$data)) {
                //Verifico se o usuário é da mesma coordenação do logado
                $user_to = User::find($data["user_to"]);
                if($user_to->coordination_id!=(Auth::user())->coordination->id)
                    return $this->throwError(
                        ['user_from_other_coord'=>'Usuário não pertence a sua coordenação']
                    );
                
                $move_request->user_to_id = $data["user_to"]; 
            }
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

        $request->getSession()->flash("success",true);
        
        return redirect("move-requests/create");
        
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
