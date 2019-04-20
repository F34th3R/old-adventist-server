<?php

use Illuminate\Http\Request;

Route::group(['prefix' => '/auth'], function () {
    // Route::post('login', 'AuthController@login');
    Route::post('login', 'Auth\AccessTokenController@issueToken');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});

// Route::group(['prefix'  =>  '/advertisement'], function () {
//     //? Show all the advertisements
//     Route::get('/','AdvertisementController@index');

//     //? search-filter for select whish advertisement is display
//     Route::get('/filter/{user_id}','AdvertisementController@filter');
    
//     //? Display advertisment from the filter selection
//     Route::get('/show/{department_id}','AdvertisementController@show');
// });

//! Unions
Route::group(['middleware'  =>  ['auth:api']], function () {
    Route::group(['prefix'  =>  '/unions'], function () {
        Route::get('/','UnionController@index');
        Route::get('/{id}', 'UnionController@show');
        Route::post('/', 'UnionController@store');
        Route::put('/{id}', 'UnionController@update');
        // Route::put('/{id}', array('middleware' => 'cors', 'uses' => 'UnionController@update'));
        Route::delete('/{id}', 'UnionController@destroy');
    });
});

//! Groups
Route::group(['middleware'  =>  ['auth:api']], function () {
    Route::group(['prefix'  =>  '/groups'], function () {
        Route::get('/', 'GroupController@index');
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

//Advertisements
Route::group(['middleware'  =>  ['auth:api']], function () {
    Route::group(['prefix'  =>  '/advertisements'], function () {
        Route::get('/', 'AdvertisementController@index');
        Route::get('/{user_id}', 'AdvertisementController@indexFromParams');
        Route::post('', 'AdvertisementController@store');
        Route::put('/{id}', 'AdvertisementController@update');
        Route::delete('/{id}', 'AdvertisementController@destroy');
    });
    Route::get('advertisement/{id}', 'AdvertisementController@show');
    Route::post('advertisement/feather', 'AdvertisementController@searchAdvertisementByTypeAndId');
});

// Route::group(['prefix'  =>  '/union'], function () {
//     Route::get('/name_code','UnionController@nameAndCode');
//     Route::get('/name_code_user/{user_id}','UnionController@nameAndCodeByUser');
// });

// Route::group(['prefix'  =>  '/group'], function () {
//     //? Display the names and codes from the migration
//     Route::get('/name_code','GroupController@nameAndCode');
//     //? Display the same where user_id is like the input
//     Route::get('/name_code_user/{user_id}','GroupController@nameAndCodeByUser');
//     //? the same but union_id
//     Route::get('/name_code_union/{union_id}','GroupController@nameAndCodeByUnion');
// });

// Route::group(['prefix'  =>  '/church'], function () {
//     //? is the same of the group api route
//     Route::get('/name_code','ChurchController@nameAndCode');
//     Route::get('/name_code_user/{user_id}','ChurchController@nameAndCodeByUser');
//     Route::get('/name_code_group/{group_id}','ChurchController@nameAndCodeByGroup');
// });

// Route::group(['prefix'  =>  '/department'], function () {
//     Route::get('/name_code','DepartmentController@nameAndCode');
// });
