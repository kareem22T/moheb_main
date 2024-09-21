<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Term;
use App\Models\Article;
use App\Models\Article_Title;
use App\Models\Article_Content;
use App\Models\Categories_description;
use App\Models\Language;
use App\Models\Term_Name;
use App\Models\Term_Title;
use App\Models\Term_Content;
use App\Models\Term_Sound;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Category_Name;
use App\Models\Comment;
use App\Models\Tag;
use App\Traits\DataFormController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    use DataFormController;
    public function getTermsTag($tag_id) {
        return view('site.tag_terms')->with(compact('tag_id'));
    }
    public function getTermsTagByCategory($tag_id, $category_id) {
        return view('site.tag_terms')->with(compact(['tag_id', 'category_id']));
    }
    public function pushComment(Request $request) {
        $validator = Validator::make($request->all(), [
            'comment' => 'required',
            'email' => 'required',
            'name' => 'required',
            'term_id' => 'required',
        ]);

        $comment = Comment::create($request->toArray());

        if ($comment)
            return redirect()->back()->with(["succes" => "Comment Submited successfuly"]);
    }

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

            $term = Term::with([    "tags" => function ($q) {
                $q->select('tags.id', 'tags.name')
                  ->withPivot('term_id', 'tag_id', 'category_id'); // Add category_id to pivot
            },"category" => function ($q) use ($lang){
                $q->with(["names" =>function ($q) use ($lang) {
                    $q->when(isset($lang), function ($q) use($lang){
                        $q->where("language_id", $lang->id);
                    });
                }]);
            }])->find($request->id);
            if ($term->vists) {
                $term->vists = (int) $term->vists + 1;
                $term->save();
            }

            $term_name = Term_Name::where('language_id', $lang->id)->where('term_id', $term->id)->first();
            $term->name = $term_name->term;
            $term_title = Term_Title::where('language_id', $lang->id)->where('term_id', $term->id)->first();
            $term->title = $term_title->title;
            $term_content = Term_Content::where('language_id', $lang->id)->where('term_id', $term->id)->first();
            $term->content = $term_content->content ?? "";
            $term_sound = Term_Sound::where('language_id', $lang->id)->where('term_id', $term->id)->first();
            $term->sound = $term_sound ? $term_sound->iframe : '';


            $user = Auth::user() ? Auth::user() : false;

            if ($user)
            $term->isFav = $term->isFavoritedByUser($user->id);

            return $this->jsonData(true, true, '', [], $term);
        } catch (\Throwable $th) {
            $lang = Language::where('symbol', 'like', '%' . "EN" . '%')->first();


            $term = Term::with(["tags", "category" => function ($q) use ($lang){
                $q->with(["names" =>function ($q) use ($lang) {
                    $q->when(isset($lang), function ($q) use($lang){
                        $q->where("language_id", $lang->id);
                    });
                }]);
            }])->find($request->id);

            if ($term->vists) {
                $term->vists = (int) $term->vists + 1;
                $term->save();
            }

            $term_name = Term_Name::where('language_id', $lang->id)->where('term_id', $term->id)->first();
            $term->name = $term_name->term;
            $term_title = Term_Title::where('language_id', $lang->id)->where('term_id', $term->id)->first();
            $term->title = $term_title->title;
            $term_content = Term_Content::where('language_id', $lang->id)->where('term_id', $term->id)->first();
            $term->content = $term_content->content ?? "";
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

            $category = Category::with(["sub_categories" => function ($q) use ($lang) {
                $q->with(["names" => function ($Q) use ($lang) {
                    $Q->where('language_id', $lang['id']);
                }]);
            }])->where("is_in_nav", true)->get();
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

            $category = Category::with(["sub_categories" => function ($q) use ($lang) {
                $q->with(["names" => function ($Q) use ($lang) {
                    $Q->where('language_id', $lang->id);
                }]);
            }])->where("is_in_nav", true)->get();
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

            $category = Category::orderBy('main_name')->where("cat_type", 0)->get();
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

            $category = Category::orderBy('main_name')->get();
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

            $category = Category::with(["tags" => function ($q) {
                $q->select('tags.id', 'tags.name')
                  ->withPivot('term_id', 'tag_id', 'category_id'); // Add category_id to pivot
            },'terms' => function ($q) use ($lang) {
                // No need to fetch $lang here, use the captured $lang
                $q->with(['names' => function ($query) use ($lang) {
                    $query->where("language_id", $lang->id);
                }])->where('hide', false);
            }, "sub_categories"])->find($request->id);

            if (!$category) {
                // Handle category not found error (optional)
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }

            $category_name = Category_Name::where('category_id', $category->id)->where('language_id', $lang->id)->first();
            $category->name = $category_name->name;

            $category_desc = Categories_description::where('category_id', $category->id)->where('language_id', $lang->id)->first();
            $category->description = $category_desc?->description;

            // Add similar logic for subcategories
            foreach ($category->sub_categories as $subCategory) {
                $subCategory_name = Category_Name::where('category_id', $subCategory->id)->where('language_id', $lang->id)->first();
                $subCategory->name = $subCategory_name->name;

                $subCategory_desc = Categories_description::where('category_id', $subCategory->id)->where('language_id', $lang->id)->first();
                $subCategory->description = $subCategory_desc?->description;
            }

            $subCategoryIds = $category->sub_categories()->pluck('id')->toArray();

            // Include the parent category ID in the array of IDs
            $allCategoryIds = array_merge([$category->id], $subCategoryIds);

            // Fetch the terms for the parent category and its subcategories
            $terms = Term::orderBy('name')
                ->with(['names' => function ($query) use ($lang) {
                    $query->where("language_id", $lang->id);
                }])
                ->whereIn("category_id", $allCategoryIds)
                ->paginate(30); // Adjust the pagination size as needed

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

            $category = Category::with([    "tags" => function ($q) {
                $q->select('tags.id', 'tags.name')
                  ->withPivot('term_id', 'tag_id', 'category_id'); // Add category_id to pivot
            }, 'terms' => function ($q) use ($lang) {
                // No need to fetch $lang here, use the captured $lang
                $q->with(['names' => function ($query) use ($lang) {
                    $query->where("language_id", $lang->id);
                }])->where('hide', false);
            }, "sub_categories"])->find($request->id);

            if (!$category) {
                // Handle category not found error (optional)
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }

            $category_name = Category_Name::where('category_id', $category->id)->where('language_id', $lang->id)->first();
            $category->name = $category_name->name;

            $category_desc = Categories_description::where('category_id', $category->id)->where('language_id', $lang->id)->first();
            $category->description = $category_desc?->description;

            // Add similar logic for subcategories
            foreach ($category->sub_categories as $subCategory) {
                $subCategory_name = Category_Name::where('category_id', $subCategory->id)->where('language_id', $lang->id)->first();
                $subCategory->name = $subCategory_name->name;

                $subCategory_desc = Categories_description::where('category_id', $subCategory->id)->where('language_id', $lang->id)->first();
                $subCategory->description = $subCategory_desc?->description;
            }

            $subCategoryIds = $category->sub_categories()->pluck('id')->toArray();

            // Include the parent category ID in the array of IDs
            $allCategoryIds = array_merge([$category->id], $subCategoryIds);

            // Fetch the terms for the parent category and its subcategories
            $terms = Term::orderBy('name')
                ->with(['names' => function ($query) use ($lang) {
                    $query->where("language_id", $lang->id);
                }])
                ->whereIn("category_id", $allCategoryIds)
                ->paginate(30); // Adjust the pagination size as needed
            $subCategoryIds = $category->sub_categories()->pluck('id')->toArray();

            // Include the parent category ID in the array of IDs
            $allCategoryIds = array_merge([$category->id], $subCategoryIds);

            // Fetch the terms for the parent category and its subcategories
            $terms = Term::orderBy('name')
                ->with(['names' => function ($query) use ($lang) {
                    $query->where("language_id", $lang->id);
                }])
                ->whereIn("category_id", $allCategoryIds)
                ->paginate(30); // Adjust the pagination size as needed

            // Return the data using your preferred method (e.g., JSON)
            return $this->jsonData(true, true, '', [], ["category" => $category, "terms" => $terms]);
        }
    }

     public function getTermsByTag(Request $request) {
        try {
            // Fetch language once outside nested queries
            $lang = Language::where('symbol', 'like', '%' . $request->lang . '%')->first();

        // Build the query to fetch terms associated with the tag
        $termsQuery = Term::whereHas('tags', function ($query) use ($request) {
            $query->where('tag_id', $request->tag_id);

            // Apply category filter if category_id is provided
            if ($request->filled('category_id')) {
                $query->where('category_id', $request->category_id);
            }
        })
        ->with(['names' => function ($query) use ($lang) {
            $query->where('language_id', $lang->id);
        }])
        ->where('hide', false)
        ->orderBy('name');

        $tag = Tag::find($request->tag_id);

        // Paginate the results
        $terms = $termsQuery->paginate(30);

        // Check if any terms were found
        if ($terms->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No terms found'
            ], 404);
        }

        // Return the terms with the associated tag
        return $this->jsonData(true, true, '', [], ["tag" => $tag, "terms" => $terms]);

        } catch (\Throwable $th) {
            // Fetch language once outside nested queries
            $lang = Language::where('symbol', 'like', '%' . "EN" . '%')->first();


        // Build the query to fetch terms associated with the tag
        $termsQuery = Term::whereHas('tags', function ($query) use ($request) {
            $query->where('tag_id', $request->tag_id);

            // Apply category filter if category_id is provided
            if ($request->filled('category_id')) {
                $query->where('category_id', $request->category_id);
            }
        })
        ->with(['names' => function ($query) use ($lang) {
            $query->where('language_id', $lang->id);
        }])
        ->where('hide', false)
        ->orderBy('name');

        $tag = Tag::find($request->tag_id);

        // Paginate the results
        $terms = $termsQuery->paginate(30);

        // Check if any terms were found
        if ($terms->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No terms found'
            ], 404);
        }

            // Return the data using your preferred method (e.g., JSON)
            return $this->jsonData(true, true, '', [], ["tag" => $tag, "terms" => $terms]);
        }
    }

    public function getAllArticels(Request $request) {
        try {

            $lang = Language::where('symbol', 'like', '%' . $request->lang . '%')->first();

            $articles =  Article::with('category')->latest()->orderby('id', 'desc')->paginate(20);

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

            $articles =  Article::with('category')->latest()->orderby('id', 'desc')->paginate(20);

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

    public function search(Request $request)
    {
        $lang = $request->lang;
        $search = $request->search_words;

        // Get the preferred language ID once to avoid duplicate queries
        $preferredLanguageId = Language::where('symbol', $lang)->value('id');

        $terms = Term::whereHas('titles', function ($q) use ($lang, $search) {
            $q->where('title', 'like', '%' . $search . '%');
        })
        ->with([
            'titles' => function ($q) use ($preferredLanguageId) {
                $q->orderByRaw('language_id = ? DESC', [$preferredLanguageId]);
            },
            'names' => function ($q) use ($preferredLanguageId) {
                $q->orderByRaw('language_id = ? DESC', [$preferredLanguageId]);
            },
            'category' => function ($q) use ($preferredLanguageId) {
                $q->with([
                    'names' => function ($q) use ($preferredLanguageId) {
                        $q->orderByRaw('language_id = ? DESC', [$preferredLanguageId]);
                    }
                ]);
            }
        ])
        ->paginate(30); // Apply pagination directly on the query

        // Now sort the terms as needed
        $sortedTerms = $terms->getCollection()->sortBy(function ($term) {
            return strtolower(optional($term->names->first())->term ?? '');
        });

        // Replace the current collection with the sorted one
        $terms->setCollection($sortedTerms->values());

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
