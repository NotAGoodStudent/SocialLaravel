<?php

namespace App\Http\Controllers;

use App\Post;
use App\Topic;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function makePost(Request $request, $id)
    {
        $post = new Post();
        $post->owner = $id;
        $post->content = $request->input('post');
        $post->likes = 0;
        $post->retweets = 0;
        $content = explode(" ", $request->input('post'));
        $topicStr = null;
        $found = false;
        echo count($content);
        foreach ($content as $c)
        {
            echo $c .' ';
            if(preg_match_all('/^\s*#([\p{Pc}\p{N}\p{L}\p{Mn}]{1,50})$/um', $c))
            {
                echo 'matches';
                echo $c . 'looping';
                $topicStr = $c;
                $found = true;
                break;
            }
        }

        if($found) {
            if (Topic::where('topic', '=', $topicStr)->exists()) {
                $topic = Topic::where('topic', '=', $topicStr)->first();
                $post->topic_id = $topic->id;
                $post->save();
                //return redirect()->route('home');
            } else {
                $topic = new Topic();
                $topic->topic = $topicStr;
                $topic->save();
                $post->topic_id = $topic->id;
                $post->save();
                //return redirect()->route('home');
            }
        }
        else
            {
                $post->topic_id = null;
                $post->save();
                //return redirect()->route('home');
            }


    }
}
