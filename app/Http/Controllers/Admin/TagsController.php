<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\DataFormController;
use App\Traits\SaveFileTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Tag;

class TagsController extends Controller
{
    use DataFormController;
    use SaveFileTrait;

    public function preview() {
        return view('admin.tags.preview');
    }

    public function getTags() {
        $Tags = Tag::paginate(10);
        
        return $this->jsonData(true, true, '', [], $Tags);
    }

    public function search(Request $request) {
        $Tags = Tag::where('name', 'like', '%' . $request->search_words . '%')
                                ->paginate(10);
        
        return $this->jsonData(true, true, '', [], $Tags);

    }

    public function addIndex() {
        return view('admin.tags.add');
    }

    public function add(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:tags,name',
        ], [
            'name.required' => 'please enter Tag name',
            'name.unique' => 'Tag name is already exist',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, true, 'Add failed', [$validator->errors()->first()], []);
        }

        $createTag = Tag::create([
            'name' => $request->name
        ]);

        if ($createTag)
            return $this->jsonData(true, true, 'Tag has been added successfuly', [], []);
    }

    public function editTag(Request $request) {
        $validator = Validator::make($request->all(), [
            'tag_id' => 'required',
            'tag_name' => 'required',
        ], [
            'tag_name.required' => 'please enter tag name'
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, true, 'Edit failed', [$validator->errors()->first()], []);
        }

        $Tag = Tag::find($request->tag_id);
        $Tag->name = $request->tag_name;
        $Tag->save();

        if ($Tag)
            return $this->jsonData(true, true, $request->file_name . ' Tag has been updated succussfuly', [], []);
    }

    public function delete(Request $request) {
        $validator = Validator::make($request->all(), [
            'tag_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, true, 'Edit failed', [$validator->errors()->first()], []);
        }

        $Tag = Tag::find($request->tag_id);
        foreach ($Tag->articles as $article) {
            $article->tags()->detach($tag->id);
            $article->save();
        }
        foreach ($Tag->terms as $term) {
            $term->tags()->detach($tag->id);
            $term->save();
        }
        $Tag->delete();

        if ($Tag)
            return $this->jsonData(true, true, $request->file_name . ' Tag has been deleted succussfuly', [], []);
    }
}
