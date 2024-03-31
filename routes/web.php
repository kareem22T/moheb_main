<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Site\RegisterController;
use App\Http\Controllers\Site\HomeController;

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
    return view('site.home');
})->name('site.home');

Route::get('/logout', [RegisterController::class, 'logout'])->name('site.logout');
Route::post('/login', [RegisterController::class, 'login'])->name('site.loginprocess');
// Route::get('/logout', [RegisterController::class, 'logutIndex'])->name('site.logout');

Route::get('/register', [RegisterController::class, 'getRegisterIndex'])->name('site.register');
Route::get('/login', [RegisterController::class, 'getLoginIndex'])->name('site.login');
Route::middleware('auth:sanctum')->get('/get-user', [RegisterController::class, 'getUser'])->name('site.get-user');
Route::post('/register', [RegisterController::class, 'register'])->name('site.register');
Route::get('/term/{name}/{id}', [HomeController::class, 'getTermIndex'])->name('term.get');
Route::get('/article/{id}', [HomeController::class, 'getArticleIndex'])->name('article.get');
Route::get('/category/{id}', [HomeController::class, 'getCategoryIndex'])->name('category.get');
Route::post('/category', [HomeController::class, 'getCategoryById'])->name('category.getbyid');
Route::post('/term', [HomeController::class, 'getTerm'])->name('site.getterm');
Route::post('/article', [HomeController::class, 'getArticle'])->name('site.getarticle');
Route::post('/latest-terms', [HomeController::class, 'getLatestTerms'])->name('term.getlatest');
Route::post('/latest-articles', [HomeController::class, 'getLatestLatest'])->name('article.getlatest');
Route::post('/latest-categories', [HomeController::class, 'getLatestCategories'])->name('categories.getlatest');
Route::post('/get-categories', [HomeController::class, 'getCategories'])->name('categories.get');
Route::post('/get_football_cat', [HomeController::class, 'getFootballCat'])->name('categories.football');
Route::post('/search', [HomeController::class, 'search'])->name('words.search');
Route::post('/fav-add-delete', [HomeController::class, 'addToFav'])->name('fav.addordelete');
Route::get('/search/{word}', [HomeController::class, 'searchIndex'])->name('view.search');
Route::get('/my-wishlist', [HomeController::class, 'favIndex'])->name('view.wishlist');
Route::get('/contact-us', function () {
    return view("site.contact");
});
Route::get('/about-us', function () {
    return view("site.about");
});
Route::post('/my-favTerms', [HomeController::class, 'favTerms'])->name('fav.terms');
