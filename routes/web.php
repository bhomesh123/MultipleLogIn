<?php

use Illuminate\Support\Facades\Route;  
use App\Http\Controllers\FacebookController;
use App\Http\Controllers\LinkedinController;
use App\Http\Controllers\LoginWithGoogleController;
use App\Http\Controllers\LoginWithShopifyController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });


Route::get('/', function () {
    return view('welcome');
});
  
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
  
Route::controller(LoginWithGoogleController::class)->group(function(){
    Route::get('authorized/google', 'redirectToGoogle')->name('auth.google');
    Route::get('authorized/google/callback', 'handleGoogleCallback');
    // Route::post('/logout', [LoginWithGoogleController::class, 'destroy'])->name('logout');
});








// Route::controller(LoginWithShopifyController::class)->group(function(){
//     // Route to initiate the Shopify login
//     Route::get('authorized/shopify', 'redirectToShopify')->name('auth.shopify');
//     // Route to handle the callback from Shopify
//     Route::get('authorized/shopify/callback', 'handleShopifyCallback');
// });


Route::prefix('login-with-shopify')->group(function () {
    Route::get('authorized/shopify', [LoginWithShopifyController::class, 'redirectToShopify'])->name('auth.shopify');
    Route::get('authorized/shopify/callback', [LoginWithShopifyController::class, 'handleShopifyCallback']);
});





// Route::get('/login/shopify', 'LoginWithShopifyController@redirectToShopify')->name('login.shopify');
// Route::get('/login/shopify/callback', 'LoginWithShopifyController@handleShopifyCallback');


Route::get('auth/linkedin', [LinkedinController::class, 'linkedinRedirect']);
Route::get('auth/linkedin/callback', [LinkedinController::class, 'linkedinCallback']);



Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
  
Route::controller(FacebookController::class)->group(function(){
    Route::get('auth/facebook', 'redirectToFacebook')->name('auth.facebook');
    Route::get('auth/facebook/callback', 'handleFacebookCallback');
});



// Route::prefix('auth')->group(function () {
//     Route::get('facebook', [FacebookController::class, 'redirectToFacebook'])->name('auth.facebook');
//     Route::get('facebook/callback', [FacebookController::class, 'handleFacebookCallback']);
// });