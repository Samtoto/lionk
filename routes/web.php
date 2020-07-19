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

// Route::get('/', function () {
//     return view('welcome');
// });

use App\Community;

Route::get('/add', function() {
    // Debugbar::info('hello debugbar');
    // Debugbar::error('debugbar::error');
    return view('add');
});

Route::post('/add', 'BlogController@add');

Route::get('/', function() {
    return view('show');
});

Route::get('/showBlogs', 'BlogController@show');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/my/config', 'UserInfoController@config');

Route::get('/reply/{blog_id}', 'CommentController@show')->where(['blog_id' => '[0-9]+']);

Route::get('/showComments/{blog_id}', 'CommentController@showComment')->where(['blog_id' => '[0-9]+']);
Route::post('/comment/add', 'CommentController@add');

Route::get('/communities', function() {
    return view('communities');
});

Route::get('/community/change_status/{community_id}', 'CommunityController@changeStatus')->where(['community_id', '[0-9]+']);

Route::get('/test', function() {
    return view('test');
});

Route::get('/communities/all', 'CommunityController@all');

Route::get('/community/my', 'CommunityController@my');

Route::get('/community/add', function() {
    $communities = [
        'sports',
        'Futurology',
        'yesyesyesyesno',
        'pics',
        'LifeProTips',
        'entertainment',
        'Cringetopia',
        'ToiletPaperUSA',
        'WinStupidPrizes',
        'movies',
        'MMA',
        'funny',
        'Jokes',
        'videos',
        'clevercomebacks',
        'television',
        'IAmA',
        'MadeMeSmile',
        'ThatsInsane',
        'UnethicalLifeProTips',
        'whatisthisthing',
        'AskReddit',
        'gifs',
        'nba',
        'nextfuckinglevel'
    ];
    // foreach ($communities as $community) {
    //     $communityObj = new Community;
    //     $communityObj->name = $community;
    //     $communityObj->save();
    // }
    return 'done!';
    
});





