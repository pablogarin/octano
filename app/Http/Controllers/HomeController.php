<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Player;

class HomeController extends Controller
{
    public function showPlayers() {
    	$playersList = Player::all();
    	$players = [];
    	foreach( $playersList as $player ) {
    		$players[] = $player->name;
    	}
    	return join(", ", $players);
    }
}
