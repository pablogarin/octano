<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $table = 'games';
    protected $primaryKey = 'id';
    protected $attributes = [
    	'ended' => false,
    ];
    
    public function playerOne() {
    	return $this->hasOne('App\Player', 'player_one');
    }

    public function playerTwo() {
    	return $this->hasOne('App\Player', 'player_two');
    }

    public function rounds() {
    	return $this->hasMany('App\Round', 'game_id');
    }

    public function getWinner() {
    	if( $this->ended ) {
    		$rouns = $this->rounds();
    		print($rounds);
    	} else {
    		return null;
    	}
    }
}
