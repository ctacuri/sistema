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
        'id', 'idempresa', 'usuario', 'password', 'idrol', 'condicion'
    ];

    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];
 
    public function persona(){
        return $this->hasOne('App\Persona','id'); //Un usuario hace referencia a uan persona
    }

    public function empresa()
    {
        return $this->belongsTo('App\Empresa','idempresa'); //Un usuario pertenece a una empresa
    }

    public function rol(){
        return $this->belongsTo('App\Rol','idrol'); //Un usuario pertenece a un Rol
    }
}
