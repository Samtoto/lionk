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
use App\Comment;
use Illuminate\Http\Request;

Route::prefix('blog')->group(function () {

    Route::get('/add', function () {
        return view('add');
    })->middleware('auth');
    Route::post('/add', 'BlogController@add');
    Route::post('/addImg', 'BlogController@addImg');
    Route::get('/all', 'BlogController@all');

    Route::get('/show/{blog_id}', 'BlogController@show')->where(['blog_id' => '[0-9]+']);
    Route::get('/{blog_id}/edit', 'BlogController@edit')->where(['blog_id' => '[0-9]+']);
    Route::put('/{blog_id}', 'BlogController@update')->where(['blog_id' => '[0-9]+']);
    Route::delete('/{blog_id}', 'BlogController@delete')->where(['blog_id' => '[0-9]+']);

    Route::get('/{blog}/comment', 'CommentController@index')->where(['blog' => '[0-9]+']);
});

Route::prefix('comment')->group(function () {
    Route::get('/show/{blog_id}', 'CommentController@show')->where(['blog_id' => '[0-9]+']);
    Route::post('/add', 'CommentController@add');
    Route::post('/add_sub', 'CommentController@addSub');
    Route::get('/{comment_id}/edit', 'CommentController@edit')->where(['comment_id' => '[0-9]+']);
    Route::put('/{comment_id}', 'CommentController@update')->where(['comment_id' => '[0-9]+']);
    Route::delete('/{comment_id}', 'CommentController@delete')->where(['comment_id' => '[0-9]+']);
});

Route::prefix('community')->group(function () {
    Route::get('/', function () {
        return view('communities');
    })->name('communities');

    Route::get('/change_status/{community_id}', 'CommunityController@changeStatus')->where(['community_id', '[0-9]+'])->middleware('auth');
    Route::get('/all', 'CommunityController@all');
    Route::get('/my', 'CommunityController@my');
    Route::get('/{community_id}', 'CommunityController@show')->where(['community_id', '[0-9]+']);
});

Route::prefix('user')->group(function () {
    Route::get('/profile', 'UserProfileController@show');
});

Route::get('/', function() {
    return view('show');
})->name('blogs');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
