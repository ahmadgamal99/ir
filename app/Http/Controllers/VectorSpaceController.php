<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use writecrow\Lemmatizer\Lemmatizer;

class VectorSpaceController extends Controller
{
    public $files;
    public function __construct()
    {
        $tokenizer = new TokenizerController();
        $this->files = $tokenizer->files;
    }

    public function termFrequency()
    {
        $tokenizer = new TokenizerController();
        $files = $tokenizer->getFiles();
        $uniqueTokens = array_unique($tokenizer->constructTokens());
        $stopWords = StopWordRemovalController::$stopWordList;
        $frequencyMatrix = [];
        foreach ($uniqueTokens as $key => $term)
        {
            if(in_array($term, $stopWords))
            {
                unset($uniqueTokens[$key]);
            }
        }

        foreach($uniqueTokens as  $uniqueToken)
        {

            foreach($files as $fileIndex => $file)
            {
                $frequencyMatrix[$uniqueToken][ 'file ' . ( $fileIndex + 1 ) ] = substr_count($file , $uniqueToken);
            }
        }

        return view('term_frequency' , compact('frequencyMatrix'));
    }


    public function inverseDocumentFrequency()
    {

        $docFrequencies = [];

        $tokenizer = new TokenizerController();
        $uniqueTokens = array_unique($tokenizer->constructTokens());
        $stopWords = StopWordRemovalController::$stopWordList;
        foreach ($uniqueTokens as $key => $term)
        {
            if(in_array($term, $stopWords))
            {
                unset($uniqueTokens[$key]);
            }
        }

        foreach ($uniqueTokens as $token) {

            $df = 0;

            foreach ($tokenizer->files as  $file)
            {
                ! str_contains($file , $token) ?: ++$df;
            }

            array_push($docFrequencies, [
                'term' => $token,
                'df' => $df,
                'idf' => round(log(10/$df , 10),1)
            ]);

        }

        return view('idf',compact('docFrequencies'));



    }

    public function TF_IDFWeightMatrix()
    {

        $tokenizer = new TokenizerController();
        $files = $tokenizer->getFiles();
        $uniqueTokens = array_unique($tokenizer->constructTokens());
        $stopWords = StopWordRemovalController::$stopWordList;
        $tf_Idf_Matrix = [];
        foreach ($uniqueTokens as $key => $term)
        {
            if(in_array($term, $stopWords))
            {
                unset($uniqueTokens[$key]);
            }
        }


        foreach($uniqueTokens as  $uniqueToken)
        {
            $df = 0;
            foreach ($tokenizer->files as  $file)
            {
                ! str_contains($file , $uniqueToken) ?: ++$df;
            }


            foreach($files as $fileIndex => $file)
            {

                $tf = substr_count($file , $uniqueToken);

                $tfWeight = round(log(1 + $tf , 10) , 1);

                $idfWeight = round(log(10/$df, 10),1);

                $tf_Idf_Matrix[$uniqueToken][ 'file ' . ( $fileIndex + 1 ) ] = $tfWeight * $idfWeight;

            }

        }


        return view('tf_idf_matrix' , compact('tf_Idf_Matrix'));

    }

    public function Normailize()
    {

        $similarityTables = $this->buildSimilarityTables();

        return view('similarity_tables' , compact('similarityTables'));


    }

    public function buildSimilarityTables()
    {

        $tokenizer = new TokenizerController();
        $files = $tokenizer->getFiles();
        $uniqueTokens = array_unique($tokenizer->constructTokens());
        $stopWords = StopWordRemovalController::$stopWordList;

        $similarityTables = [];

        foreach ($files as $fileIndex => $file)
        {
            $fileContent = file_get_contents(storage_path('app/files/file_' .( $fileIndex + 1) . '.txt'));

            $token = strtok($fileContent , " \n\t\r");

            $uniqueTokens = [];

            while ($token !== false)
            {
                $token = Lemmatizer::getLemma($token);

                if(! in_array($token, StopWordRemovalController::$stopWordList) && ! in_array($token, $uniqueTokens)){
                    array_push($uniqueTokens, $token);

                }

                $token = strtok(" \n\t\r");
            }



            foreach ($uniqueTokens as $uniqueToken) {
                $df = 0;

                foreach ($files as  $smallFile)
                {
                    ! str_contains($smallFile , $uniqueToken) ?: ++$df;
                }

                $tf = substr_count($file , $uniqueToken);

                $tfWeight = round(log(1 + $tf , 10) , 2);

                $idfWeight = round(log(10/$df, 10),2);

                $similarityTables[$fileIndex][$uniqueToken] = [
                    "tf" => $tf,
                    "tfWeight" => $tfWeight,
                    "idfWeight" => $idfWeight,
                    "tf-idf" => $tfWeight * $idfWeight,
                    "normalize" => 0, // tf-idf / length
                ];
            }


        }

        $similarityTables = collect($similarityTables);
        $docsLengths = [];

        // initialize the normalization in each term


        foreach ($similarityTables as $similarityTable) {

            $length = collect($similarityTable)->map(function($item){
                return pow($item['tf-idf'], 2);
            })->sum();

            $length = sqrt($length);

            array_push($docsLengths, $length);
        }
        $newSimilarityTables = [];
        foreach ($docsLengths as $index => $length) {
            foreach ($similarityTables[$index] as $token => $similarityTable) {

                $newSimilarityTables[$index][$token] = [
                    "tf" => $similarityTable['tf'],
                    "tfWeight" => $similarityTable['tfWeight'],
                    "idfWeight" => $similarityTable['idfWeight'],
                    "tf-idf" => $similarityTable['tf-idf'],
                    "normalize" => $similarityTable['tf-idf'] /$length, // tf-idf / length
                ];
            }

        }

        return $newSimilarityTables;
    }


