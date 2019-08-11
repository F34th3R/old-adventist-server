<?php

Route::group(['prefix' => '/auth'], function () {
    //* Login
    Route::post('login', 'Auth\AccessTokenController@issueToken');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

//! Unions
Route::group(['middleware'  =>  ['auth:api']], function () {
    Route::group(['prefix'  =>  '/unions'], function () {
        Route::get('/','UnionController@index');
        Route::get('/{id}', 'UnionController@show');
        Route::post('/', 'UnionController@store');
        Route::put('/{id}', 'UnionController@update');
        Route::delete('/{id}', 'UnionController@destroy');
    });
});

//! Groups
Route::group(['middleware'  =>  ['auth:api']], function () {
    Route::group(['prefix'  =>  '/groups'], function () {
        Route::get('/', 'GroupController@index');
        // TODO delete this route! after update client
        Route::get('/{id}', 'GroupController@indexFromParams');
        Route::post('/', 'GroupController@store');
        Route::put('/{id}', 'GroupController@update');
        Route::delete('/{id}', 'GroupController@destroy');
    });
    Route::get('group/{id}', 'GroupController@show');
});

//! Churches
Route::group(['middleware'  =>  ['auth:api']], function () {
    Route::group(['prefix'  =>  '/churches'], function () {
        Route::get('/', 'ChurchController@index');
        Route::get('/{id}', 'ChurchController@indexFromParams');
        Route::post('/', 'ChurchController@store');
        Route::put('/{id}', 'ChurchController@update');
        Route::delete('/{id}', 'ChurchController@destroy');
    });
    Route::get('church/{id}', 'ChurchController@show');
});

//! Departments
Route::group(['middleware'  =>  ['auth:api']], function () {
    Route::group(['prefix'  =>  '/departments'], function () {
        Route::get('/', 'DepartmentController@index');
        Route::get('/{user_id}', 'DepartmentController@indexFromParams');
        Route::post('/', 'DepartmentController@store');
        Route::put('/{id}', 'DepartmentController@update');
        Route::delete('/{id}', 'DepartmentController@destroy');
    });
    Route::get('department/{id}', 'DepartmentController@show');
});

// ! News
Route::group(['middleware'  =>  ['auth:api']], function () {
    Route::group(['prefix'  =>  '/news'], function () {
        Route::get('/', 'AdvertisementController@getAdvertisements');
        Route::post('/', 'AdvertisementController@store');
        Route::put('/{id}', 'AdvertisementController@update');
        Route::delete('/{id}', 'AdvertisementController@destroy');
    });
    Route::post('advertisement', 'AdvertisementController@show');
});

Route::group(['middleware'  =>  ['auth:api']], function () {
    Route::group(['prefix'  =>  '/settings'], function () {
        Route::post('/change/username', 'UserController@changeUsername');
        Route::post('/change/password', 'UserController@changePassword');
        Route::post('/change/email', 'UserController@changeEmail');
    });
});

Route::group(['middleware'  =>  ['auth:api']], function () {
    Route::group(['prefix'  =>  '/comments'], function () {
        Route::get('/list', 'CommentController@index');
//        Route::get('/comments/user', 'CommentController@userComments');
        Route::post('/store', 'CommentController@store');
//        Route::put('/comments/{id}', 'CommentController@updateComment');
//        Route::delete('/comments{id}', 'CommentController@deleteComments');
    });
});

Route::group(['prefix'  =>  '/search'], function () {
    Route::post('/advertisements', 'FeatherController@searchAdvertisement');
    Route::post('/users', 'FeatherController@searchUsers');
});

Route::group(['prefix'  =>  '/public'], function () {
    //? show all advertisement
    Route::get('/adv', 'PublicController@getAdvertisements');
    //? show one advertisement by code
    Route::post('/adv/show', 'PublicController@showAdvertisement');
    //? show one [Union, Group, church] by code
    Route::post('/adv/belongs', 'PublicController@belongsAdvertisements');
    //? show all the following by user's codes
    Route::post('/adv/favorite', 'PublicController@favoriteAdvertisement');
    //? show all the bookmarks by advertisement's code
    Route::post('/adv/bookmarks', 'PublicController@bookmarksAdvertisement');
    Route::post('/adv/search', 'PublicController@searchAdvertisement');
    Route::post('/adv/search/user', 'PublicController@searchUserAdvertisement');
});
