<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{

    protected $table = 'itens';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item', 'patrimony_number'
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function coordination() {
        return $this->belongsTo('App\Coordination');
    }

    public function type() {
        return $this->belongsTo('App\ItemType');
    }

}
