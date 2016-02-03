<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 */

Route::get('/', ['as' => 'homepage', 'uses' => 'HomepageController@index']);
Route::get('/dashboard', ['as' => 'dashboard', 'middleware' => 'auth', 'uses' => 'HomepageController@dashboard']);
/**
 * Authentication and Registration Process
 */
Route::get('/signin', ['as' => 'signin.index', 'uses' => 'Auth\AuthController@getLogin']);
Route::post('/signin', ['as' => 'user.signin', 'uses' => 'Auth\AuthController@postLogin']);
Route::get('/signout', ['as' => 'user.signout', 'uses' => 'Auth\AuthController@getLogout']);
Route::get('/signup', ['as' => 'signup.index', 'uses' => 'Auth\AuthController@getRegister']);
Route::post('/signup', ['as' => 'signup.store', 'uses' => 'Auth\AuthController@postRegister']);

Route::group(['prefix' => 'api'], function () {
    Route::get('/links', ['as' => 'links.index', 'middleware' => 'auth', 'uses' => 'Api\LinksController@index']);
    Route::get('/links/{short_url}', ['as' => 'links.show', 'uses' => 'Api\LinksController@show']);
    Route::post('/links', ['as' => 'links.store', 'uses' => 'Api\LinksController@store']);
});

Route::get('/{any}', ['as' => 'shorten.redirect', 'uses' => 'HomepageController@getRedirect']);
