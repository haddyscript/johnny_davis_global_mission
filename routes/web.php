<?php

use App\Http\Controllers\ChatbotController;
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
use App\Http\Controllers\Admin\DonationController as AdminDonationController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\NavItemController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\EmailLogController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PreviewController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


// Public page GET routes — blocked by nav.visibility if the matching nav item is hidden
Route::middleware('nav.visibility')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    Route::get('/news', [NewsController::class, 'index'])->name('news');
    Route::get('/who-we-are', [WhoWeAreController::class, 'index'])->name('who-we-are');
    Route::get('/ministry', [MinistryController::class, 'index'])->name('ministry');
    Route::get('/donate', [DonationController::class, 'index'])->name('donate');
});

// Non-page routes — never blocked by nav visibility
Route::post('/contact',             [ContactController::class, 'store'])->name('contact.store');
Route::post('/newsletter/subscribe',      [SubscriberController::class, 'store'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe',     [SubscriberController::class, 'unsubscribe'])->name('newsletter.unsubscribe')->middleware('signed');
Route::post('/donate/charge',                      [DonationController::class, 'charge'])->name('donate.charge');
Route::post('/donate/confirm',                     [DonationController::class, 'confirm'])->name('donate.confirm');
Route::post('/donate/paypal/order',                [DonationController::class, 'paypalOrder'])->name('donate.paypal.order');
Route::post('/donate/paypal/capture',              [DonationController::class, 'paypalCapture'])->name('donate.paypal.capture');
Route::post('/donate/paypal/subscription/create',  [DonationController::class, 'paypalCreateSubscription'])->name('donate.paypal.subscription.create');
Route::post('/donate/paypal/subscription/confirm', [DonationController::class, 'paypalConfirmSubscription'])->name('donate.paypal.subscription.confirm');
Route::post('/stripe/webhook',                     [DonationController::class, 'webhook'])->name('stripe.webhook');
Route::post('/paypal/webhook',                     [DonationController::class, 'paypalWebhook'])->name('paypal.webhook');
Route::post('/chatbot',                [ChatbotController::class, 'chat'])->name('chatbot.chat');
Route::post('/chatbot/stream',         [ChatbotController::class, 'stream'])->name('chatbot.stream');

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profile/avatar', [ProfileController::class, 'removeAvatar'])->name('profile.avatar.remove');
});

require __DIR__.'/auth.php';

// Admin routes
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::redirect('/home', 'dashboard');
    Route::resource('pages', PageController::class);
    Route::patch('pages/{page}/toggle', [PageController::class, 'toggle'])->name('pages.toggle');
    Route::resource('sections', SectionController::class);
    Route::resource('content-blocks', ContentBlockController::class);

    Route::get('preview/page/{slug}', [PreviewController::class, 'page'])->name('preview.page');

    Route::resource('contact-messages', ContactMessageController::class)->only(['index', 'show', 'destroy']);
    Route::post('contact-messages/{contactMessage}/reply', [ContactMessageController::class, 'reply'])
        ->name('contact-messages.reply');

    Route::get('subscribers/fetch', [AdminSubscriberController::class, 'fetch'])->name('subscribers.fetch');
    Route::post('subscribers/bulk-email', [AdminSubscriberController::class, 'bulkEmail'])->name('subscribers.bulk-email');
    Route::resource('subscribers', AdminSubscriberController::class)->only(['index', 'destroy']);
    Route::patch('subscribers/{subscriber}/toggle', [AdminSubscriberController::class, 'toggleActive'])
        ->name('subscribers.toggle');
    Route::patch('contact-messages/{contactMessage}/toggle-read', [ContactMessageController::class, 'toggleRead'])
        ->name('contact-messages.toggle-read');

    Route::get('donations/export', [AdminDonationController::class, 'export'])->name('donations.export');
    Route::post('donations/{donation}/followup', [AdminDonationController::class, 'followup'])->name('donations.followup');
    Route::resource('donations', AdminDonationController::class)->only(['index', 'show']);
    Route::resource('email-templates', EmailTemplateController::class);
    Route::patch('email-templates/{emailTemplate}/toggle', [EmailTemplateController::class, 'toggle'])
        ->name('email-templates.toggle');
    Route::get('email-templates/{emailTemplate}/preview', [EmailTemplateController::class, 'preview'])
        ->name('email-templates.preview');
    Route::post('email-templates/preview-render', [EmailTemplateController::class, 'previewRender'])
        ->name('email-templates.preview-render');

    Route::resource('email-logs', EmailLogController::class)->only(['index', 'show', 'destroy']);

    Route::resource('campaigns', CampaignController::class)->except(['show']);
    Route::patch('campaigns/{campaign}/toggle', [CampaignController::class, 'toggle'])->name('campaigns.toggle');

    // Navigation items
    Route::prefix('nav-items')->name('nav-items.')->group(function () {
        Route::get('/',                          [NavItemController::class, 'index'])->name('index');
        Route::post('/',                         [NavItemController::class, 'store'])->name('store');
        Route::put('/{navItem}',                 [NavItemController::class, 'update'])->name('update');
        Route::delete('/{navItem}',              [NavItemController::class, 'destroy'])->name('destroy');
        Route::patch('/{navItem}/toggle',        [NavItemController::class, 'toggle'])->name('toggle');
        Route::post('/reorder',                  [NavItemController::class, 'reorder'])->name('reorder');
    });

    // Admin user management
    Route::resource('admins', AdminUserController::class)->only(['index', 'store', 'update', 'destroy']);

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/',                       [NotificationController::class, 'index'])->name('index');
        Route::get('/recent',                 [NotificationController::class, 'recent'])->name('recent');
        Route::get('/unread-count',           [NotificationController::class, 'unreadCount'])->name('unread-count');
        Route::patch('/{notification}/read',  [NotificationController::class, 'markRead'])->name('mark-read');
        Route::post('/mark-all-read',         [NotificationController::class, 'markAllRead'])->name('mark-all-read');
    });
});
