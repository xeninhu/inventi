<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Coordination;
use App\Item;
use App\ItemType;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Util\SearchObject;
use App\User;

class ItensController extends Controller
{
    
    /**
    * Get a validator for an incoming registration request.
    *
    * @param  array  $data
    * @return \Illuminate\Contracts\Validation\Validator
    */
    protected function validator(array $data,$id=false)
    {
        $rules = [
            'item_type' => 'required|string|max:255',
            'item' => 'required|string|max:255',
            'coordination' => 'required|integer|exists:coordinations,id'
        ];
        if(!$id)
            $rules['patrimony_number'] = 'required|integer|unique:itens';
        else
            $rules['patrimony_number'] = "required|integer|unique:itens,patrimony_number,$id";

        return Validator::make($data, $rules);
    }

    //update e store possuem esse mesmo trecho de código. Aqui eu altero o item com os dados vindos do template
    private function fillItem(&$item,$request) {

        $data = $request->all();

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

        return $item;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $itens = Item::paginate(10);

        return view('itens.index',['itens'=>$itens]);
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
        $this->validator($request->all())->validate();

        $item = new Item();

        $this->fillItem($item,$request);
        $item->save();
        
        $request->session()->flash('successMessage','Item cadastrado com sucesso');
        return redirect("itens/$item->id/edit");

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
        $item = Item::find($id);
        $coordinations = Coordination::all();

        return view("itens.edit",['item'=>$item,'coordinations'=>$coordinations]);
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
        $this->validator($request->all(),$id)->validate();

        $item = Item::findOrFail($id);

        $this->fillItem($item,$request);
        
        $item->save();

        $request->session()->flash('successMessage','Item atualizado com sucesso');
        return redirect("itens/$item->id/edit");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,Request $request)
    {
        Item::destroy($id);
        $request->session()->flash('from-remove',true);
        return redirect('/itens');
    }

    public function moveItensToUserPage() {
        return view('itens.moving');
    }

    public function moveItensToUser(Request $request) {
        
        //return $request->all();

        $user_id = $request->user;
        $user = User::find($user_id);
        $itens_id = explode(",",$request->itens);
        $wrong_ids = array();
        $correct_itens = array();
        foreach($itens_id as $item_id) {
            try {
                $item = Item::findOrFail($item_id);
                $item->user()->associate($user);
                $item->save();
                $correct_itens[] = $item;
            }catch(ModelNotFoundException $e) {
                $wrong_ids[] = $item_id;
            }
        }
        
        $session = $request->getSession();
        if(!empty($wrong_ids))
            $session->flash('wrong_ids',true);
        if(!empty($correct_itens)) {
            $session->flash('correct_itens',$correct_itens);
            $session->flash('user',$user);
        }

        return redirect('/itens/move');
    }

    public function search($patrimony_number) {
        $itens = Item::where('patrimony_number','like',"%$patrimony_number%")->get();
        $search_object = new SearchObject($itens,'patrimony_number','id');
        return response()->json($search_object);
    }
    //$user_id,$patrimonys_id
}
