<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',function(){
    return view('tokenization');
});

Route::get('/tokenization','TokenizerController@viewTokens');
Route::get('/Stop-word-removal','StopWordRemovalController@stopWordRemovalView');
Route::get('/positional_index_model','PositionalIndexController@buildModel');
Route::get('/do-query','MainController@query');
Route::post('/do-query','MainController@queryResult');
