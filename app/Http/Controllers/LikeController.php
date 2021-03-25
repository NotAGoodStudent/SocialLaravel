<?php

namespace App\Http\Controllers;

use App\Like;
use App\Post;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function likePost()
    {
        $id = $_POST['post_id'];
        $uid = $_POST['user_id'];
        $user = User::findOrFail($uid);
        $like = new Like();
        $like->user_id = auth()->user()->id;
        $like->post_id = $id;
        $like->save();
        return redirect()->route('profile',[$user->username]);

    }
}
