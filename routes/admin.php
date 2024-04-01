<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RegisterController;
use App\Http\Controllers\Admin\LanguagesController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\WordsController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\TagsController;
use App\Http\Controllers\Admin\ImagesController;
use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\Admin\SettingsController;

Route::middleware(['guest_admin'])->group(function () {
    Route::get('/login', [RegisterController::class, 'getLoginIndex']);
    Route::post('/login', [RegisterController::class, 'login']);
});

Route::post('/admin/categories/get-languages', [CategoriesController::class, 'getLanguages'])->name('languages.get');

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/', [RegisterController::class, 'ff']);
    Route::get('/logout', [RegisterController::class, 'logout']);
    Route::get('/get-excel', [MainController::class, 'getExcelData']);
    Route::get('/get-contact', [SettingsController::class, 'getContact'])->name('contact.get');

    //languages
    Route::get('/languages', [LanguagesController::class, 'preview']);
    Route::post('/languages', [LanguagesController::class, 'getLanguages']);
    Route::post('/languages/search', [LanguagesController::class, 'search']);
    Route::post('/languages/edit', [LanguagesController::class, 'editLang']);
    Route::post('/languages/delete', [LanguagesController::class, 'delete']);
    Route::get('/languages/add', [LanguagesController::class, 'addIndex']);
    Route::post('/languages/add', [LanguagesController::class, 'add']);
    Route::get('/languages/content', [LanguagesController::class, 'contentIndex']);
    Route::post('/languages/content/update', [LanguagesController::class, 'updateContentFile']);

    //tags
    Route::get('/tags', [TagsController::class, 'preview']);
    Route::post('/tags', [TagsController::class, 'getTags']);
    Route::post('/tags/search', [TagsController::class, 'search']);
    Route::post('/tags/edit', [TagsController::class, 'editTag']);
    Route::post('/tags/delete', [TagsController::class, 'delete']);
    Route::get('/tags/add', [TagsController::class, 'addIndex']);
    Route::post('/tags/add', [TagsController::class, 'add']);
    Route::get('/tags/content', [TagsController::class, 'contentIndex']);
    Route::post('/tags/content/update', [TagsController::class, 'updateContentFile']);

    //categories
    Route::get('/categories', [CategoriesController::class, 'preview']);
    Route::post('/categories/main', [CategoriesController::class, 'getMainCategories']);
    Route::post('/categories/sub', [CategoriesController::class, 'getSubCategories']);
    Route::post('/category', [CategoriesController::class, 'getCategoryById']);
    Route::get('/category/{cat_id}', [CategoriesController::class, 'getCategoryIndex']);
    Route::post('/category/names', [CategoriesController::class, 'getCategoryNames']);
    Route::post('/categories/get-languages', [CategoriesController::class, 'getLanguages']);
    Route::post('/categories/search', [CategoriesController::class, 'search']);
    Route::post('/categories/delete', [CategoriesController::class, 'delete']);
    Route::get('/categories/add', [CategoriesController::class, 'addIndex']);
    Route::post('/categories/add', [CategoriesController::class, 'add']);
    Route::get('/categories/edit/', [CategoriesController::class, 'preview']);
    Route::post('/categories/edit', [CategoriesController::class, 'editCategory']);
    Route::post('/categories/makeTop', [CategoriesController::class, 'makeTop']);
    Route::get('/categories/edit/{cat_id}', [CategoriesController::class, 'editIndex']);

    //words
    Route::get('/words', [WordsController::class, 'preview']);
    Route::post('/words', [WordsController::class, 'getTerms']);
    Route::post('/categories', [WordsController::class, 'getMainCategories']);
    Route::post('/word', [WordsController::class, 'getWordById']);
    Route::post('/word/names', [WordsController::class, 'getWordNames']);
    Route::post('/word/titles', [WordsController::class, 'getWordTitles']);
    Route::post('/word/contents', [WordsController::class, 'getWordContents']);
    Route::post('/word/sounds', [WordsController::class, 'getWordSounds']);
    Route::post('/words/get-languages', [WordsController::class, 'getLanguages']);
    Route::post('/words/search', [WordsController::class, 'search']);
    Route::post('/words/delete', [WordsController::class, 'delete']);
    Route::get('/words/add', [WordsController::class, 'addIndex']);
    Route::post('/words/add', [WordsController::class, 'add']);
    Route::get('/words/edit/', [WordsController::class, 'preview']);
    Route::post('/words/edit', [WordsController::class, 'editTerm']);
    Route::post('/words/toggle-hide', [WordsController::class, 'toggleHide']);
    Route::get('/words/edit/{cat_id}', [WordsController::class, 'editIndex']);

    //articles
    Route::get('/articles', [ArticleController::class, 'preview']);
    Route::post('/articles', [ArticleController::class, 'getArticles']);
    Route::post('/categories', [ArticleController::class, 'getMainCategories']);
    Route::post('/article', [ArticleController::class, 'getArticleById']);
    Route::post('/article/titles', [ArticleController::class, 'getArticleTitles']);
    Route::post('/article/contents', [ArticleController::class, 'getArticleContents']);
    Route::post('/articles/get-languages', [ArticleController::class, 'getLanguages']);
    Route::post('/articles/search', [ArticleController::class, 'search']);
    Route::post('/articles/delete', [ArticleController::class, 'delete']);
    Route::get('/articles/add', [ArticleController::class, 'addIndex']);
    Route::post('/articles/add', [ArticleController::class, 'add']);
    Route::get('/articles/edit/', [ArticleController::class, 'preview']);
    Route::post('/articles/edit', [ArticleController::class, 'editArticle']);
    Route::get('/articles/edit/{article_id}', [ArticleController::class, 'editIndex']);

    // images
    Route::post('/images/upload', [ImagesController::class, 'uploadeImg']);
    Route::get('/images/get_images', [ImagesController::class, 'getImages']);
    Route::post('/images/search', [ImagesController::class, 'search']);

    Route::get('/update-contact', function() {
        return view('admin.update_contact');
    })->name('update.contact');
    Route::post('/add-contact', [SettingsController::class, 'addContact'])->name('contact.put');

});

