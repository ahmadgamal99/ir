<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VectorSpaceController extends Controller
{
    public $files;
    public function __construct()
    {
        $tokenizer = new TokenizerController();
        $this->files = $tokenizer->files;
    }

    public function termFrequency($term)
    {
//        foreach ($this->files as $file) {
//            $docId = array_search($file,$this->files) + 1;
//            $frequency += substr_count($file, $token);
//            $occurrences = array_keys(preg_split('/\s+/', $file), $token);
//            if(count($occurrences) > 0){
//                $positions[$docId] =  $occurrences;
//            }
//        }
    }


    public function inverseDocumentFrequency($term)
    {

    }

    public function TF_IDFWeightMatrix()
    {

    }
}
