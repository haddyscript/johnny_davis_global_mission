<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\SubscriberController as AdminSubscriberController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\WhoWeAreController;
use App\Http\Controllers\MinistryController;
use App\Http\Controllers\DonationController;

use App\Http\Controllers\Admin\ContentBlockController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/contact',  [ContactController::class, 'index'])->name('contact');
Route::post('/contact',             [ContactController::class, 'store'])->name('contact.store');
Route::post('/newsletter/subscribe', [SubscriberController::class, 'store'])->name('newsletter.subscribe');
Route::get('/news', [NewsController::class, 'index'])->name('news');
Route::get('/who-we-are', [WhoWeAreController::class, 'index'])->name('who-we-are');
Route::get('/ministry', [MinistryController::class, 'index'])->name('ministry');
Route::get('/donate', [DonationController::class, 'index'])->name('donate');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Admin routes
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', 'pages');
    Route::resource('pages', PageController::class);
    Route::patch('pages/{page}/toggle', [PageController::class, 'toggle'])->name('pages.toggle');
    Route::resource('sections', SectionController::class);
    Route::resource('content-blocks', ContentBlockController::class);

    Route::resource('contact-messages', ContactMessageController::class)->only(['index', 'show', 'destroy']);

    Route::resource('subscribers', AdminSubscriberController::class)->only(['index', 'destroy']);
    Route::patch('subscribers/{subscriber}/toggle', [AdminSubscriberController::class, 'toggleActive'])
        ->name('subscribers.toggle');
    Route::patch('contact-messages/{contactMessage}/toggle-read', [ContactMessageController::class, 'toggleRead'])
        ->name('contact-messages.toggle-read');
});
