<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/story', 'MobileController@GetAllDataStory');
Route::get('/storypart/{id}', 'MobileController@GetDataStoryPart');
Route::get('/storydetail', 'MobileController@GetDataStoryDetail');
Route::get('/recommended', 'MobileController@Recommended');
Route::get('/popular', 'MobileController@Popular');
Route::get('/banners', 'MobileController@Banners');
Route::post('/comment', 'MobileController@PostComment');
Route::get('/comment/{id}', 'MobileController@GetComment');
Route::post('/like', 'MobileController@PostLike');
Route::get('/like/{id}', 'MobileController@CountLike');
Route::get('/story/category/{id}', 'MobileController@GetdataKategory');
Route::get('/story/thebest', 'MobileController@GetDataStoryBest');
Route::get('/story/foryou', 'MobileController@GetDataStoryforyou');
Route::get('/story/forslideleft/{id}', 'MobileController@GetDataSlideLeft');
Route::get('/comment/{story}/{id}', 'MobileController@GetCommentPart');
Route::post('/favorites', 'MobileController@SaveFavorite')->middleware('jwt.verify');
Route::get('/favorites', 'MobileController@GetFavoriteByUserId')->middleware('jwt.verify');
Route::post('/favorites/delete', 'MobileController@deleteFavorites')->middleware('jwt.verify');

Route::get('/onePopular', 'MobileController@OnePopular');
Route::get('/gettopcomment', 'MobileController@GetTopCommentByDate');
Route::post('/favorites/parent', 'MobileController@SaveFavoriteParrent')->middleware('jwt.verify');
Route::post('/favorites/parent/delete', 'MobileController@deleteFavoritesParent')->middleware('jwt.verify');

Route::get('/search', 'MobileController@searchByTitle');


//images
Route::get('/image/{filename}', 'ImageController@image');

//auth
Route::post('register', 'AuthApi@register');
Route::post('handleCallback', 'AuthApi@handleCallback');
Route::get('refresh', 'AuthApi@refresh');
