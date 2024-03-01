<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\DataFormController;
use App\Traits\SaveFileTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Language;
use App\Models\Category;
use App\Models\Category_Name;
use App\Models\Tag;
use App\Models\Article;
use App\Models\Article_Title;
use App\Models\Article_Content;
use App\Models\Articles_image;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ArticleController extends Controller
{
    use DataFormController;
    use SaveFileTrait;

    public function preview() {
        return view('admin.articles.preview');
    }

    public function getLanguages() {
        $languages = Language::all();
        
        return $this->jsonData(true, true, '', [], $languages);
    }

    public function getMainCategories() {
        $categories = Category::with('sub_categories')->where('cat_type', 0)->get();
        
        return $this->jsonData(true, true, '', [], $categories);
    }

    public function getArticles() {
        $Articles = Article::with('category')->paginate(10);
        
        return $this->jsonData(true, true, '', [], $Articles);
    }

    public function search(Request $request) {
        $Articles = Article::with('category')->where('name', 'like', '%' . $request->search_Articles . '%')
                                ->paginate(10);

        $ArticlesPerTitles = Article::with('category')->whereHas('titles', function ($query) use ($request) {
            $query->where('title', 'like', '%'.$request->search_Articles.'%');
        })->paginate(10);

        $ArticlesPerContents = Article::with('category')->whereHas('contents', function ($query) use ($request) {
            $query->where('content', 'like', '%'.$request->search_Articles.'%');
        })->paginate(10);
        
        return $this->jsonData(true, true, '', [], !$Articles->isEmpty() ? $Articles : (!$ArticlesPerTitles->isEmpty() ? $ArticlesPerTitles : $ArticlesPerContents));

    }

    public function addIndex() {
        return view('admin.articles.add');
    }

    public function add(Request $request) {
        $languages = Language::all();
        $symbols = $languages->pluck('symbol')->all();
        // add(category_translations)
        $validator = Validator::make($request->all(), [
            'main_name' => ['required'],
        ], [
            'main_name.required' => 'Please write Article main name',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, true, 'Add failed', [$validator->errors()->first()], []);
        }


        $missingTitleTranslations = array_diff($symbols, array_keys($request->title_translations));

        if (!empty($missingTitleTranslations)) { 
            return $this->jsondata(false, true, 'Add failed', ['Please enter Article title in (' . Language::where('symbol', reset($missingTitleTranslations))->first()->name . ')'], []);
        }

        $obj = [];

        $missingContentTranslations = array_diff($symbols, array_keys($request->content_translations ? $request->content_translations : $obj));

        if (!empty($missingContentTranslations)) { 
            return $this->jsondata(false, true, 'Add failed', ['Please enter Article content in (' . Language::where('symbol', reset($missingContentTranslations))->first()->name . ')'], []);
        }
        if (!$request->cat_id) { 
            return $this->jsondata(false, true, 'Add failed', ['Please choose category for your Article'], []);
        }
        if (Category::find($request->cat_id)->sub_categories()->count() > 0) { 
            return $this->jsondata(false, true, 'Add failed', ['Please choose sub category for your Article'], []);
        }

        $createArticle = Article::create([
            'name' => Str::ucfirst($request->main_name),
            'thumbnail_path' => $request->thumbnail ? $request->thumbnail : null,
            'category_id' => $request->cat_id
        ]);

        if ($request->tags)
            foreach ($request->tags as $tagName) {
                $tag = Tag::firstOrCreate(['name' => $tagName]); // Check if tag exists or create a new one
                $createArticle->tags()->attach($tag->id); // Attach the tag to the Article
            }

        foreach ($request->title_translations as $lang => $title) {
            $addTitles = Article_Title::create([
                'title' => $title,
                'article_id' => $createArticle->id,
                'language_id' => Language::where('symbol', $lang)->first()->id,
            ]);
        };

        foreach ($request->content_translations as $lang => $content) {
            $addContents = Article_Content::create([
                'content' => $content,
                'article_id' => $createArticle->id,
                'language_id' => Language::where('symbol', $lang)->first()->id,
            ]);
        };

        if ($createArticle)
            return $this->jsonData(true, true, 'Article has been added successfuly', [], []);
    }

    public function editIndex ($cat_id) {
        $Article = Article::find($cat_id);
        return view('admin.articles.edit')->with(compact('Article'));
    }    

    public function getArticleById(Request $request) {
        $Article = Article::with('category')->with('tags')->find($request->article_id);
        
        return $this->jsonData(true, true, '', [], $Article);
    }

    public function getArticleTitles(Request $request) {
        $languages = Language::all();
        $symbols = $languages->pluck('symbol')->all();
        
        $Article_titles = Article::find($request->article_id)->titles;
        $Article_titles_key_value = [];

        if ($Article_titles)
            foreach ($Article_titles as $key => $Article_title) {
                $Article_titles_key_value[$Article_title->language->symbol] = $Article_title->title;
            };

        if ($Article_titles_key_value) :
            $missingLanguages = array_diff($symbols, array_keys($Article_titles_key_value));
            if ($missingLanguages)
                foreach ($missingLanguages as $lang) {
                    $Article_titles_key_value[$lang] = null;
                }
        endif;

        return $this->jsonData(true, true, '', [], $Article_titles_key_value);
    }

    public function getArticleContents(Request $request) {
        $languages = Language::all();
        $symbols = $languages->pluck('symbol')->all();
        
        $Article_contents = Article::find($request->article_id)->contents;
        $Article_contents_key_value = [];

        if ($Article_contents)
            foreach ($Article_contents as $key => $Article_content) {
                $Article_contents_key_value[$Article_content->language->symbol] = $Article_content->content;
            };

        if ($Article_contents_key_value) :
            $missingLanguages = array_diff($symbols, array_keys($Article_contents_key_value));
            if ($missingLanguages)
                foreach ($missingLanguages as $lang) {
                    $Article_contents_key_value[$lang] = null;
                }
        endif;

        return $this->jsonData(true, true, '', [], $Article_contents_key_value);
    }

    public function editArticle(Request $request) {
        $languages = Language::all();
        $symbols = $languages->pluck('symbol')->all();
        $Article = Article::find($request->article_id);

        $validator = Validator::make($request->all(), [
            'main_name' => ['required'],
        ], [
            'main_name.required' => 'Please write Article main name',
            'main_cat' => 'Please choose category for your Article'
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, true, 'Edit failed', [$validator->errors()->first()], []);
        }


        $missingTitleTranslations = array_diff($symbols, array_keys($request->title_translations));

        if (!empty($missingTitleTranslations)) { 
            return $this->jsondata(false, true, 'Add failed', ['Please enter Article title in (' . Language::where('symbol', reset($missingTitleTranslations))->first()->name . ')'], []);
        }

        $missingContentTranslations = array_diff($symbols, array_keys($request->content_translations));

        if (!empty($missingContentTranslations)) { 
            return $this->jsondata(false, true, 'Add failed', ['Please enter Article content in (' . Language::where('symbol', reset($missingContentTranslations))->first()->name . ')'], []);
        }
        if (!$request->cat_id) { 
            return $this->jsondata(false, true, 'Add failed', ['Please choose category for your Article'], []);
        }
        if (Category::find($request->cat_id)->sub_categories()->count() > 0) { 
            return $this->jsondata(false, true, 'Add failed', ['Please choose sub category for your Article'], []);
        }

        $Article->name = Str::ucfirst($request->main_name);
        if ($request->thumbnail)
            $Article->thumbnail_path = $request->thumbnail;
        $Article->category_id = $request->cat_id;
        $Article->save();

        DB::table('article_tag')->where('article_id', $Article->id)->delete();
        if ($request->tags)
        foreach ($request->tags as $tagName) {
            $tag = Tag::firstOrCreate(['name' => $tagName]); // Check if tag exists or create a new one
            $Article->tags()->attach($tag->id); // Attach the tag to the Article
        }

        foreach ($request->title_translations as $lang => $title) {
            $lang_id = Language::where('symbol', $lang)->first()->id;
            $Article_title = Article_Title::where('article_id', $request->article_id)->where('language_id', $lang_id)->first();

            if ($Article_title) {
                $Article_title->title = $title;
                $Article_title->save();
            } else {
                $addTitles = Article_Title::create([
                    'title' => $title,
                    'article_id' => $request->article_id,
                    'language_id' => Language::where('symbol', $lang)->first()->id,
                ]);
            }
        };

        foreach ($request->content_translations as $lang => $content) {
            $lang_id = Language::where('symbol', $lang)->first()->id;
            $Article_content = Article_Content::where('article_id', $request->article_id)->where('language_id', $lang_id)->first();

            if ($Article_content) {
                $Article_content->content = $content;
                $Article_content->save();
            } else {
                $addContents = Article_Content::create([
                    'content' => $content,
                    'article_id' => $request->article_id,
                    'language_id' => Language::where('symbol', $lang)->first()->id,
                ]);
            }
        };

        if ($Article)
            return $this->jsonData(true, true, 'Article has been Updated successfuly', [], []);
    }

    public function delete(Request $request) {
        $validator = Validator::make($request->all(), [
            'article_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, true, 'Edit failed', [$validator->errors()->first()], []);
        }

        $Article = Article::find($request->article_id);
        if ($Article->thumbnail_path)
            File::delete(public_path('/dashboard/images/uploads/articles_thumbnail/' . $Article->thumbnail_path));
        $Article->titles()->delete();
        $Article->contents()->delete();
        $Article->delete();

        if ($Article)
            return $this->jsonData(true, true, $request->file_name . ' Article has been deleted succussfuly', [], []);
    }
}
