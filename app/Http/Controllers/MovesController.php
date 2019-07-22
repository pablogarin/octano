<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Move;

class MovesController extends Controller
{
    private const DELETED          = "Move Deleted";
    private const INVALID_ID       = "ID must be a number greater than 0";
    private const NAME_NOT_SENT    = "You must define a name";
    private const KILLS_NOT_SENT   = "You must define which move is killed by this one";
    private const NOT_FOUND_ERROR  = "Move not found";
    private const NOT_SAVED_ERROR  = "Move not saved";
    private const UNABLE_TO_DELETE = "Unable to delete Move";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Move::all();
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
        $json = $request->json();
        // Attributes
        $name  = null;
        $kills = null;

        if ( !$json->has('name') ) {
            return response()->json([
                "message" => self::NAME_NOT_SENT
            ], 500);
        } else {
            $name = $json->get('name');
        }
        if ( count(Move::all()) > 0 && !$json->has('kills') ) {
            return response()->json([
                "message" => self::KILLS_NOT_SENT
            ], 500);
        }
        if ( $json->has('kills') ) {
            $kills = (int) preg_replace('/\D/', '', $json->get('kills'));
            if ( $kills <= 0 ) {
                return response()->json([
                    "message" => self::INVALID_ID
                ], 500);
            }
            if ( Move::find($kills) == null ) {
                return response()->json([
                    "message" => self::NOT_FOUND_ERROR
                ], 400);
            }
        }

        // Create Element
        $move        = new Move;
        $move->name  = $name;
        $move->kills = $kills;
        try {
            if ( $move->save() ) {
                return $move;
            }
        } catch ( Exception $e ) {
            return response()->json([
                "exception" => $e->getMessage()
            ], 500);
        }
        return response()->json([
            "message" => self::NOT_SAVED_ERROR
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
        $move = Move::find($id);
        if ( $move == null ) {
            return response()->json([
                "message" => self::NOT_FOUND_ERROR
            ], 400);
        }
        return $move;
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
        $move  = Move::find($id);
        $json  = $request->json();
        $name  = null;
        $kills = null;
        if ( $move == null ) {
            return response()->json([
                "message" => self::NOT_FOUND_ERROR
            ], 400);
        }
        if ( $json->has('name') ) {
            $name = $json->get('name');
        } else {
            return response()->json([
                "message" => self::NAME_NOT_SENT
            ], 500);
        }
        if( $json->has('kills') ) {
            $kills = (int) preg_replace('/\D/', '', $json->get('kills'));
            if ( $kills <= 0 ) {
                return response()->json([
                    "message" => self::INVALID_ID
                ], 500);
            }
            if ( Move::find($kills) ==  null ) {
                return response()->json([
                    "message" => self::NOT_FOUND_ERROR,
                    "details" => "Foreign key error"
                ], 400);
            }
        } else {
            if ( count(Move::all()) > 0 ) {
                return response()->json([
                    "message" => self::KILLS_NOT_SENT
                ], 500);
            }
        }
        $move->name = $name;
        if ( $kills != null ) {
            $move->kills = $kills;
        }
        try {
            if ( $move->save() ) {
                return $move;
            }
        } catch ( Exception $e ) {
            return response()->json([
                "exception" => $e->getMessage()
            ], 500);
        }
        return response()->json([
            "message" => self::NOT_SAVED_ERROR
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
        $move  = Move::find($id);
        if ( $move == null ) {
            return response()->json([
                "message" => self::NOT_FOUND_ERROR
            ], 400);
        }
        if ( $move->delete() ) {
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
