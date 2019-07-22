<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    protected $table = 'rounds';
    protected $primaryKey = 'id';

    public function game() {
    	return $this->hasOne('App\Game', 'game_id');
    }

    public function winner() {
    	return $this->hasOne('App\Player', 'round_winner');
    }
}
