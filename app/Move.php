<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Move extends Model
{
    protected $table = 'moves';
    protected $primaryKey = 'id';
    protected $fillable = [
    	"name",
    	"kills"
    ];

    public function kills() {
    	return $this->hasOne('App\Move', 'kills');
    }
}
