<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\WhoWeAreController;
use App\Http\Controllers\MinistryController;
use App\Http\Controllers\DonationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::get('/news', [NewsController::class, 'index'])->name('news');
Route::get('/who-we-are', [WhoWeAreController::class, 'index'])->name('who-we-are');
Route::get('/ministry', [MinistryController::class, 'index'])->name('ministry');
Route::get('/donate', [DonationController::class, 'index'])->name('donate');
