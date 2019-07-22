<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Round;

class RoundController extends Controller
{
    private const DELETED                    = "Round deleted";
    private const ROUND_NOT_FOUND            = "Round not found";
    private const ROUND_NUMBER_UNDEFINED     = "round number must be defined";
    private const GAME_ID_UNDEFINED          = "game must be defined";
    private const ROUND_WINNER_UNDEFINED     = "round winner must be defined";
    private const PLAYER_ONE_MOVE_UNDEFINED  = "player one move must be defined";
    private const PLAYER_TWO_MOVE_UNDEFINED  = "player two move must be defined";
    private const ROUND_NUMBER_INVALID_VALUE = "Round number must be a valid number";
    private const GAME_ID_INVALID_VALUE      = "Game must be a valid number";
    private const ROUND_WINNER_INVALID_VALUE = "Round winner must be a valid number";
    private const UNABLE_TO_SAVE             = "Unable to save round, please try again";
    private const UNABLE_TO_DELETE           = "Unable to delete round, please try again";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Round::all();
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
        $round           = new Round;
        $json            = $request->json();
        $round_number    = null;
        $game_id         = null;
        $round_winner    = null;
        $player_one_move = null;
        $player_two_move = null;
        if ( $json->has('round_number') ) {
            $round_number = (int) preg_replace('/\D/', '', $json->get('round_number'));
            if ( $round_number <= 0 ) {
                return response()->json([
                    "message" => self::ROUND_NUMBER_INVALID_VALUE
                ], 500);
            }
        } else {
            return response()->json([
                "message" => self::ROUND_NUMBER_UNDEFINED
            ], 500);
        }
        if ( $json->has('game_id') ) {
            $game_id = (int) preg_replace('/\D/', '', $json->get('game_id'));
            if ( $game_id <= 0 ) {
                return response()->json([
                    "message" => self::GAME_ID_INVALID_VALUE
                ], 500);
            }
        } else {
            return response()->json([
                "message" => self::GAME_ID_UNDEFINED
            ], 500);
        }
        if ( $json->has('round_winner') ) {
            $round_winner = (int) preg_replace('/\D/', '', $json->get('round_winner'));
            if ( $round_winner <= 0 ) {
                return response()->json([
                    "message" => self::ROUND_WINNER_INVALID_VALUE
                ], 500);
            }
        }
        if ( $json->has('player_one_move') ) {
            $player_one_move = $json->get('player_one_move');
        } else {
            return response()->json([
                "message" => self::PLAYER_ONE_MOVE_UNDEFINED
            ], 500);
        }
        if ( $json->has('player_two_move') ) {
            $player_two_move = $json->get('player_two_move');
        } else {
            return response()->json([
                "message" => self::PLAYER_TWO_MOVE_UNDEFINED
            ], 500);
        }
        try {
            $round->round_number    = $round_number;
            $round->game_id         = $game_id;
            $round->round_winner    = $round_winner;
            $round->player_one_move = $player_one_move;
            $round->player_two_move = $player_two_move;
            if ( $round->save() ) {
                return $round;
            }
            return response()->json([
                "message" => self::UNABLE_TO_SAVE
            ], 500);
        } catch( Exception $e ) {
            return response()->json([
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $round = Round::find($id);
        if ( $round == null ) {
            return response()->json([
                "message" => self::ROUND_NOT_FOUND
            ], 400);
        }
        return $round;
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
        $round           = Round::find($id);
        $json            = $request->json();
        $round_number    = null;
        $game_id         = null;
        $round_winner    = null;
        $player_one_move = null;
        $player_two_move = null;
        if ( $round == null ) {
            return response()->json([
                "message" => self::ROUND_NOT_FOUND
            ], 400);
        }
        if ( $json->has('round_number') ) {
            $round_number = (int) preg_replace('/\D/', '', $json->get('round_number'));
            if ( $round_number <= 0 ) {
                return response()->json([
                    "message" => self::ROUND_NUMBER_INVALID_VALUE
                ], 500);
            }
        } else {
            return response()->json([
                "message" => self::ROUND_NUMBER_UNDEFINED
            ], 500);
        }
        if ( $json->has('game_id') ) {
            $game_id = (int) preg_replace('/\D/', '', $json->get('game_id'));
            if ( $game_id <= 0 ) {
                return response()->json([
                    "message" => self::GAME_ID_INVALID_VALUE
                ], 500);
            }
        } else {
            return response()->json([
                "message" => self::GAME_ID_UNDEFINED
            ], 500);
        }
        if ( $json->has('round_winner') ) {
            $round_winner = (int) preg_replace('/\D/', '', $json->get('round_winner'));
            if ( $round_winner <= 0 ) {
                return response()->json([
                    "message" => self::ROUND_WINNER_INVALID_VALUE
                ], 500);
            }
        } else {
            return response()->json([
                "message" => self::ROUND_WINNER_UNDEFINED
            ], 500);
        }
        if ( $json->has('player_one_move') ) {
            $player_one_move = $json->get('player_one_move');
        } else {
            return response()->json([
                "message" => self::PLAYER_ONE_MOVE_UNDEFINED
            ], 500);
        }
        if ( $json->has('player_two_move') ) {
            $player_two_move = $json->get('player_two_move');
        } else {
            return response()->json([
                "message" => self::PLAYER_TWO_MOVE_UNDEFINED
            ], 500);
        }
        try {
            $round->round_number    = $round_number;
            $round->game_id         = $game_id;
            $round->round_winner    = $round_winner;
            $round->player_one_move = $player_one_move;
            $round->player_two_move = $player_two_move;
            if ( $round->save() ) {
                return $round;
            }
            return response()->json([
                "message" => self::UNABLE_TO_SAVE
            ], 500);
        } catch( Exception $e ) {
            return response()->json([
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $round = Round::find($id);
        if ( $round == null ) {
            return response()->json([
                "message" => self::ROUND_NOT_FOUND
            ], 400);
        }
        if ( $round->delete() ) {
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
