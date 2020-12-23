<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PositionalIndexController extends Controller
{
    public function buildModel()
    {
        $tokenizer = new TokenizerController();
        /*
         * [
         *  "term" => 'khaled',
         *  "freq" => 15,
         *  "positions" => [
         *      'doc1' => [1,2,3,4],
         *      'doc2' => [1,2,3,4],
         *      'doc3' => [1,2,3,4],
         *      ...................,
         *  ]
         * ]
         * */
        $uniqueTokens = array_unique($tokenizer->constructTokens());
        $positionalIndex = [];
        foreach ($uniqueTokens as $token) {
            $positions = [];
            $frequency = 0;
            foreach ($tokenizer->files as $file) {
                $docId = array_search($file, $tokenizer->files) + 1;
                $frequency += substr_count($file, $token);
                $occurrences = array_keys(preg_split('/\s+/', $file), $token);
                if(count($occurrences) > 0){
                    $positions[$docId] =  $occurrences;
                }
            }

            array_push($positionalIndex, [
                'term' => $token,
                'frequency' => $frequency,
                'positions' => $positions
            ]);

        }
        return view('positional_index_model',[
            'positionalIndex' => $positionalIndex
        ]);

    }
}
