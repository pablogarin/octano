<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $table = 'players';
    protected $primaryKey = 'id';
    protected $fillable = [
    	"name"
    ];
    /*
    public function games() {
    	return $this->hasMany('App\Game', '')
    }
    //*/
}
