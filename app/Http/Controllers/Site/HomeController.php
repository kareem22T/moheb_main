<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Term;
use App\Models\Article;
use App\Models\Article_Title;
use App\Models\Article_Content;
use App\Models\Language;
use App\Models\Term_Name;
use App\Models\Term_Title;
use App\Models\Term_Content;
use App\Models\Term_Sound;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Category_Name;
use App\Traits\DataFormController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    use DataFormController;

    public function favTerms (Request $request) {
        $lang = $request->lang ? Language::where("symbol", $request->lang)->first() : Language::where("symbol", "EN")->first();
        $user = Auth::user() ? Auth::user() : false;

        $favorites = Favorite::where("user_id", $user->id)->pluck("term_id")->all();
        $terms = Term::with(["names" => function ($q) use ($lang) {
            $q->where("language_id", $lang->id);
        }])->whereIn('id', $favorites)->get();

        foreach ($terms as $term) {
            $category_name = Category_Name::where('category_id', $term->category_id)->where('language_id', $lang->id)->first();
            $term->category_name = $category_name->name;
        }
        return $terms;
    }

    public function favIndex () {
        $user = Auth::user() ? Auth::user() : false;

        if ($user)
            return view("site.wishlist");

        return view("site.login");
    }

    public function getTermIndex() {
        return view('site.term');
    }

    public function getArticleIndex() {
        return view('site.article');
    }

    public function getCategoryIndex() {
        return view('site.category');
    }

    public function getTerm(Request $request) {
        try {
            $lang = Language::where('symbol', 'like', '%' . $request->lang . '%')->first();

            $term = Term::with("tags")->find($request->id);
            if ($term->vists) {
                $term->vists = (int) $term->vists + 1;
                $term->save();
            }

            $term_name = Term_Name::where('language_id', $lang->id)->where('term_id', $term->id)->first();
            $term->name = $term_name->term;
            $term_title = Term_Title::where('language_id', $lang->id)->where('term_id', $term->id)->first();
            $term->title = $term_title->title;
            $term_content = Term_Content::where('language_id', $lang->id)->where('term_id', $term->id)->first();
            $term->content = $term_content->content;
            $term_sound = Term_Sound::where('language_id', $lang->id)->where('term_id', $term->id)->first();
            $term->sound = $term_sound ? $term_sound->iframe : '';


            $user = Auth::user() ? Auth::user() : false;

            if ($user)
            $term->isFav = $term->isFavoritedByUser($user->id);

            return $this->jsonData(true, true, '', [], $term);
        } catch (\Throwable $th) {
            $lang = Language::where('symbol', 'like', '%' . "EN" . '%')->first();

            $term = Term::find($request->id);
            if ($term->vists) {
                $term->vists = (int) $term->vists + 1;
                $term->save();
            }

            $term_name = Term_Name::where('language_id', $lang->id)->where('term_id', $term->id)->first();
            $term->name = $term_name->term;
            $term_title = Term_Title::where('language_id', $lang->id)->where('term_id', $term->id)->first();
            $term->title = $term_title->title;
            $term_content = Term_Content::where('language_id', $lang->id)->where('term_id', $term->id)->first();
            $term->content = $term_content->content;
            $term_sound = Term_Sound::where('language_id', $lang->id)->where('term_id', $term->id)->first();
            $term->sound = $term_sound ? $term_sound->iframe : '';


            $user = Auth::user() ? Auth::user() : false;

            if ($user)
            $term->isFav = $term->isFavoritedByUser($user->id);

            return $this->jsonData(true, true, '', [], $term);
        }
    }

    public function getArticle(Request $request) {
        try {
            $lang = Language::where('symbol', 'like', '%' . $request->lang . '%')->first();

            $article = Article::find($request->id);

            $article_title = Article_Title::where('language_id', $lang->id)->where('article_id', $article->id)->first();
            $article->title = $article_title->title;
            $article_content = Article_Content::where('language_id', $lang->id)->where('article_id', $article->id)->first();
            $article->content = $article_content->content;

            return $this->jsonData(true, true, '', [], $article);
        } catch (\Throwable $th) {
            $lang = Language::where('symbol', 'like', '%' . "EN" . '%')->first();

            $article = Article::find($request->id);

            $article_title = Article_Title::where('language_id', $lang->id)->where('article_id', $article->id)->first();
            $article->title = $article_title->title;
            $article_content = Article_Content::where('language_id', $lang->id)->where('article_id', $article->id)->first();
            $article->content = $article_content->content;

            return $this->jsonData(true, true, '', [], $article);
        }
    }

    public function getLatestTerms(Request $request) {
        try {
            $lang = Language::where('symbol', 'like', '%' . $request->lang . '%')->first();

            $terms =  Term::with('category')->latest()->where('hide', false)->orderby('id', 'desc')->take(12)->get();

            foreach ($terms as $term) {
                $category_name = Category_Name::where('category_id', $term->category_id)->where('language_id', $lang->id)->first();
                $term->category_name = $category_name->name;
                $term_name = Term_Name::where('language_id', $lang->id)->where('term_id', $term->id)->first();
                $term->name = $term_name->term;
                $term_title = Term_Title::where('language_id', $lang->id)->where('term_id', $term->id)->first();
                $term->title = $term_title->title;
                $term_content = Term_Content::where('language_id', $lang->id)->where('term_id', $term->id)->first();
                $term->content = $term_content->content;
                $term_sound = Term_Sound::where('language_id', $lang->id)->where('term_id', $term->id)->first();
                $term->sound = $term_sound ? $term_sound->sound : '';
            }

            $user = Auth::user() ? Auth::user() : false;

            if ($user)
                $terms->each(function ($term) use ($user) {
                    $term->isFav = $term->isFavoritedByUser($user->id);
                });

            return $this->jsonData(true, true, '', [], $terms);
        } catch (\Throwable $th) {
            $lang = Language::where('symbol', 'like', '%' . 'EN' . '%')->first();

            $terms =  Term::with('category')->latest()->where('hide', false)->orderby('id', 'desc')->take(12)->get();

            foreach ($terms as $term) {
                $category_name = Category_Name::where('category_id', $term->category_id)->where('language_id', $lang->id)->first();
                $term->category_name = $category_name->name;
                $term_name = Term_Name::where('language_id', $lang->id)->where('term_id', $term->id)->first();
                $term->name = $term_name->term;
                $term_title = Term_Title::where('language_id', $lang->id)->where('term_id', $term->id)->first();
                $term->title = $term_title->title;
                $term_content = Term_Content::where('language_id', $lang->id)->where('term_id', $term->id)->first();
                $term->content = $term_content->content;
                $term_sound = Term_Sound::where('language_id', $lang->id)->where('term_id', $term->id)->first();
                $term->sound = $term_sound ? $term_sound->sound : '';
            }

            $user = Auth::user() ? Auth::user() : false;

            if ($user)
                $terms->each(function ($term) use ($user) {
                    $term->isFav = $term->isFavoritedByUser($user->id);
                });

            return $this->jsonData(true, true, '', [], $terms);
        }
    }

    public function getLatestCategories(Request $request) {
        try {
            $lang = Language::where('symbol', 'like', '%' . $request->lang . '%')->first();

            $categories =  Category::latest()->take(10)->get();

            foreach ($categories as $cat) {
                $category_name = Category_Name::where('category_id', $cat->id)->where('language_id', $lang->id)->first();
                $cat->name = $category_name->name;
            }

            return $this->jsonData(true, true, '', [], $categories);
        } catch (\Throwable $th) {
            $lang = Language::where('symbol', 'like', '%' . "EN" . '%')->first();

            $categories =  Category::latest()->take(10)->get();

            foreach ($categories as $cat) {
                $category_name = Category_Name::where('category_id', $cat->id)->where('language_id', $lang->id)->first();
                $cat->name = $category_name->name;
            }

            return $this->jsonData(true, true, '', [], $categories);
        }
    }

    public function getCategories(Request $request) {
        try {
            // Fetch language once outside nested queries
            $lang = Language::where('symbol', 'like', '%' . $request->lang . '%')->first();

            // If $lang is not found, handle the error (e.g., return an error response)
            if (!$lang) {
                // Handle language not found error
                return response()->json([
                    'success' => false,
                    'message' => 'Language not found'
                ], 404);
            }

            $category = Category::where("is_in_nav", true)->get();
            foreach ($category as $cat) {
                $category_name = Category_Name::where('category_id', $cat->id)->where('language_id', $lang->id)->first();
                $cat->name = $category_name->name;
            }


            if (!$category) {
                // Handle category not found error (optional)
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }

            // Return the data using your preferred method (e.g., JSON)
            return $this->jsonData(true, true, '', [], $category);
        } catch (\Throwable $th) {
            // Fetch language once outside nested queries
            $lang = Language::where('symbol', 'like', '%' . "EN" . '%')->first();

            // If $lang is not found, handle the error (e.g., return an error response)
            if (!$lang) {
                // Handle language not found error
                return response()->json([
                    'success' => false,
                    'message' => 'Language not found'
                ], 404);
            }

            $category = Category::where("is_in_nav", true)->get();
            foreach ($category as $cat) {
                $category_name = Category_Name::where('category_id', $cat->id)->where('language_id', $lang->id)->first();
                $cat->name = $category_name->name;
            }


            if (!$category) {
                // Handle category not found error (optional)
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }

            // Return the data using your preferred method (e.g., JSON)
            return $this->jsonData(true, true, '', [], $category);
        }
    }

    public function getAllCategories(Request $request) {
        try {
            // Fetch language once outside nested queries
            $lang = Language::where('symbol', 'like', '%' . $request->lang . '%')->first();

            // If $lang is not found, handle the error (e.g., return an error response)
            if (!$lang) {
                // Handle language not found error
                return response()->json([
                    'success' => false,
                    'message' => 'Language not found'
                ], 404);
            }

            $category = Category::orderBy('main_name')->all();
            foreach ($category as $cat) {
                $category_name = Category_Name::where('category_id', $cat->id)->where('language_id', $lang->id)->first();
                $cat->name = $category_name->name;
            }


            if (!$category) {
                // Handle category not found error (optional)
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }

            // Return the data using your preferred method (e.g., JSON)
            return $this->jsonData(true, true, '', [], $category);
        } catch (\Throwable $th) {
            // Fetch language once outside nested queries
            $lang = Language::where('symbol', 'like', '%' . "EN" . '%')->first();

            // If $lang is not found, handle the error (e.g., return an error response)
            if (!$lang) {
                // Handle language not found error
                return response()->json([
                    'success' => false,
                    'message' => 'Language not found'
                ], 404);
            }

            $category = Category::orderBy('main_name')->all();
            foreach ($category as $cat) {
                $category_name = Category_Name::where('category_id', $cat->id)->where('language_id', $lang->id)->first();
                $cat->name = $category_name->name;
            }


            if (!$category) {
                // Handle category not found error (optional)
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }

            // Return the data using your preferred method (e.g., JSON)
            return $this->jsonData(true, true, '', [], $category);
        }
    }

    public function getFootballCat(Request $request) {
        try {
            // Fetch language once outside nested queries
            $lang = Language::where('symbol', 'like', '%' . $request->lang . '%')->first();

            // If $lang is not found, handle the error (e.g., return an error response)
            if (!$lang) {
                // Handle language not found error
                return response()->json([
                    'success' => false,
                    'message' => 'Language not found'
                ], 404);
            }

            $category = Category::with(['terms' => function ($q) use ($lang) {
                // No need to fetch $lang here, use the captured $lang
                $lang = $lang;
                $q->latest()->with(['names' => function ($query) use ($lang) {
                    $query->where("language_id", $lang->id);
                }])->where('hide', false)->take(12);
            }])->whereHas('names', function ($query) use ($request) {
                // No need to fetch $lang here, use the captured $lang
                $query->where('name', 'like', '%' . "football" . '%');
            })->first();

            if (!$category) {
                // Handle category not found error (optional)
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }

            // Set category name (assuming `Category_Name` model has a `name` attribute)
            $category->name = $category->names->first()->name ?? '';

            // Return the data using your preferred method (e.g., JSON)
            return $this->jsonData(true, true, '', [], $category);

        } catch (\Throwable $th) {
            // Fetch language once outside nested queries
            $lang = Language::where('symbol', 'like', '%' . "EN" . '%')->first();

            // If $lang is not found, handle the error (e.g., return an error response)
            if (!$lang) {
                // Handle language not found error
                return response()->json([
                    'success' => false,
                    'message' => 'Language not found'
                ], 404);
            }

            $category = Category::with(['terms' => function ($q) use ($lang) {
                // No need to fetch $lang here, use the captured $lang
                $lang = $lang;
                $q->latest()->with(['names' => function ($query) use ($lang) {
                    $query->where("language_id", $lang->id);
                }])->where('hide', false)->take(12);
            }])->whereHas('names', function ($query) use ($request) {
                // No need to fetch $lang here, use the captured $lang
                $query->where('name', 'like', '%' . "football" . '%');
            })->first();

            if (!$category) {
                // Handle category not found error (optional)
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }

            // Set category name (assuming `Category_Name` model has a `name` attribute)
            $category->name = $category->names->first()->name ?? '';

            // Return the data using your preferred method (e.g., JSON)
            return $this->jsonData(true, true, '', [], $category);
        }
     }

    public function getCategoryById(Request $request) {
        try {
            // Fetch language once outside nested queries
            $lang = Language::where('symbol', 'like', '%' . $request->lang . '%')->first();

            // If $lang is not found, handle the error (e.g., return an error response)
            if (!$lang) {
                // Handle language not found error
                return response()->json([
                    'success' => false,
                    'message' => 'Language not found'
                ], 404);
            }

            $category = Category::with(['terms' => function ($q) use ($lang) {
                // No need to fetch $lang here, use the captured $lang
                $lang = $lang;
                $q->with(['names' => function ($query) use ($lang) {
                    $query->where("language_id", $lang->id);
                }])->where('hide', false);
            }, "sub_categories"])->find($request->id);

            $category_name = Category_Name::where('category_id', $category->id)->where('language_id', $lang->id)->first();
            $category->name = $category_name->name;

            $terms = Term::orderBy('name')->with(['names' => function ($query) use ($lang) {
                $query->where("language_id", $lang->id);
            }])->where("category_id", $category->id)->paginate(30); // You can adjust the pagination size (e.g., 10 items per page)

            if (!$category) {
                // Handle category not found error (optional)
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }

            // Return the data using your preferred method (e.g., JSON)
            return $this->jsonData(true, true, '', [], ["category" => $category, "terms" => $terms]);

        } catch (\Throwable $th) {
            // Fetch language once outside nested queries
            $lang = Language::where('symbol', 'like', '%' . "EN" . '%')->first();

            // If $lang is not found, handle the error (e.g., return an error response)
            if (!$lang) {
                // Handle language not found error
                return response()->json([
                    'success' => false,
                    'message' => 'Language not found'
                ], 404);
            }

            $category = Category::with(['terms' => function ($q) use ($lang) {
                // No need to fetch $lang here, use the captured $lang
                $lang = $lang;
                $q->with(['names' => function ($query) use ($lang) {
                    $query->where("language_id", $lang->id);
                }])->where('hide', false);
            }, "sub_categories"])->find($request->id);

            $category_name = Category_Name::where('category_id', $category->id)->where('language_id', $lang->id)->first();
            $category->name = $category_name->name;

            $terms = Term::orderBy('name')->with(['names' => function ($query) use ($lang) {
                $query->where("language_id", $lang->id);
            }])->where("category_id", $category->id)->paginate(30); // You can adjust the pagination size (e.g., 10 items per page)

            if (!$category) {
                // Handle category not found error (optional)
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }

            // Return the data using your preferred method (e.g., JSON)
            return $this->jsonData(true, true, '', [], ["category" => $category, "terms" => $terms]);
        }
     }

    public function getLatestLatest(Request $request) {
        try {

            $lang = Language::where('symbol', 'like', '%' . $request->lang . '%')->first();

            $articles =  Article::with('category')->latest()->orderby('id', 'desc')->take(12)->get();

            foreach ($articles as $article) {
                $category_name = Category_Name::where('category_id', $article->category->id)->where('language_id', $lang->id)->first();
                $article->category_name = $category_name->name;
                $article_title = Article_Title::where('language_id', $lang->id)->where('article_id', $article->id)->first();
                $article->title = $article_title->title;
                $articlecontent = Article_Content::where('language_id', $lang->id)->where('article_id', $article->id)->first();
                $article->content = $articlecontent->content;
            }
            return $this->jsonData(true, true, '', [], $articles);
        } catch (\Throwable $th) {
            $lang = Language::where('symbol', 'like', '%' . "EN" . '%')->first();

            $articles =  Article::with('category')->latest()->orderby('id', 'desc')->take(12)->get();

            foreach ($articles as $article) {
                $category_name = Category_Name::where('category_id', $article->category->id)->where('language_id', $lang->id)->first();
                $article->category_name = $category_name->name;
                $article_title = Article_Title::where('language_id', $lang->id)->where('article_id', $article->id)->first();
                $article->title = $article_title->title;
                $articlecontent = Article_Content::where('language_id', $lang->id)->where('article_id', $article->id)->first();
                $article->content = $articlecontent->content;
            }
            return $this->jsonData(true, true, '', [], $articles);

        }

    }

    public function search(Request $request) {
        $lang = $request->lang;
        $search = $request->search_words;

        $terms = Term::with(["titles" => function ($q) use ($lang, $search) {
            $q->where('title', 'like', '%' . $search . '%')
              ->where('language_id', Language::where("symbol", $lang)->value('id'));
        }, "names" => function ($q) use ($lang, $search) {
                $q->where('language_id', Language::where("symbol", $lang)->value('id'));
        }, "category" => function ($q) use ($lang) {
            $q->with(["names" => function ($Q) use ($lang) {
                $Q->where('language_id', Language::where("symbol", $lang)->value('id'));
            }]);
        }])
        ->whereHas("titles", function ($q) use ($lang, $search) {
            $q->where('title', 'like', '%' . $search . '%')
              ->where('language_id', Language::where("symbol", $lang)->value('id'));
        })
        ->paginate(30);

        return $this->jsonData(true, true, '', [], $terms);

    }

    public function addToFav(Request $request) {
        $validator = Validator::make($request->all(), [
            'term_id' => ['required'],
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Add to fav failed', [$validator->errors()->first()], []);
        }

        $user = Auth::user();

        if (!$user)
            return $this->jsondata(false, null, 'Add to fav failed', ["Login First"], []);

        $favorite = Favorite::where("user_id", $user->id)->where("term_id", $request->term_id)->first();

        if ($favorite) :
            $favorite->delete();
        else :
            $addfavorite = Favorite::create([
                'user_id' => $user->id,
                'term_id' => $request->term_id
            ]);
        endif;

        return $this->jsondata(true, null, 'Success', [], []);

    }

    public function searchIndex() {
        return view("site.search");
    }
}
