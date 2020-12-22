<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use writecrow\Lemmatizer\Lemmatizer;

class MainController extends Controller
{

    public static  $stopWordList = [ "a", "about", "above", "after", "again", "against", "all", "am", "an", "and", "any", "are", "aren't", "as",
        "at", "be", "because", "been", "before", "being", "below", "between", "both", "but", "by", "can't",
        "cannot",
        "could",
        "couldn't",
        "did",
        "didn't",
        "do",
        "does",
        "doesn't",
        "doing",
        "don't",
        "down",
        "during",
        "each",
        "few",
        "for",
        "from",
        "further",
        "had",
        "hadn't",
        "has",
        "hasn't",
        "have",
        "haven't",
        "having",
        "he",
        "he'd",
        "he'll",
        "he's",
        "her",
        "here",
        "here's",
        "hers",
        "herself",
        "him",
        "himself",
        "his",
        "how",
        "how's",
        "i",
        "i'd",
        "i'll",
        "i'm",
        "i've",
        "if",
        "in",
        "into",
        "is",
        "isn't",
        "it",
        "it's",
        "its",
        "itself",
        "let's",
        "me",
        "more",
        "most",
        "mustn't",
        "my",
        "myself",
        "no",
        "nor",
        "not",
        "of",
        "off",
        "on",
        "once",
        "only",
        "or",
        "other",
        "ought",
        "our",
        "ours",
        "ourselves",
        "out",
        "over",
        "own",
        "same",
        "shan't",
        "she",
        "she'd",
        "she'll",
        "she's",
        "should",
        "shouldn't",
        "so",
        "some",
        "such",
        "than",
        "that",
        "that's",
        "the",
        "their",
        "theirs",
        "them",
        "themselves",
        "then",
        "there",
        "there's",
        "these",
        "they",
        "they'd",
        "they'll",
        "they're",
        "they've",
        "this",
        "those",
        "through",
        "to",
        "too",
        "under",
        "until",
        "up",
        "very",
        "was",
        "wasn't",
        "we",
        "we'd",
        "we'll",
        "we're",
        "we've",
        "were",
        "weren't",
        "what",
        "what's",
        "when",
        "when's",
        "where",
        "where's",
        "which",
        "while",
        "who",
        "who's",
        "whom",
        "why",
        "why's",
        "with",
        "won't",
        "would",
        "wouldn't",
        "you",
        "you'd",
        "you'll",
        "you're",
        "you've",
        "your",
        "yours",
        "yourself"
    ];

    public $files = [];

    public function __construct()
    {
        $this->files = $this->getFiles();
    }

    public function tokenization()
    {

        $tokens = [];

        for($i = 0 ; $i <= 9 ; $i++)
        {
            $token = strtok($this->files[$i] , " \n\t");

            while ($token !== false)
            {
                $tokens[$i][$token] = $i + 1;
                $token = strtok(" \n\t");
            }

        }


        return view('tokenization_result',compact('tokens'));
    }

    public function stopWordRemovalView()
    {

        $tokens = $this->stopWordRemoval();

        return view('stop-word-removal-result',compact('tokens'));

    }


    public function query()
    {
        return view('query-result');
    }

    public function queryResult()
    {
        dd('hello');
        return view('query-result');
    }

    public function buildInvertedIndex()
    {

        $tokens = $this->stopWordRemoval();

        dd($tokens);

        $tokensFlatten = [];
        $docsNo = [];

        foreach ($tokens as $token)
        {

            foreach ($token as $term => $docID)
            {
                $term = Lemmatizer::getLemma($term);

                array_push($tokensFlatten , $term);

                if(array_key_exists($term,$docsNo))
                {

                    array_push($docsNo[$term] , $docID);


                }else
                {
                    $docsNo[$term] = [$docID];
                }


            }

        }

        sort($tokensFlatten);

        dd($tokensFlatten);

        $tokenFrequencies = array_count_values(($tokensFlatten));

        dd($tokenFrequencies);

        return view('inverted-index',compact('tokenFrequencies','docsNo'));

    }


    public function stopWordRemoval()
    {
        $tokens = [];
        $files_size = [];
/*
 * [
 *  "term" => [docID => [p1, p2, p3]]
 * ]
 * */

        for($i = 0 ; $i <= 9 ; $i++)
        {
            $token = strtok($this->files[$i] , " \n\t");

            while ($token !== false)
            {
                array_push($tokens, $token);
                $token = strtok(" \n\t");
            }

            array_push($files_size ,str_word_count($this->files[$i]));
        }
        // cats dogs run run run run khaled
        $test_array = [];
        $array_keys = [];
        $k = 0;
        $l = 0;
        for($i = 0 ; $i < count($files_size) ; $i++)
        {
            $position = 0;
            $l = $l + $files_size[$i];
            for (; $k < $l; $k++){
                for ($j = $k; $j < $l; $j++){
                    if($tokens[$j] == $tokens[$k]){

                        array_push($test_array, $j);
                    }
                }
                if(!array_key_exists($tokens[$k], $array_keys)){
                    $array_keys[$tokens[$k]] = $i . ': ' . json_encode($test_array);
                }
//                echo $tokens[$k] . '=> ' . $i . ': ' . json_encode($test_array) . '<br>';
                $test_array = [];
            }


//            array_push($files_size ,str_word_count($this->files[$i]));
        }

dd($array_keys);

        dd('done');

        return $tokens;
    }
    public function getFiles() : array
    {

        $files  = [];

        for($i = 1 ; $i <= 10 ; $i++)
        {
            $file = fopen(storage_path('app/files/file_'. $i .'.txt'), "r");
            $file_content = fread($file,filesize(storage_path('app/files/file_' . $i .'.txt')));

            array_push($files , $file_content);

        }

        return $files;
    }

}
