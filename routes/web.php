<?php

use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UpcomingTripsController;
use App\Http\Controllers\UpcomingTripsParticipantsController;
use Illuminate\Support\Facades\Route;




Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('posts', PostController::class)
    ->only(['index', 'store', 'edit', 'update', 'destroy'])
    ->middleware(['auth', 'verified']);

Route::resource('comments', CommentController::class)
    ->only(['destroy'])
    ->middleware(['auth', 'verified']);

Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->middleware('auth')->name('posts.comments.store');

Route::post('/posts/{post}/like', [PostLikeController::class, 'toggle'])->middleware('auth')->name('posts.like.toggle');

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])->middleware('auth', 'admin')->name('contacts.destroy');

Route::get('/dashboard', [UpcomingTripsController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('upcoming-trips.index');

Route::post('/dashboard', [UpcomingTripsController::class, 'store'])->middleware(['auth', 'admin'])->name('upcoming-trips.store');

Route::get('/upcoming-trips.create', function () {
    return view('upcoming-trips.create');
})->middleware(['auth', 'verified', 'admin'])->name('upcoming-trips.create');

Route::delete('/dashboard/{trip}', [UpcomingTripsController::class, 'destroy'])->middleware('auth', 'admin')->name('trip.destroy');

Route::post('/dashbord/{trip}/joinTrip', [UpcomingTripsParticipantsController::class, 'joinTrip'])->middleware('auth')->name('trip.join');
Route::post('/dashboard/{trip}/leaveTrip', [UpcomingTripsParticipantsController::class, 'leaveTrip'])->middleware('auth')->name('trip.leave');

Route::get('/contacts.response', function () {
    return view('contacts.response');
})->middleware(['auth', 'admin'])->name('contacts.response');

//Route::post('/emails.response-email', [EmailController::class, 'sendResponse'])->name('email.response-email');

require __DIR__ . '/auth.php';
