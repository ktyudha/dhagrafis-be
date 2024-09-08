<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ArtworkController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\LatestArticleController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\Website\Home\HomeController;
use App\Http\Controllers\Admin\Gallery\ImageController;
use App\Http\Controllers\Admin\Slider\SliderController;
use App\Http\Controllers\Website\About\AboutController;
use App\Http\Controllers\Website\Promo\PromoController;
use App\Http\Controllers\Admin\Service\ServiceController;
use App\Http\Controllers\Website\Contact\ContactController;
use App\Http\Controllers\Website\Katalog\KatalogController;
use App\Http\Controllers\Admin\Jobs\JobsManagementController;
use App\Http\Controllers\Admin\About\AboutManagementController;
use App\Http\Controllers\Admin\Keunggulan\KeunggulanController;
use App\Http\Controllers\Admin\Service\PromoManagementController;
use App\Http\Controllers\Admin\SocialMedia\SocialMediaController;
use App\Http\Controllers\Admin\Contact\ContactManagementController;
use App\Http\Controllers\Admin\Katalog\KatalogManagementController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\ArtworkController as AdminArtworkController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Website\Jobs\JobsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')->group(function () {
    Route::get('me', [AuthController::class, 'me'])
        ->middleware(['auth:sanctum', 'verified'])
        ->name('auth.me');
    // Route::post('register', [AuthController::class, 'register'])
    //     ->middleware(['guest'])
    //     ->name('auth.register');
    Route::post('login', [AuthController::class, 'login'])
        ->middleware(['throttle:6,1', 'guest'])
        ->name('auth.login');
    // Route::post('logout', [AuthController::class, 'logout'])
    //     ->middleware(['auth:sanctum', 'verified'])
    //     ->name('auth.logout');

    Route::post('profile', [AuthController::class, 'profile'])->name('auth.profile');

    // Route::post('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    //     ->middleware(['throttle:6,1'])
    //     ->name('verification.verify');
    // Route::post('email/verification-notification/{id}', [EmailVerificationController::class, 'notification'])
    //     ->middleware(['throttle:6,1'])
    //     ->name('verification.notification');
});

// Route::get('articles', [ArticleController::class, 'index'])->name('articles.index');
// Route::get('articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');

// Route::get('events', [EventController::class, 'index'])->name('events.index');
// Route::get('events/{slug}', [EventController::class, 'show'])->name('events.show');

// Route::get('artworks', [ArtworkController::class, 'index'])->name('artworks.index');
// Route::get('artworks/{slug}', [ArtworkController::class, 'show'])->name('artworks.show');

// Route::get('categories', CategoryController::class)->name('categories');
// Route::get('latest-articles', LatestArticleController::class)->name('articles.latest');

Route::prefix('admin')->middleware('auth:sanctum')->name('admin.')->group(function () {
    // Route::apiResource('categories', AdminCategoryController::class);
    // Route::apiResource('articles', AdminArticleController::class)
    //     ->scoped(['article' => 'slug']);
    // Route::prefix('articles')->name('articles.')->group(function () {
    //     Route::put('{article:slug}/publish', [AdminArticleController::class, 'publish'])->name('publish');
    //     Route::put('{article:slug}/unpublish', [AdminArticleController::class, 'unpublish'])->name('unpublish');
    // });

    // Route::apiResource('artworks', AdminArtworkController::class)
    //     ->scoped(['artwork' => 'slug']);
    // Route::prefix('artworks')->name('artworks.')->group(function () {
    //     Route::put('{artwork:slug}/publish', [AdminArtworkController::class, 'publish'])->name('publish');
    //     Route::put('{artwork:slug}/unpublish', [AdminArtworkController::class, 'unpublish'])->name('unpublish');
    // });

    // Route::apiResource('events', AdminEventController::class)
    //     ->scoped(['event' => 'slug']);
    // Route::prefix('events')->name('events.')->group(function () {
    //     Route::put('{event:slug}/publish', [AdminArtworkController::class, 'publish'])->name('publish');
    //     Route::put('{event:slug}/unpublish', [AdminArtworkController::class, 'unpublish'])->name('unpublish');
    // });

    Route::apiResource('roles', RoleController::class);
    Route::get('permissions', PermissionController::class);

    Route::apiResource('users', UserController::class);



    Route::name('about.')->group(
        function () {
            Route::get('about/info', [AboutManagementController::class, 'index'])->name('info.index');
            Route::put('about/info/edit', [AboutManagementController::class, 'update'])->name('info.update');
        }
    );

    Route::name('jobs.')->group(
        function () {
            Route::get('jobs/info', [JobsManagementController::class, 'index'])->name('info.index');
            Route::put('jobs/info/edit', [JobsManagementController::class, 'update'])->name('info.update');
        }
    );

    Route::name('contact.')->group(
        function () {
            Route::get('contact/info', [ContactManagementController::class, 'index'])->name('info.index');
            Route::put('contact/info/edit', [ContactManagementController::class, 'update'])->name('info.update');
        }
    );

    Route::name('keunggulan.')->group(
        function () {
            Route::get('keunggulan/info', [KatalogManagementController::class, 'index'])->name('info.index');
            Route::put('keunggulan/info/edit', [KatalogManagementController::class, 'update'])->name('info.update');
        }
    );

    Route::name('social.')->group(
        function () {
            Route::get('social', [SocialMediaController::class, 'index'])->name('info.index');
            Route::put('social/edit', [SocialMediaController::class, 'update'])->name('info.update');
        }
    );

    Route::name('promo.')->group(
        function () {
            Route::get('promo/info', [PromoManagementController::class, 'index'])->name('info.index');
            Route::put('promo/info/edit', [PromoManagementController::class, 'update'])->name('info.update');
        }
    );
});


Route::get('/home', [HomeController::class, 'index'])->name('home.index');

Route::apiResource('sliders', SliderController::class);
Route::apiResource('services', ServiceController::class);
Route::apiResource('keunggulan', KeunggulanController::class);
Route::apiResource('gallery', ImageController::class);

Route::get('about', [AboutController::class, 'index'])->name('about.index');
Route::get('contact', [ContactController::class, 'index'])->name('contact.index');
Route::get('katalog', [KatalogController::class, 'index'])->name('katalog.index');
Route::get('katalog/info', [KatalogController::class, 'info'])->name('katalog.info.index');
Route::get('promo', [PromoController::class, 'index'])->name('promo.index');
Route::get('jobs', [JobsController::class, 'index'])->name('jobs.index');
