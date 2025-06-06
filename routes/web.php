<?php

// Keep your existing 'use' statements
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TripParticipants;
use App\Http\Controllers\UpcomingTripsController;
use App\Http\Controllers\UpcomingTripsParticipantsController;
use App\Http\Controllers\EmailController;
use App\Models\Comment;
use App\Models\UpcomingTrips;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});

// Your existing Profile routes are correct
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Your existing Post resource route is correct
Route::resource('posts', PostController::class)
    ->only(['index', 'store', 'edit', 'update', 'destroy'])
    ->middleware(['auth', 'verified']);

// Your existing Comment destroy route is correct
Route::resource('comments', CommentController::class)
    ->only(['destroy'])
    ->middleware(['auth', 'verified']);

// Your existing Comment store route is correct. We will use this name.
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->middleware('auth')->name('posts.comments.store');

// --- ADJUSTMENT ---
// Replaced the separate 'like' and 'unlike' routes with a single 'toggle' route.
// This is a cleaner approach for AJAX and simplifies the controller and frontend logic.
Route::post('/posts/{post}/like', [PostLikeController::class, 'toggle'])->middleware('auth')->name('posts.like.toggle');
// The old routes that will be replaced:
// Route::post('/posts/{post}/like', [PostLikeController::class, 'like'])->middleware('auth')->name('posts.like');
// Route::post('/posts/{post}/unlike', [PostLikeController::class, 'unlike'])->middleware('auth')->name('posts.unlike');


// Your other routes remain unchanged
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