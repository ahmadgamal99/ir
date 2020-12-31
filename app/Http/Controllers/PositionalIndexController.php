<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use writecrow\Lemmatizer\Lemmatizer;

class PositionalIndexController extends Controller
{
    public function positionalModelView()
    {
        $positionalIndex = $this->buildModel();
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
            foreach ($tokenizer->files as $index => $file) {
                $docId = $index + 1;
                $occurrences = array_keys(preg_split('/\s+/', $file), $token);
                if(count($occurrences) > 0){
                    ++$frequency;
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

        $vectorSpaceModel = new VectorSpaceController();
        $terms = explode(' ', $request->queryInput); // ['cats', 'dogs'] => 'cats dogs'

        foreach ($terms as $index => $term)
        {
            $terms[$index] = Lemmatizer::getLemma($term);
        }


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


            foreach ($intersectedDocs as $intersectedDoc)
            {
                $fileContent = file_get_contents(storage_path('app/files/file_' . $intersectedDoc . '.txt'));

                $token = strtok($fileContent , " \n\t\r");

                $fileContentLemmitized = "";

                while ($token !== false)
                {
                    $token = Lemmatizer::getLemma($token);
                    $fileContentLemmitized .= $token . " ";
                    $token = strtok(" \n\t\r");
                }


                !str_contains($fileContentLemmitized, implode(" " , $terms) ) ?: array_push($relevantDocs, $intersectedDoc);

            }

        }elseif (count($selectedPositionKeys) == 1)
        {
            $relevantDocs = $selectedPositionKeys[0];
        }



        $similarities = $vectorSpaceModel->queryDocumentSimilarities(implode(' ', $terms), $relevantDocs);
        dd($similarities, $relevantDocs);
        return view('query_result');
    }

}
