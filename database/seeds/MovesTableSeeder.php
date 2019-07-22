<?php

use Illuminate\Database\Seeder;
use App\Move;

class MovesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Rock
        $rock = new Move;
        $rock->name = "Rock";
        $rock->save();

        // Paper
        $paper = new Move;
        $paper->name = "Paper";
        $paper->kills = $rock->id;
        $paper->save();

        // Scissor
        $scissor = new Move;
        $scissor->name = "Scissor";
        $scissor->kills = $paper->id;
        $scissor->save();

        $rock->kills = $scissor->id;
        $rock->save();

    }
}
