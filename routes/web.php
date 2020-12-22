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

Route::get('/tokenization','MainController@tokenization');
Route::get('/Stop-word-removal','MainController@stopWordRemovalView');
Route::get('/inverted-index','MainController@buildInvertedIndex');
Route::get('/do-query','MainController@query');
Route::post('/do-query','MainController@queryResult');
