<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\DataFormController;
use App\Traits\SaveFileTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Language;

class LanguagesController extends Controller
{
    use DataFormController;
    use SaveFileTrait;

    public function preview() {
        return view('admin.languages.preview');
    }

    public function getLanguages() {
        $languages = Language::paginate(10);
        
        return $this->jsonData(true, true, '', [], $languages);
    }

    public function search(Request $request) {
        $languages = Language::where('symbol', 'like', '%' . $request->search_words . '%')
                                ->orWhere('name', 'like', '%' . $request->search_words . '%')
                                ->paginate(10);
        
        return $this->jsonData(true, true, '', [], $languages);

    }

    public function addIndex() {
        return view('admin.languages.add');
    }

    public function add(Request $request) {
        $validator = Validator::make($request->all(), [
            'symbol' => 'required|unique:languages,symbol',
            'name' => 'required|unique:languages,name',
        ], [
            'symbol.required' => 'please enter language symbol',
            'name.required' => 'please enter language name',
            'symbol.unique' => 'language symbol is already exist',
            'name.unique' => 'language name is already exist',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, true, 'Add failed', [$validator->errors()->first()], []);
        }

        $createLang = Language::create([
            'symbol' => Str::upper($request->symbol),
            'name' => Str::ucfirst($request->name)
        ]);

        if ($createLang)
            return $this->jsonData(true, true, 'Language has been added successfuly', [], []);
    }

    public function contentIndex() {
        return view('admin.languages.content');
    }

    public function updateContentFile(Request $request) {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:json',
        ], [
            'file.required' => 'Please upload the content json file',
            'file.mimes' => 'Just json file is accepted',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, true, 'update failed', [$validator->errors()->first()], []);
        }

        $update = $this->saveFile($request->file, 'json', $request->file_name);
        if ($update)
            return $this->jsonData(true, true, $request->file_name . ' content has been Updated successfuly', [], []);
    }

    public function editLang(Request $request) {
        $validator = Validator::make($request->all(), [
            'lang_id' => 'required',
            'lang_symbol' => 'required',
            'lang_name' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, true, 'Edit failed', [$validator->errors()->first()], []);
        }

        $language = Language::find($request->lang_id);
        $language->symbol = Str::upper($request->lang_symbol);
        $language->name = Str::ucfirst($request->lang_name);
        $language->save();

        if ($language)
            return $this->jsonData(true, true, $request->file_name . ' Language has been updated succussfuly', [], []);
    }

    public function delete(Request $request) {
        $validator = Validator::make($request->all(), [
            'lang_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, true, 'Edit failed', [$validator->errors()->first()], []);
        }

        $language = Language::find($request->lang_id);
        $language->category_names()->delete();
        $language->term_names()->delete();
        $language->term_titles()->delete();
        $language->term_contents()->delete();
        $language->term_sounds()->delete();
        $language->article_titles()->delete();
        $language->article_contents()->delete();
        $language->delete();

        if ($language)
            return $this->jsonData(true, true, $request->file_name . ' Language has been deleted succussfuly', [], []);
    }
}
