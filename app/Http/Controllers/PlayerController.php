<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Player;

class PlayerController extends Controller
{
    private const DELETED           = "Player Deleted";
    private const NAME_NOT_SENT     = "You must define a name";
    private const NAME_LENGTH_ERROR = "Name can't be empty";
    private const NOT_FOUND_ERROR   = "Player not found";
    private const UNABLE_TO_DELETE  = "Unable to delete player, please try again";
    private const UNABLE_TO_SAVE    = "Unable to save, please try again";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Player::all();
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
        $json   = $request->json();
        $player = new Player;
        $name   = null;
        if ( $json->has('name') ) {
            $name = $json->get('name');
            if ( strlen($name) == 0 ) {
                return response()->json([
                    "message" => self::NAME_LENGTH_ERROR
                ], 500);
            }
        } else {
            return response()->json([
                "message" => self::NAME_NOT_SENT
            ], 500);
        }
        try {
            $player->name = $name;
            if ( $player->save() ) {
                return $player;
            }
            return response()->json([
                "message" => self::UNABLE_TO_SAVE
            ], 500);
        } catch ( Exception $e ) {
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
        $player = Player::find($id);
        if ( $player != null ) {
            return $player;
        }
        return response()->json([
            "message" => self::NOT_FOUND_ERROR
        ], 400);
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
        $json   = $request->json();
        $player = Player::find($id);
        $name   = null;
        if ( $json->has('name') ) {
            $name = $json->get('name');
            if ( strlen($name) == 0 ) {
                return response()->json([
                    "message" => self::NAME_LENGTH_ERROR
                ], 500);
            }
        } else {
            return response()->json([
                "message" => self::NAME_NOT_SENT
            ], 500);
        }
        try {
            $player->name = $name;
            if ( $player->save() ) {
                return $player;
            }
            return response()->json([
                "message" => self::UNABLE_TO_SAVE
            ], 500);
        } catch ( Exception $e ) {
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
        $player = Player::find($id);
        if( $player == null ) {
            return response()->json([
                "message" => self::NOT_FOUND_ERROR
            ], 400);
        }
        if( $player != null && $player->delete() ) {
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
