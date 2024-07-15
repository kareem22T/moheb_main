<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Traits\DataFormController;
use App\Traits\SaveFileTrait;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use DataFormController;
    use SaveFileTrait;

    public function index() {
        return view('admin.comments.preview');
    }


    public function comments() {
        $terms = Comment::latest()->with('term')->paginate(10);

        return $this->jsonData(true, true, '', [], $terms);
    }

}
