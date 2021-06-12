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

Route::get('/', function () {
    return view('auth.login');
});
Route::get('/login', function () {
    return view('auth.login');
});
Auth::routes();
Route::post('/v1/login', 'Controller@login');
Route::post('/v1/register', 'Auth\RegisterController@create');
Route::get('/v1/logout', 'Controller@postLogout');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/story', 'StoryController@index')->name('story');
Route::get('/addstory', 'StoryController@addstory')->name('addstory');
Route::post('/savestory', 'StoryController@saveStory')->name('saveStroy');
Route::get('/partStory/{id}', 'partStoryController@index')->name('listpartStory');
Route::get('/addpartStory/{id}', 'partStoryController@addpartStory')->name('partStory');
Route::post('/savepartstory', 'partStoryController@savepartStory')->name('savepartsotry');
Route::get('/editpartstory/{id}', 'partStoryController@getEditPartStory')->name('editpartstory');
Route::post('/updatepartstory', 'partStoryController@updatepartStory')->name('updatepartStory');
Route::get('/deleteStory/{id}', 'StoryController@deleteStory')->name('updatepartStory');
Route::get('/deletePartStory/{storyID}/{id}', 'partStoryController@deletePartStory')->name('updatepartStory');



Route::get('/editStory/{id}', 'StoryController@getEditStory')->name('editstory');
Route::post('/UpdateStories', 'StoryController@updateStory')->name('updateStory');

//
Route::get('/recommended', 'StoryController@recomended')->name('recommended');
Route::get('/deleterecomended/{storyId}/{id}', 'StoryController@deleterecomended')->name('deleterecomended');
Route::get('/addRecomended', 'StoryController@addRecomended')->name('recommended');
Route::post('/saveRecomended/{storyId}/{id}', 'StoryController@SaveRecomended')->name('save-recommended');

Route::get('/banner', 'StoryController@ViewBanner')->name('ViewBanner');
Route::get('/addbanner', 'StoryController@addBanner')->name('addBanner');
Route::post('/savebanner', 'StoryController@SaveBanner')->name('SaveBanner');
Route::get('/deletebanner/{id}', 'StoryController@deleteBanner')->name('deleteBanner');

//best
Route::get('/best', 'StoryController@bestStories')->name('bestStories');
Route::get('/deleteBest/{storyId}/{id}', 'StoryController@deletebest')->name('deletebest');
Route::get('/addBest', 'StoryController@addBest')->name('addBest');
Route::post('/saveBest/{storyId}/{id}', 'StoryController@SaveBest')->name('SaveBest');


//category
Route::get('/category', 'CategoryController@index')->name('catetgory');
Route::get('/addcategory', 'CategoryController@add')->name('add');
Route::post('/savecategory', 'CategoryController@save')->name('save');
Route::get('/category/{id}', 'CategoryController@edit')->name('edit');
Route::post('/categoryUpdate', 'CategoryController@update')->name('update');

