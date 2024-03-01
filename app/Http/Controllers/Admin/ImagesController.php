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
use App\Models\Image;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImagesController extends Controller
{    
    use DataFormController;
    use SaveFileTrait;

    public function uploadeImg(Request $request) {
        $validator = Validator::make($request->all(), [
            'img' => ['required'],
        ], [
            'img.required' => 'Please uploade an valid image',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, true, 'upload failed', [$validator->errors()->first()], []);
        }

        $image = $this->saveFile($request->img, 'dashboard/images/uploads');
        if ($image)
            $upload_image = Image::create([
                'path' => $image
            ]);

        if ($upload_image)
            return $this->jsondata(true, true, 'Image have uploaded successfully', [], []);


        return $this->jsondata('false', true, 'uploade field', ['please uploade valid image'], []);

    }

    public function search(Request $request) {
        $images = Image::where('path', 'like', '%' . $request->search_words . '%')->orderby('id', 'desc')
                                ->paginate(15);
        
        return $this->jsonData(true, true, '', [], $images);

    }


    public function getImages() {
        
        $get_images = Image::orderby('id', 'desc')->paginate(15);

        if ($get_images)
            return $this->jsondata(true, true, '', [], $get_images);


        return $this->jsondata('false', true, 'there is no images yet field', ['please uploade images'], []);

    }
}
