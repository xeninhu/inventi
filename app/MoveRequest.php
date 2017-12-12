<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MoveRequest extends Model
{
    /**comentando pois não usarei por somente um campo ser utilizado.
    protected $fillable = [
        "description"
    ];*/

    public function user_from() {
        return $this->belongsTo('App\User');
    }

    public function user_to() {
        return $this->belongsTo('App\User');
    }

    public function coordination() {
        return $this->belongsTo('App\Coordination');
    }

    public function item() {
        return $this->belongsTo('App\Item');
    }

}
