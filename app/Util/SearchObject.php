<?php

namespace App\Util;

class SearchObject {
    public $results;
    
    /**
    * Constrói objeto de busca simples, para auto complete. Caso passado o parâmetro $field_value o objeto de busca
    * se torna um objeto para select, sendo $field o name e $field_value o conteúdo do value do select.
    */
    public function __construct($itens,$field,$field_value=false) {
        $this->results = [];
        foreach($itens as $item) {
            $obj = new \stdClass();
            if(!$field_value) {
                $obj->title = $item->$field;  
            }
            else {
                $obj->name = $item->$field;
                $obj->value = $item->$field_value;
            }
            $this->results[] = $obj;
        }
    }
    
   
}