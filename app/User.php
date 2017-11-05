<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'admin'
    ];

    public function coordination()
    {
        return $this->belongsTo('App\Coordination');
    }
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
    * Se for coordenador, retorna a coordenação, senão retorna false
    */
    public function getCoordinatorAttribute() {
        if($this->coordination->coordinator->id===$this->id)
            return $this->coordination;
        else
            return false;
    }

    public function setAdminAttribute($value) {
        $this->attributes['admin'] = $value?true:false;
    }

    public function getAdminAttribute($value) {
        return $value?true:false;
    }
}
