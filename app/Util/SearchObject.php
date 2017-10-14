<?php

namespace App\Util;

class SearchObject {
    public $results;
    
    public function __construct($itens,$field) {
        $this->results = [];
        foreach($itens as $item) {
            $obj = new \stdClass();
            $obj->title = $item->$field;
            $this->results[] = $obj;
        }
    }
}