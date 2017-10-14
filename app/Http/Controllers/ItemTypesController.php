<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ItemType;
use App\Util\SearchObject;

class ItemTypesController extends Controller
{
    public function index() {
       
    }

    public function search($type) {
        $types = ItemType::where('type','like',"%$type%")->get();
        //return $types;
        $searchObject = new SearchObject($types,'type');
        return response()->json($searchObject);
    }
}
