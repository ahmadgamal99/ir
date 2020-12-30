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
}
