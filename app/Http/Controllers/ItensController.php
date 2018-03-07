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
use Illuminate\Support\Facades\Auth;
use App\Mail\MovingItens;
use Illuminate\Support\Facades\Mail;

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

    /**
    *   Movimenta itens para um usuário. Os itens devem vir como string de ids separados por virgula
    *   enquanto o usuário apenas com o id.
    *
    *   Coordenador só poderá movimentar itens da coordenação dele, para usuários da sua coordenação. Usuários comuns
    *   que não são coordenadores não terão acesso a movimentação direta dos itens.
    *
    *   Caso o usuário logado não for o usuário afetado com a movimentação, um e-mail deverá ser enviado.
    */

    public function moveItensToUser(Request $request) {
        
        $wrong_ids = array();
        $correct_itens = array();
        $from_user = array();//Armazeno itens que já são do usuário e não precisam ser movidos

        $logged_user = Auth::user();
        $coordination = $logged_user->coordinator; //Pega a coordenação ou false se não for coordenador
        if(!$logged_user->admin && !$logged_user->coordinator)
           return abort(401,"Você precisa ser adminstrador ou coordenador para acessar essa busca");
        
        $user_id = $request->user;
        $user = User::find($user_id);
        if(!$logged_user->admin && $coordination->id!==$user->coordination->id)
                return abort(401,"Coordenador só pode atribuir itens a colaboradores de sua coordenação");

        $itens_id = explode(",",$request->itens);
        
        foreach($itens_id as $item_id) {
            try {
                $item = Item::findOrFail($item_id);
                if($item->user!=null && $item->user->id===$user->id) {
                    $from_user[] = $item;
                }
                else {
                    $item->user()->associate($user);
                    $item->save();
                    $correct_itens[] = $item;
                }
            }catch(ModelNotFoundException $e) {
                $wrong_ids[] = $item_id;
            }
        }
        
        $session = $request->getSession();
        if(!empty($wrong_ids))
            $session->flash('wrong_ids',true);
        
        if(!empty($correct_itens) || !empty($from_user)) {
            $session->flash('user',$user);
            if(!empty($correct_itens)) {
                $session->flash('correct_itens',$correct_itens);
                Mail::to($user->email)->send(new MovingItens($correct_itens));
            }
            if(!empty($from_user)) 
                $session->flash('from_user',$from_user);
        }
            
       
        

        return redirect('/itens/move');
    }


    /**
    *   Lista patrimônios para coordenadores ou admins. Caso perfil logado seja coordenador,
    *   lista apenas patrimônios da coordenação, se for admin lista qualquer um.
    */

    public function search($patrimony_number) {
        $logged_user = Auth::user();
        $coordination = $logged_user->coordinator; //Pega a coordenação ou false se não for coordenador
        if(!$logged_user->admin && !$logged_user->coordinator)
            return response([
                "message" => "Você precisa ser adminstrador ou coordenador para acessar essa busca"
                ],401);
        
        if(!$logged_user->admin)  //Não é admin, é coordenador.
            $itens = Item::where('patrimony_number','like',"%$patrimony_number%")
                ->where('coordination_id',$coordination->id)
                ->get();
        else //Admin pega qualquer item
            $itens = Item::where('patrimony_number','like',"%$patrimony_number%")->get();

        $search_object = new SearchObject($itens,'patrimony_number','id');
        return response()->json($search_object);
    }

    public function pageItensGroupedByUser(Request $request) {
        $coordinations = Coordination::all();
        $users = null;
        if(isset($request->coordination)) {
            $users = User::where('coordination_id',$request->coordination)
                ->with('itens')->get();
            $itens_alone = Item::doesntHave("user")->get();
        }
        $array_to_view = [
            "coordinations" => $coordinations,
            "coordination" => $request->coordination,
            "users" => $users
        ];
        return view('itens-reports/pagegroupedbyuser',$array_to_view);
    }
    
}
