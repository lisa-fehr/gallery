<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use LisaFehr\Gallery\Http\Controllers\GalleryController;
use LisaFehr\Gallery\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

// /gallery?filter[tags]=California2014
Route::get('/gallery', GalleryController::class);
Route::get('/tags/{filters?}', TagController::class);

Route::pattern('path', '.*$');
Route::get('/gallery/temp/{path}', function (string $path) {
    $expires = request()->input('expires', Carbon::now()->getTimestamp());
    if (Carbon::createFromTimestamp($expires)->isPast()) {
        abort(404, "Image Expired");
    }
    return Storage::disk('gallery')->download($path);
})->name('gallery.temp');