    public function queryDocumentSimilarities($queryStringLemmitized = 'cat dog', $relevantDocs = [1,5,8])
    {

        $tokenizer = new TokenizerController();
        $files = $tokenizer->getFiles();
        $similarityTables = $this->buildSimilarityTables();


        $similarityTablesOfRelevantDocs = [];
        $similarityTableOfQuery = [];
        $queryUniqueTokens = array_unique(array_filter(explode(' ', $queryStringLemmitized)));

        foreach ($relevantDocs as $relevantDoc)
        {
            $similarityTablesOfRelevantDocs[$relevantDoc] = $similarityTables[$relevantDoc - 1];
        }




        foreach ($queryUniqueTokens as $uniqueToken) {
            $df = 0;
            foreach ($files as  $file)
            {
                ! str_contains($file , $uniqueToken) ?: ++$df;
            }

            if($df > 0){
                $tf = substr_count($queryStringLemmitized , $uniqueToken);
                $tfWeight = round(log(1 + $tf , 10) , 2);
                $idfWeight = round(log(10/$df, 10),2);

                $similarityTableOfQuery[$uniqueToken] = [
                    "tf" => $tf,
                    "tfWeight" => $tfWeight,
                    "idfWeight" => $idfWeight,
                    "tf-idf" => $tfWeight * $idfWeight,
                    "normalize" => 0, // tf-idf / length
                ];
            }

        }

        $similarityTableOfQuery = collect($similarityTableOfQuery);


        $queryLength = $similarityTableOfQuery ->map(function($item){
            return pow($item['tf-idf'], 2);
        })->sum();

        $queryLength = sqrt($queryLength);

        $newSimilarityTablesOfQuery =[];

        foreach ($similarityTableOfQuery as $term => $item){

            $newSimilarityTablesOfQuery[$term] = [
                "tf" => $item['tf'],
                "tfWeight" => $item['tfWeight'],
                "idfWeight" => $item['idfWeight'],
                "tf-idf" => $item['tf-idf'],
                "normalize" => $item['tf-idf'] / $queryLength,
            ];

        }

        $queryDocumentSimilarities = [];

        foreach ($similarityTablesOfRelevantDocs as $docID => $similarityTableOfRelevantDocs)
        {

            $queryDocumentSimilarities[$docID] = 0;

            foreach ($similarityTableOfRelevantDocs as $term => $table)
            {
                $queryDocumentSimilarities[$docID] += round(($newSimilarityTablesOfQuery[$term]['normalize'] ?? 0) * $table['normalize'] ,2 );
            }


        }


        return $queryDocumentSimilarities;
    }


    public function doQuery()
    {
        return view('do_query_similarity');
    }


    public function queryResult(Request $request)
    {


        $positionalIndex = new PositionalIndexController();
        $terms = explode(' ', $request->queryInput); // ['cats', 'dogs'] => 'cats dogs'

        foreach ($terms as $index => $term)
        {
            $terms[$index] = Lemmatizer::getLemma($term);
        }





        $relevantDocs = [];
        $positionalIndex = $positionalIndex->buildModel();


        $positionalIndex = collect($positionalIndex);


        // get the files that contains each word in the query
        $selectedPositions = $positionalIndex->whereIn('term', $terms)->pluck('positions')->toArray();
        $selectedPositionKeys = [];

        // make the structure simpler

        foreach ($selectedPositions as $selectedPosition) {
            array_push($selectedPositionKeys,array_keys($selectedPosition));
        }


        if(count($selectedPositionKeys) > 1){

            // obtain the intersection among files

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


        $similarities = $this->queryDocumentSimilarities(implode(' ', $terms), $relevantDocs);

        // sort the array descending


        arsort($similarities);

        $rankedDocs = $similarities;


        return view('query_results_ranked' , compact('rankedDocs'));
    }

}
