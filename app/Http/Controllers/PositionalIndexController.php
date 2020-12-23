<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PositionalIndexController extends Controller
{
    public function positionalModelView()
    {
        $positionalIndex = $this->buildModel();
        dd($positionalIndex);
        return view('positional_index_model',[
            'positionalIndex' => $positionalIndex
        ]);

    }
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
        $stopWords = StopWordRemovalController::$stopWordList;
        $uniqueTokens = array_unique($tokenizer->constructTokens());
        foreach ($uniqueTokens as $key => $term) {
            if(in_array($term, $stopWords)){
                unset($uniqueTokens[$key]);
            }
        }
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
        return $positionalIndex;

    }

    public function doQuery()
    {
        return view('do_query');
    }
    public function queryResult(Request $request)
    {
        $terms = explode(' ', $request->queryInput); // ['cats', 'dogs'] => 'cats dogs'
        $relevantDocs = [];
        $positionalIndex = $this->buildModel();
        $positionalIndex = collect($positionalIndex);
        $selectedPositions = $positionalIndex->whereIn('term', $terms)->pluck('positions')->toArray();
        $selectedPositionKeys = [];
        foreach ($selectedPositions as $selectedPosition) {
            array_push($selectedPositionKeys,array_keys($selectedPosition));
        }

        if(count($selectedPositionKeys) > 1){
            $intersectedDocs = call_user_func_array('array_intersect', $selectedPositionKeys);

            foreach ($intersectedDocs as $intersectedDoc) {
                $fileContent = file_get_contents(storage_path('app/files/file_' . $intersectedDoc . '.txt'));
                !str_contains($fileContent, $request['queryInput']) ?: array_push($relevantDocs, $intersectedDoc);
            }
        }elseif (count($selectedPositionKeys) == 1){
            $relevantDocs = $selectedPositionKeys[0];
        }

        dd($relevantDocs);

        return view('query_result');
    }

}
