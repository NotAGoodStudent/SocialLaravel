<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Retweet;
use App\Post;

class RetweetController extends Controller
{
    public function retweetPost($post_id)
    {
        $retweet = new Retweet();
        $retweet->user_id = auth()->user()->id;
        $retweet->post_id = $post_id;
        $retweet->save();
    }

    public function unretweetPost($post_id)
    {
        Retweet::where('user_id', '=', auth()->user()->id)->where('post_id', '=', $post_id)->delete();
    }
}
