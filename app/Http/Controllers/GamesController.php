<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Game;

class GamesController extends Controller
{
    private const DELETED              = "Game deleted";
    private const PLAYER_ONE_UNDEFINED = "Player One is undefined";
    private const PLAYER_TWO_UNDEFINED = "Player Two is undefined";
    private const PLAYER_ONE_INVALID   = "Player One must be a number";
    private const PLAYER_TWO_INVALID   = "Player Two must be a number";
    private const ENDED_UNDEFINED      = "Status of the game must be true or false";
    private const GAME_NOT_FOUND       = "Game not found";
    private const UNABLE_TO_SAVE       = "Unable to save game, try again";
    private const UNABLE_TO_DELETE     = "Unable to delete game, try again";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Game::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $json      = $request->json();
        $game      = new Game;
        $playerOne = null;
        $playerTwo = null;
        if ( $json->has('player_one') ) {
            $playerOne = (int) preg_replace('/\D/','',$json->get('player_one'));
            if ( $playerOne <= 0 ) {
                return response()->json([
                    "message" => self::PLAYER_ONE_INVALID
                ], 500);
            }
        } else {
            return response()->json([
                "message" => self::PLAYER_ONE_UNDEFINED
            ], 500);
        }
        if ( $json->has('player_two') ) {
            $playerTwo = (int) preg_replace('/\D/','',$json->get('player_two'));
            if ( $playerTwo <= 0 ) {
                return response()->json([
                    "message" => self::PLAYER_TWO_INVALID
                ], 500);
            }
        } else {
            return response()->json([
                "message" => self::PLAYER_TWO_UNDEFINED
            ], 500);
        }
        $game->player_one = $playerOne;
        $game->player_two = $playerTwo;
        if ( $game->save() ) {
            return $game;
        }
        return response()->json([
            "message" => self::UNABLE_TO_SAVE
        ], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $game = Game::find($id);
        if ( $game == null ) {
            return response()->json([
                "message" => self::GAME_NOT_FOUND
            ], 400);
        }
        return $game;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $json      = $request->json();
        $game      = Game::find($id);
        $playerOne = null;
        $playerTwo = null;
        $ended     = false;
        if ( $game == null ) {
            return response()->json([
                "message" => self::GAME_NOT_FOUND
            ], 400);
        }
        if ( $json->has('player_one') ) {
            $playerOne = (int) preg_replace('/\D/','',$json->get('player_one'));
            if ( $playerOne <= 0 ) {
                return response()->json([
                    "message" => self::PLAYER_ONE_INVALID
                ], 500);
            }
        } else {
            return response()->json([
                "message" => self::PLAYER_ONE_UNDEFINED
            ], 500);
        }
        if ( $json->has('player_two') ) {
            $playerTwo = (int) preg_replace('/\D/','',$json->get('player_two'));
            if ( $playerTwo <= 0 ) {
                return response()->json([
                    "message" => self::PLAYER_TWO_INVALID
                ], 500);
            }
        } else {
            return response()->json([
                "message" => self::PLAYER_TWO_UNDEFINED
            ], 500);
        }
        if ( $json->has('ended') ) {
            $ended = (bool) $json->get('ended');
        } else {
            return response()->json([
                "message" => self::ENDED_UNDEFINED
            ], 500);
        }
        /*
        $request->validate([
            'player_one' => 'required|max:120',
            'player_two' => 'required|max:120',
            'ended' => ''
        ]);
        //*/
        $game->player_one = $playerOne;
        $game->player_two = $playerTwo;
        $game->ended      = $ended;
        if ( $game->save() ) {
            return $game;
        }
        return response()->json([
            "message" => self::UNABLE_TO_SAVE
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $game = Game::find($id);
        if ( $game == null ) {
            return response()->json([
                "message" => self::GAME_NOT_FOUND
            ], 400);
        }
        if ( $game->delete() ) {
            return response()->json([
                "message" => self::DELETED,
                "id" => $id
            ], 200);
        }
        return response()->json([
            "message" => self::UNABLE_TO_DELETE
        ], 500);
    }
}
