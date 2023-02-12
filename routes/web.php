<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ContactNoteController;

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

Route::get('/', WelcomeController::class);

// grouped contact routes
Route::controller(ContactController::class)->group(function() {
    Route::get('/contacts', 'index')->name('contacts.index');
    Route::get('/contacts/create', 'create')->name('contacts.create');
    Route::get('/contacts/{id}', 'show')->name('contacts.show');
});

// nested resourses (left side of dot notation is parent resouce name, right side is the child resource)
Route::resource('/contacts.notes', ContactNoteController::class);
// ALSO SEEMS TO BE REMOVED IN VERSION 9
// resource route (for a resource controller) prevents calling them one by one
Route::resource('/companies', CompanyController::class)->shallow();

// THIS FEATURE SEEMS TO BE REMOVED IN LARAVEL 9.5
// partial resource routes (pass routes to want to include) (can also use ->except() for reverse effect)
// Route::resource('activities', ActivityController::class)-only([
//     'create', 'store', 'edit', 'update', 'destroy'
// ]);
// Route::resource('pactivitieshotos', ActivityController::class)->except([
//     'index', 'show'
// ]);
// Route::only(['create', 'store', 'edit', 'update', 'destroy'])->resource('activities', ActivityController::class);



// adding a route to a resource controller (MUST BE ADDED BEFORE CALLING THE RESOURCE ROUTE)
Route::get('/tags/testing', [TagController::class, 'testing']);
// listing multiple resource controllers
Route::resources([
    '/tags' => TagController::class,
    '/tasks' => TaskController::class
]);


Route::fallback(function() {
    return "<h1>Sorry, the page does not exist</h1>";
});
