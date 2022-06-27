<?php

use LisaFehr\Gallery\Http\Controllers\GalleryController;
use LisaFehr\Gallery\Http\Controllers\TagController;
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

Route::get('/gallery', GalleryController::class);
// /gallery?filter[tags]=California2014
Route::get('/tags/{filters?}', TagController::class);
