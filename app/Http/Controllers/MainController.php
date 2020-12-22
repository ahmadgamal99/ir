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



        $tokenFrequencies = array_count_values(($tokensFlatten));



        return view('inverted-index',compact('tokenFrequencies','docsNo'));

    }


    public function stopWordRemoval()
    {
        $tokens = [];


        for($i = 0 ; $i <= 9 ; $i++)
        {
            $token = strtok($this->files[$i] , " \n\t");

            while ($token !== false)
            {
                in_array($token,self::$stopWordList) ?:  $tokens[$i][$token] = $i + 1;
                $token = strtok(" \n\t");
            }
        }

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
