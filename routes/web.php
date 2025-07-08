<?php

use App\Routes\Route;

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');
Route::get('/home/index', 'HomeController@index');

// Post routes
Route::get('/posts', 'PostController@index');
Route::get('/post/show', 'PostController@show');
Route::get('/post/create', 'PostController@create');
Route::post('/post/store', 'PostController@store');
Route::get('/post/edit', 'PostController@edit');
Route::post('/post/edit', 'PostController@update');
Route::post('/post/delete', 'PostController@delete');

// Comment routes
Route::post('/comment/store', 'CommentController@store');
Route::post('/comment/delete', 'CommentController@delete');

// Category routes
Route::get('/categories', 'CategoryController@index');
Route::get('/category/show', 'CategoryController@show');
Route::get('/category/create', 'CategoryController@create');
Route::post('/category/store', 'CategoryController@store');
Route::get('/category/edit', 'CategoryController@edit');
Route::post('/category/edit', 'CategoryController@update');
Route::post('/category/delete', 'CategoryController@delete');

// User routes
Route::get('/user/create', 'UserController@create');
Route::post('/user/create', 'UserController@store');

// Admin user creation routes
Route::get('/user/admin-create', 'UserController@adminCreate');
Route::post('/user/admin-create', 'UserController@adminStore');

// Auth routes
Route::get('/login', 'AuthController@index');
Route::post('/login', 'AuthController@store');
Route::get('/logout', 'AuthController@delete');

// Activity Log routes (Admin only)
Route::get('/activity-logs', 'ActivityLogController@index');
Route::get('/activity-logs/clear', 'ActivityLogController@clear');
Route::post('/activity-logs/clear', 'ActivityLogController@clear');

// Language routes
Route::get('/language/switch', 'LanguageController@switch');

Route::dispatch();
