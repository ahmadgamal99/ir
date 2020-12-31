<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use writecrow\Lemmatizer\Lemmatizer;

class TokenizerController extends Controller
{


    public $files = [];

    public function __construct()
    {
        $this->files = $this->getFiles();
    }

    public function viewTokens()
    {
        $tokensWithDocID = $this->tokensWithDocID();
        return view('tokenization_result',compact('tokensWithDocID'));
    }



    public function getFiles()
    {

        $files  = [];

        for($i = 1 ; $i <= 10 ; $i++)
        {
            $file = fopen(storage_path('app/files/file_'. $i .'.txt'), "r");
            $file_content = fread($file,filesize(storage_path('app/files/file_' . $i .'.txt')));

            $token = strtok($file_content , " \n\t");
            $fileContentLemmitized = "";

            while ($token !== false )
            {
                $token = Lemmatizer::getLemma($token);
                $fileContentLemmitized .= $token . " ";
                $token = strtok(" \n\t\r");
            }

            array_push($files , $fileContentLemmitized);

        }

        return $files;
    }

    public function constructTokens()
    {
        $tokens = [];
        foreach ($this->files as $file) {
            $token = strtok($file , " \n\t");
            while ($token !== false)
            {
                array_push($tokens, $token);
                $token = strtok(" \n\t");
            }
        }
        return $tokens;
    }

    public function tokensWithDocID()
    {
        $tokens = [];
        foreach ($this->files as $file) {
            $token = strtok($file , " \n\t");
            $docId = array_search($file, $this->files) + 1;
            while ($token !== false)
            {
                array_push($tokens, [
                    'token' => $token,
                    'docID' => $docId,
                ]);
                $token = strtok(" \n\t");
            }
        }
        return $tokens;
    }

}
