<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coordination extends Model
{
    public function coordinator() {
        return $this->belongsTo('App\User');
    }
}
