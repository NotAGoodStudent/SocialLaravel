<?php

namespace App\Http\Controllers;

use App\Like;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function likePost($post_id)
    {
        $like = new Like();
        $like->user_id = auth()->user()->id;
        $like->post_id = $post_id;
        $like->save();
    }
}
