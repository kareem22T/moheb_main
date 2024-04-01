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
use App\Models\Term;
use App\Models\Tag;
use App\Models\Term_Name;
use App\Models\Term_Title;
use App\Models\Term_Content;
use App\Models\Term_Sound;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class WordsController extends Controller
{
    use DataFormController;
    use SaveFileTrait;

    public function preview() {
        return view('admin.words.preview');
    }

    public function getLanguages() {
        $languages = Language::all();

        return $this->jsonData(true, true, '', [], $languages);
    }

    public function getMainCategories() {
        $categories = Category::with('sub_categories')->where('cat_type', 0)->get();

        return $this->jsonData(true, true, '', [], $categories);
    }

    public function getTerms() {
        $terms = Term::with('category')->paginate(10);

        return $this->jsonData(true, true, '', [], $terms);
    }

    public function toggleHide(Request $request) {
        $term = Term::find($request->id);
        if ($term)
            $term->hide = !$term->hide;

        $term->save();
        if ($term)
        return $this->jsonData(true, true, '', [], []);
    }

    public function search(Request $request) {
        $terms = Term::with('category')->where('name', 'like', '%' . $request->search_words . '%')
                                ->paginate(10);

        $termsPerNames = Term::with('category')->whereHas('names', function ($query) use ($request) {
            $query->where('name', 'like', '%'.$request->search_words.'%');
        })->paginate(10);

        $termsPerTitles = Term::with('category')->whereHas('titles', function ($query) use ($request) {
            $query->where('title', 'like', '%'.$request->search_words.'%');
        })->paginate(10);

        $termsPerContents = Term::with('category')->whereHas('contents', function ($query) use ($request) {
            $query->where('content', 'like', '%'.$request->search_words.'%');
        })->paginate(10);

        return $this->jsonData(true, true, '', [], !$terms->isEmpty() ? $terms : (!$termsPerNames->isEmpty() ? $termsPerNames : (!$termsPerTitles->isEmpty() ? $termsPerTitles : $termsPerContents)));

    }

    public function addIndex() {
        return view('admin.words.add');
    }

    public function add(Request $request) {
        $languages = Language::take(7)->get();
        $symbols = $languages->pluck('symbol')->all();
        // add(category_translations)
        $validator = Validator::make($request->all(), [
            'main_name' => ['required'],
        ], [
            'main_name.required' => 'Please write term main name',
            'main_cat' => 'Please choose category for your term'
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, true, 'Add failed', [$validator->errors()->first()], []);
        }

        $missingTermsTranslations = array_diff($symbols, array_keys($request->term_translations));

        if (!empty($missingTermsTranslations)) {
            return $this->jsondata(false, true, 'Add failed', ['Please enter term name in (' . Language::where('symbol', reset($missingTermsTranslations))->first()->name . ')'], []);
        }

        $missingTitleTranslations = array_diff($symbols, array_keys($request->title_translations));

        if (!empty($missingTitleTranslations)) {
            return $this->jsondata(false, true, 'Add failed', ['Please enter term title in (' . Language::where('symbol', reset($missingTitleTranslations))->first()->name . ')'], []);
        }

        $missingContentTranslations = array_diff($symbols, array_keys($request->content_translations));

        if (!empty($missingContentTranslations)) {
            return $this->jsondata(false, true, 'Add failed', ['Please enter term content in (' . Language::where('symbol', reset($missingContentTranslations))->first()->name . ')'], []);
        }
        if (!$request->cat_id) {
            return $this->jsondata(false, true, 'Add failed', ['Please choose category for your term'], []);
        }
        if (Category::find($request->cat_id)->sub_categories()->count() > 0) {
            return $this->jsondata(false, true, 'Add failed', ['Please choose sub category for your term'], []);
        }

        $createTerm = Term::create([
            'name' => Str::ucfirst($request->main_name),
            'thumbnail_path' => $request->thumbnail ? $request->thumbnail : null,
            'category_id' => $request->cat_id
        ]);

        if ($request->tags)
        foreach ($request->tags as $tagName) {
            $tag = Tag::firstOrCreate(['name' => $tagName]); // Check if tag exists or create a new one
            $createTerm->tags()->attach($tag->id); // Attach the tag to the term
        }

        foreach ($request->term_translations as $lang => $term) {
            $addNames = Term_Name::create([
                'term' => $term,
                'term_id' => $createTerm->id,
                'language_id' => Language::where('symbol', $lang)->first()->id,
            ]);
        };

        foreach ($request->title_translations as $lang => $title) {
            $addTitles = Term_Title::create([
                'title' => $title,
                'term_id' => $createTerm->id,
                'language_id' => Language::where('symbol', $lang)->first()->id,
            ]);
        };

        foreach ($request->content_translations as $lang => $content) {
            $addContents = Term_Content::create([
                'content' => $content,
                'term_id' => $createTerm->id,
                'language_id' => Language::where('symbol', $lang)->first()->id,
            ]);
        };

        if ($request->sounds_translations)
            foreach ($request->sounds_translations as $lang => $sound) {
                $addSounds = Term_Sound::create([
                    'iframe' => $sound,
                    'term_id' => $createTerm->id,
                    'language_id' => Language::where('symbol', $lang)->first()->id,
                ]);
            };

        if ($createTerm)
            return $this->jsonData(true, true, 'Term has been added successfuly', [], []);
    }

    public function editIndex ($cat_id) {
        $term = Term::find($cat_id);
        return view('admin.words.edit')->with(compact('term'));
    }

    public function getWordById(Request $request) {
        $term = Term::with('category')->with('tags')->find($request->term_id);

        return $this->jsonData(true, true, '', [], $term);
    }

    public function getWordNames(Request $request) {
        $languages = Language::all();
        $symbols = $languages->pluck('symbol')->all();

        $term_names = Term::find($request->term_id)->names;
        $term_names_key_value = [];

        if ($term_names)
            foreach ($term_names as $key => $term_name) {
                $term_names_key_value[$term_name->language->symbol] = $term_name->term;
            };

        if ($term_names_key_value) :
            $missingLanguages = array_diff($symbols, array_keys($term_names_key_value));
            if ($missingLanguages)
                foreach ($missingLanguages as $lang) {
                    $term_names_key_value[$lang] = null;
                }
        endif;

        return $this->jsonData(true, true, '', [], $term_names_key_value);
    }

    public function getWordTitles(Request $request) {
        $languages = Language::all();
        $symbols = $languages->pluck('symbol')->all();

        $term_titles = Term::find($request->term_id)->titles;
        $term_titles_key_value = [];

        if ($term_titles)
            foreach ($term_titles as $key => $term_title) {
                $term_titles_key_value[$term_title->language->symbol] = $term_title->title;
            };

        if ($term_titles_key_value) :
            $missingLanguages = array_diff($symbols, array_keys($term_titles_key_value));
            if ($missingLanguages)
                foreach ($missingLanguages as $lang) {
                    $term_titles_key_value[$lang] = null;
                }
        endif;

        return $this->jsonData(true, true, '', [], $term_titles_key_value);
    }

    public function getWordContents(Request $request) {
        $languages = Language::all();
        $symbols = $languages->pluck('symbol')->all();

        $term_contents = Term::find($request->term_id)->contents;
        $term_contents_key_value = [];

        if ($term_contents)
            foreach ($term_contents as $key => $term_content) {
                $term_contents_key_value[$term_content->language->symbol] = $term_content->content;
            };

        if ($term_contents_key_value) :
            $missingLanguages = array_diff($symbols, array_keys($term_contents_key_value));
            if ($missingLanguages)
                foreach ($missingLanguages as $lang) {
                    $term_contents_key_value[$lang] = null;
                }
        endif;

        return $this->jsonData(true, true, '', [], $term_contents_key_value);
    }

    public function getWordSounds(Request $request) {
        $languages = Language::all();
        $symbols = $languages->pluck('symbol')->all();

        $term_sounds = Term::find($request->term_id)->sounds;
        $term_sounds_key_value = [];

        if ($term_sounds)
            foreach ($term_sounds as $key => $term_sound) {
                $term_sounds_key_value[$term_sound->language->symbol] = $term_sound->iframe;
            };

        if ($term_sounds_key_value) :
            $missingLanguages = array_diff($symbols, array_keys($term_sounds_key_value));
            if ($missingLanguages)
                foreach ($missingLanguages as $lang) {
                    $term_sounds_key_value[$lang] = null;
                }
        endif;

        return $this->jsonData(true, true, '', [], $term_sounds_key_value);
    }

    public function editTerm(Request $request) {
        $languages = Language::take(7)->get();
        $symbols = $languages->pluck('symbol')->all();
        $term = Term::find($request->term_id);

        $validator = Validator::make($request->all(), [
            'main_name' => ['required'],
        ], [
            'main_name.required' => 'Please write term main name',
            'main_cat' => 'Please choose category for your term'
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, true, 'Edit failed', [$validator->errors()->first()], []);
        }


        $missingTermsTranslations = array_diff($symbols, array_keys($request->term_translations));

        if (!empty($missingTermsTranslations)) {
            return $this->jsondata(false, true, 'Add failed', ['Please enter term name in (' . Language::where('symbol', reset($missingTermsTranslations))->first()->name . ')'], []);
        }

        $missingTitleTranslations = array_diff($symbols, array_keys($request->title_translations));

        if (!empty($missingTitleTranslations)) {
            return $this->jsondata(false, true, 'Add failed', ['Please enter term title in (' . Language::where('symbol', reset($missingTitleTranslations))->first()->name . ')'], []);
        }

        $missingContentTranslations = array_diff($symbols, array_keys($request->content_translations));

        if (!empty($missingContentTranslations)) {
            return $this->jsondata(false, true, 'Add failed', ['Please enter term content in (' . Language::where('symbol', reset($missingContentTranslations))->first()->name . ')'], []);
        }
        if (!$request->cat_id) {
            return $this->jsondata(false, true, 'Add failed', ['Please choose category for your term'], []);
        }
        if (Category::find($request->cat_id)->sub_categories()->count() > 0) {
            return $this->jsondata(false, true, 'Add failed', ['Please choose sub category for your term'], []);
        }

        $term->name = Str::ucfirst($request->main_name);
            if ($request->thumbnail)
            $term->thumbnail_path = $request->thumbnail;
        $term->category_id = $request->cat_id;
        $term->save();

        DB::table('term_tag')->where('term_id', $term->id)->delete();
        if ($request->tags)
        foreach ($request->tags as $tagName) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $term->tags()->attach($tag->id);
        }

        foreach ($request->term_translations as $lang => $term) {
            $lang_id = Language::where('symbol', $lang)->first()->id;
            $term_name = Term_Name::where('term_id', $request->term_id)->where('language_id', $lang_id)->first();

            if ($term_name) {
                $term_name->term = $term;
                $term_name->save();
            } else {
                $addNames = Term_Name::create([
                    'term' => $term,
                    'term_id' => $request->term_id,
                    'language_id' => Language::where('symbol', $lang)->first()->id,
                ]);
            }
        };

        foreach ($request->title_translations as $lang => $title) {
            $lang_id = Language::where('symbol', $lang)->first()->id;
            $term_title = Term_Title::where('term_id', $request->term_id)->where('language_id', $lang_id)->first();

            if ($term_title) {
                $term_title->title = $title;
                $term_title->save();
            } else {
                $addTitles = Term_Title::create([
                    'title' => $title,
                    'term_id' => $request->term_id,
                    'language_id' => Language::where('symbol', $lang)->first()->id,
                ]);
            }
        };

        foreach ($request->content_translations as $lang => $content) {
            $lang_id = Language::where('symbol', $lang)->first()->id;
            $term_content = Term_Content::where('term_id', $request->term_id)->where('language_id', $lang_id)->first();

            if ($term_content) {
                $term_content->content = $content;
                $term_content->save();
            } else {
                $addContents = Term_Content::create([
                    'content' => $content,
                    'term_id' => $request->term_id,
                    'language_id' => Language::where('symbol', $lang)->first()->id,
                ]);
            }
        };

        if ($request->sounds_translations)
            foreach ($request->sounds_translations as $lang => $sound) {
                $lang_id = Language::where('symbol', $lang)->first()->id;
                $term_sound = Term_Sound::where('term_id', $request->term_id)->where('language_id', $lang_id)->first();

                if ($term_sound) {
                    $term_sound->iframe = $sound;
                    $term_sound->save();
                } else {
                    if ($sound)
                        $addSounds = Term_Sound::create([
                            'iframe' => $sound,
                            'term_id' => $request->term_id,
                            'language_id' => Language::where('symbol', $lang)->first()->id,
                        ]);
                }
            };

        if ($term)
            return $this->jsonData(true, true, 'Term has been Updated successfuly', [], []);
    }

    public function delete(Request $request) {
        $validator = Validator::make($request->all(), [
            'term_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, true, 'Edit failed', [$validator->errors()->first()], []);
        }

        $term = Term::find($request->term_id);
        if ($term->thumbnail_path)
            File::delete(public_path('/dashboard/images/uploads/terms_thumbnail/' . $term->thumbnail_path));
        $term->names()->delete();
        $term->titles()->delete();
        $term->contents()->delete();
        $term->sounds()->delete();
        $term->delete();

        if ($term)
            return $this->jsonData(true, true, $request->file_name . ' Term has been deleted succussfuly', [], []);
    }
}
