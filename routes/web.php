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

    Route::get('/create', function () {
        return view('blog.create');
    })->middleware('auth');
    Route::post('/create', 'BlogController@create');
    Route::post('/createImg', 'BlogController@createImg');
    Route::get('/index', 'BlogController@index');

    Route::get('/{blog}', 'BlogController@show')->where(['blog' => '[0-9]+']);
    Route::get('/{blog}/edit', 'BlogController@edit')->where(['blog' => '[0-9]+']);
    Route::put('/{blog}', 'BlogController@update')->where(['blog' => '[0-9]+']);
    Route::delete('/{blog}', 'BlogController@destroy')->where(['blog' => '[0-9]+']);

    Route::get('/{blog}/comment', 'CommentController@index')->where(['blog' => '[0-9]+']);
});

Route::prefix('comment')->group(function () {
    Route::get('/show/{blog}', 'CommentController@show')->where(['blog' => '[0-9]+']);
    // Route::post('/add', 'CommentController@add');
    Route::post('/create', 'CommentController@create');
    Route::get('/{comment}/edit', 'CommentController@edit')->where(['comment' => '[0-9]+']);
    Route::put('/{comment}', 'CommentController@update')->where(['comment' => '[0-9]+']);
    Route::delete('/{comment}', 'CommentController@destroy')->where(['comment' => '[0-9]+']);
});

Route::prefix('community')->group(function () {
    Route::get('/', function () {
        return view('community.index');
    })->name('communities');

    Route::get('/{community}/subscribe', 'CommunityController@subscribe')->where(['community', '[0-9]+'])->middleware('auth');
    Route::get('/index', 'CommunityController@index');
    Route::get('/my', 'CommunityController@my');
    Route::get('/{community}', 'CommunityController@show')->where(['community', '[0-9]+']);
});

Route::prefix('user')->group(function () {
    Route::get('/profile', 'UserProfileController@show');
});

Route::get('/', function() {
    return view('blog.index');
})->name('blogs');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
