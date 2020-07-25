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

use App\Community;

Route::prefix('blog')->group(function () {

    Route::get('/add', function () {
        return view('add');
    });
    Route::post('/add', 'BlogController@add');
    Route::get('/all', 'BlogController@all');

    Route::get('/show/{blog_id}', 'BlogController@show')->where(['blog_id' => '[0-9]+']);
});

Route::prefix('comment')->group(function () {
    Route::get('/show/{blog_id}', 'CommentController@show')->where(['blog_id' => '[0-9]+']);
    Route::post('/add', 'CommentController@add');
    Route::post('/add_sub', 'CommentController@addSub');
});

Route::prefix('community')->group(function () {
    Route::get('/', function () {
        return view('communities');
    })->name('communities');

    Route::get('/change_status/{community_id}', 'CommunityController@changeStatus')->where(['community_id', '[0-9]+']);
    Route::get('/all', 'CommunityController@all');
    Route::get('/my', 'CommunityController@my');
});


Route::get('/', function() {
    return view('show');
})->name('blog');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/my/config', 'UserInfoController@config');