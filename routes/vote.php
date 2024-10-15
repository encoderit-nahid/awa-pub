<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::group(['domain' => 'voting.austrianweddingaward.at'], function () {

    Route::get('/', 'PublicVoteController@index')->name('vote');
    Route::get('vote-login', 'Auth\LoginController@showVoteLoginForm')->name('vote-login');
    Route::get('vote-register', 'Auth\RegisterController@showVoteRegisterForm')->name('vote-register');
    Route::get('contact', 'PublicVoteController@contact')->name('contact');

    Route::post('vote-login', [
        'as' => 'login',
        'uses' => 'Auth\LoginController@login'
    ]);
    
    Route::post('vote-register', [
        'as' => 'register',
        'uses' => 'Auth\RegisterController@register'
    ]);
    
    Route::group(['middleware' => ['web', 'vote-auth']], function () {
        Route::get('/votes/{cat_id?}', 'PublicVoteController@vote')->name('votes');
        Route::get('/vote-score', 'PublicVoteController@voteScore')->name('vote-score');
        Route::post('/public-project-rated', 'PublicVoteController@PublicProjectRated')->name('public-project-rated');

        Route::post('vote-logout', 'Auth\LoginController@logout')->name('vote-logout');

        Route::get('/storage-link', function () {
            Artisan::call('storage:link');
        });
    });
});