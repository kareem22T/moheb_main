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
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;

class CategoriesController extends Controller
{
    use DataFormController;
    use SaveFileTrait;

    public function preview() {
        return view('admin.categories.preview');
    }

    public function getLanguages() {
        $languages = Language::all();
        
        return $this->jsonData(true, true, '', [], $languages);
    }

    public function getMainCategories() {
        $categories = Category::with('sub_categories')->where('cat_type', 0)->get();
        
        return $this->jsonData(true, true, '', [], $categories);
    }

    public function getSubCategories(Request $request) {
        $categories = Category::find($request->cat_id)->sub_categories;
        
        return $this->jsonData(true, true, '', [], $categories);
    }

    public function search(Request $request) {
        $languages = Category::with('sub_categories')->where('main_name', 'like', '%' . $request->search_words . '%')
                                ->orWhere('description', 'like', '%' . $request->search_words . '%')
                                ->paginate(10);

        $categories = Category::with('sub_categories')->whereHas('names', function ($query) use ($request) {
            $query->where('name', 'like', '%'.$request->search_words.'%');
        })->paginate(10);
        
        return $this->jsonData(true, true, '', [], !$languages->isEmpty() ? $languages : $categories);

    }

    public function addIndex() {
        return view('admin.categories.add');
    }

    public function add(Request $request) {
        $languages = Language::all();
        $symbols = $languages->pluck('symbol')->all();
        // add(category_translations)
        $validator = Validator::make($request->all(), [
            'main_name' => 'required|unique:categories,main_name',
            'cat_type' => 'required',
            'main_cat_id' => 'required_if:cat_type,1',
            'thumbnail' => 'required'
        ], [
            'main_name.required' => 'Please write section main name',
            'cat_type.required' => 'Please choose category type',
            'main_cat_id.required_if' => 'Please choose main category for your sub category',
            'thumbnail.required' => 'Please upload category thumbnail'
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, true, 'Add failed', [$validator->errors()->first()], []);
        }

        $missingLanguages = array_diff($symbols, array_keys($request->category_translations));

        if (!empty($missingLanguages)) {
            return $this->jsondata(false, true, 'Add failed', ['Please enter category name in (' . Language::where('symbol', reset($missingLanguages))->first()->name . ')'], []);
        }

        $createCategory = Category::create([
            'main_name' => Str::ucfirst($request->main_name),
            'description' => $request->description ? $request->description : null,
            'cat_type' => $request->cat_type,
            'main_cat_id' => $request->main_cat_id ? $request->main_cat_id : null,
            'thumbnail_path' => $request->thumbnail
        ]);


        foreach ($request->category_translations as $lang => $name) {
            $addNames = Category_Name::create([
                'name' => $name,
                'category_id' => $createCategory->id,
                'language_id' => Language::where('symbol', $lang)->first()->id,
            ]);
        };

        if ($createCategory)
            return $this->jsonData(true, true, 'Category has been added successfuly', [], []);
    }

    public function editIndex ($cat_id) {
        $category = Category::find($cat_id);
        return view('admin.categories.edit')->with(compact('category'));
    }    

    public function getCategoryById(Request $request) {
        $category = Category::find($request->category_id);
        
        return $this->jsonData(true, true, '', [], $category);
    }

    public function getCategoryNames(Request $request) {
        $languages = Language::all();
        $symbols = $languages->pluck('symbol')->all();
        
        $category_names = Category::find($request->category_id)->names;
        $category_names_key_value = [];

        if ($category_names)
            foreach ($category_names as $key => $cat_name) {
                $category_names_key_value[$cat_name->language->symbol] = $cat_name->name;
            };

        if ($category_names_key_value) :
            $missingLanguages = array_diff($symbols, array_keys($category_names_key_value));
            if ($missingLanguages)
                foreach ($missingLanguages as $lang) {
                    $category_names_key_value[$lang] = null;
                }
        endif;

        return $this->jsonData(true, true, '', [], $category_names_key_value);
    }

    public function editCategory(Request $request) {
        $languages = Language::all();
        $symbols = $languages->pluck('symbol')->all();
        $category = Category::find($request->category_id);

        $validator = Validator::make($request->all(), [
            'main_name' => ['required', Rule::unique('categories')->ignore($category->id)],
        ], [
            'main_name.required' => 'Please write section main name',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, true, 'Add failed', [$validator->errors()->first()], []);
        }

        $missingLanguages = array_diff($symbols, array_keys($request->category_translations));

        if (!empty($missingLanguages)) {
            return $this->jsondata(false, true, 'Add failed', ['Please enter category name in (' . Language::where('symbol', reset($missingLanguages))->first()->name . ')'], []);
        }
        $category->main_name = Str::ucfirst($request->main_name);
        if ($request->thumbnail)
            $category->thumbnail_path = $request->thumbnail;
        $category->description = $request->description ? $request->description : null;
        $category->save();

        foreach ($request->category_translations as $lang => $name) {
            $lang_id = Language::where('symbol', $lang)->first()->id;
            $cat_name = Category_Name::where('category_id', $category->id)->where('language_id', $lang_id)->first();
            if ($cat_name) {
                $cat_name->name = $name;
                $cat_name->save();
            } else {
                $addNames = Category_Name::create([
                    'name' => $name,
                    'category_id' => $category->id,
                    'language_id' => Language::where('symbol', $lang)->first()->id,
                ]);
            }
        };

        if ($category)
            return $this->jsonData(true, true, 'Category has been Updated successfuly', [], []);
    }

    public function getCategoryIndex ($cat_id) {
        $category = Category::with('sub_categories')->find($cat_id);
        return view('admin.categories.category')->with(compact('category'));
    }

    public function delete(Request $request) {
        $validator = Validator::make($request->all(), [
            'cat_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, true, 'Edit failed', [$validator->errors()->first()], []);
        }

        $category = Category::find($request->cat_id);
        foreach ($category->terms() as $term) {
            if ($term->thumbnail_path)
                File::delete(public_path('/dashboard/images/uploads/terms_thumbnail/' . $term->thumbnail_path));
            $term->names()->delete();
            $term->titles()->delete();
            $term->contents()->delete();
            $term->sounds()->delete();
            $term->delete();
        }
        foreach ($category->articles() as $article) {
            if ($article->thumbnail_path)
                File::delete(public_path('/dashboard/images/uploads/articles_thumbnail/' . $article->thumbnail_path));
            $article->titles()->delete();
            $article->contents()->delete();
            $article->delete();
        }
        foreach ($category->sub_categories() as $subcategory) {
            foreach ($subcategory->terms() as $term) {
                if ($term->thumbnail_path)
                    File::delete(public_path('/dashboard/images/uploads/terms_thumbnail/' . $term->thumbnail_path));
                $term->names()->delete();
                $term->titles()->delete();
                $term->contents()->delete();
                $term->sounds()->delete();
                $term->delete();
            }
            foreach ($subcategory->articles() as $article) {
                if ($article->thumbnail_path)
                    File::delete(public_path('/dashboard/images/uploads/articles_thumbnail/' . $article->thumbnail_path));
                $article->titles()->delete();
                $article->contents()->delete();
                $article->delete();
            }
            File::delete(public_path('/dashboard/images/uploads/categories_thumbnail/' . $subcategory->thumbnail_path));
            $subcategory->delete();
        }
        File::delete(public_path('/dashboard/images/uploads/categories_thumbnail/' . $category->thumbnail_path));
        $category->delete();

        if ($category)
            return $this->jsonData(true, true, $request->file_name . 'Category has been deleted succussfuly', [], []);
    }
}
