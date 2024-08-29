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

// Route::middleware('retirect_to_cumming_soon')->group(function () {

    Route::get('/logout', [RegisterController::class, 'logout'])->name('site.logout');
    Route::post('/login', [RegisterController::class, 'login'])->name('site.loginprocess');
    // Route::get('/logout', [RegisterController::class, 'logutIndex'])->name('site.logout');

    Route::get('/register', [RegisterController::class, 'getRegisterIndex'])->name('site.register');
    Route::get('/login', [RegisterController::class, 'getLoginIndex'])->name('site.login');
    Route::get('/get-user', [RegisterController::class, 'getUser'])->name('site.get-user');
    Route::post('/register', [RegisterController::class, 'register'])->name('site.register');
    Route::post('/category', [HomeController::class, 'getCategoryById'])->name('category.getbyid');
    Route::post('/category/get-all', [HomeController::class, 'getAllCategories']);
    Route::post('/term', [HomeController::class, 'getTerm'])->name('site.getterm');
    Route::get('/tag/{tag_id}', [HomeController::class, 'getTermsTag']);
    Route::get('/tag/{tag_id}/{category_id}', [HomeController::class, 'getTermsTagByCategory']);
    Route::post('/get-by-tag', [HomeController::class, 'getTermsByTag']);
    Route::post('/article', [HomeController::class, 'getArticle'])->name('site.getarticle');
    Route::post('/latest-terms', [HomeController::class, 'getLatestTerms'])->name('term.getlatest');
    Route::post('/latest-articles', [HomeController::class, 'getLatestLatest'])->name('article.getlatest');
    Route::post('/all-articles', [HomeController::class, 'getAllArticels'])->name('article.getAllArticels');
    Route::post('/latest-categories', [HomeController::class, 'getLatestCategories'])->name('categories.getlatest');
    Route::post('/get-categories', [HomeController::class, 'getCategories'])->name('categories.get');
    Route::post('/get_football_cat', [HomeController::class, 'getFootballCat'])->name('categories.football');
    Route::post('/search-term', [HomeController::class, 'search'])->name('words.search');
    Route::post('/fav-add-delete', [HomeController::class, 'addToFav'])->name('fav.addordelete');
    Route::get('/contact-us', function () {
        return view("site.contact");
    });
    Route::get('/all-sports', function () {
        return view("site.all_categories");
    });
    Route::get('/blog', function () {
        return view("site.blog");
    });
    Route::get('/about-us', function () {
        return view("site.about");
    });
    Route::post('/my-favTerms', [HomeController::class, 'favTerms'])->name('fav.terms');
    Route::get('/term/{name}/{id}', [HomeController::class, 'getTermIndex'])->name('term.get');
    Route::get('/article/{id}', [HomeController::class, 'getArticleIndex'])->name('article.get');
    Route::get('/category/{id}', [HomeController::class, 'getCategoryIndex'])->name('category.get');
    Route::get('/search/{word}', [HomeController::class, 'searchIndex'])->name('view.search');
    Route::get('/my-wishlist', [HomeController::class, 'favIndex'])->name('view.wishlist');
    Route::get('/comment/push', [HomeController::class, 'pushComment'])->name('push.comment');

    Route::get('/', function () {
        return view('site.home');
    })->name('site.home');
    Route::get('/coming-soon', function () {
        return view('site.coming');
    })->name('site.coming');

    Route::get('/copy-rights', function () {
        return view('site.copy');
    })->name('site.copy');

    Route::get('/privacy', function () {
        return view('site.privacy');
    })->name('site.privacy');

