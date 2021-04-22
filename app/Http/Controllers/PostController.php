<?php

namespace App\Http\Controllers;

use App\Post;
use App\Topic;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */

    public function getPost($post_id)
    {
        return Post::where('id', '=', $post_id)->first();
    }

    public function isImage($img)
    {

        $rules = array(
            'image' => 'mimes:jpeg,jpg,png|required|max:10000' // max 10000kb
        );


        $fileArr = array('image'=>$img);
        $validator = Validator::make($fileArr, $rules);

        // Check to see if validation fails or passes
        if ($validator->fails())
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    //We get the request and the id of the user, in orfer to check if the post contains a hashtag we split the content and iterate through it
    //checking if it contains a topic starting with a '#', we are able to do that thanks to the regex, if it does it'll get that record from the database and
    //assign it to the post->topic_id else it'll create a new topic with the hashtag's name
    public function makePost(Request $request, $id)
    {
        $post = new Post();
        $post->owner = $id;
        if($request->file('uploadImage') != null) {
            if ($this->isImage($request->file('uploadImage'))) {
                $postImg = Storage::put('public/img/postImg', $request->file('uploadImage'));
                $post->image = $postImg;
            }
        }
        $post->content = $request->input('post');
        $content = explode(" ", $request->input('post'));
        $topicStr = null;
        $found = false;
        foreach ($content as $c)
        {
            if(preg_match_all('/^\s*#([\p{Pc}\p{N}\p{L}\p{Mn}]{1,50})$/um', $c))
            {
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
                return redirect()->route('home');
            } else {
                $topic = new Topic();
                $topic->topic = $topicStr;
                $topic->save();
                $post->topic_id = $topic->id;
                $post->save();
                return redirect()->route('home');
            }
        }
        else
            {
                $post->topic_id = null;
                $post->save();
                return redirect()->route('home');
            }


    }

}
