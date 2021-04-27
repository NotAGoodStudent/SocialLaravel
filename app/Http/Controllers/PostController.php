<?php

namespace App\Http\Controllers;

use App\Post;
use App\Topic;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use function PHPUnit\Framework\isNull;

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
    public function makePost(Request $request, $postAnsweredID, $comesFromReplyTab)
    {

        if($comesFromReplyTab == 0) {
            if ($postAnsweredID == 0) {

                $post = new Post();
                $post->owner = Auth::id();
                if ($request->file('uploadImage') != null) {
                    if ($this->isImage($request->file('uploadImage'))) {
                        $postImg = Storage::put('public/img/postImg', $request->file('uploadImage'));
                        $post->image = $postImg;
                    }
                }
                $post->content = $request->input('post');
                $post->post_rep_id = null;
                $content = explode(" ", $request->input('post'));
                $topicStr = null;
                $found = false;
                foreach ($content as $c) {
                    if (preg_match_all('/^\s*#([\p{Pc}\p{N}\p{L}\p{Mn}]{1,50})$/um', $c)) {
                        $topicStr = $c;
                        $found = true;
                        break;
                    }
                }

                if ($found) {
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
                } else {
                    $post->topic_id = null;
                    $post->save();
                    return redirect()->route('home');
                }


            }
            else {
                $post = new Post();
                $post->owner = Auth::id();
                if ($request->file('uploadImage' . $postAnsweredID) != null) {
                    if ($this->isImage($request->file('uploadImage' . $postAnsweredID))) {
                        $postImg = Storage::put('public/img/postImg', $request->file('uploadImage' . $postAnsweredID));
                        $post->image = $postImg;
                    }
                }
                $post->content = $request->input('post');
                $post->post_rep_id = $postAnsweredID;
                $content = explode(" ", $request->input('post'));
                $topicStr = null;
                $found = false;
                foreach ($content as $c) {
                    if (preg_match_all('/^\s*#([\p{Pc}\p{N}\p{L}\p{Mn}]{1,50})$/um', $c)) {
                        $topicStr = $c;
                        $found = true;
                        break;
                    }
                }

                if ($found) {
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
                } else {
                    $post->topic_id = null;
                    $post->save();
                    return redirect()->route('home');
                }
            }
        }
        else
        {
            if ($postAnsweredID == 0) {

                $post = new Post();
                $post->owner = Auth::id();
                if ($request->file('uploadImage') != null) {
                    if ($this->isImage($request->file('uploadImage'))) {
                        $postImg = Storage::put('public/img/postImg', $request->file('uploadImage'));
                        $post->image = $postImg;
                    }
                }
                $post->content = $request->input('post');
                $post->post_rep_id = null;
                $content = explode(" ", $request->input('post'));
                $topicStr = null;
                $found = false;
                foreach ($content as $c) {
                    if (preg_match_all('/^\s*#([\p{Pc}\p{N}\p{L}\p{Mn}]{1,50})$/um', $c)) {
                        $topicStr = $c;
                        $found = true;
                        break;
                    }
                }

                if ($found) {
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
                } else {
                    $post->topic_id = null;
                    $post->save();
                    return redirect()->route('home');
                }


            }
            else {
                $post = new Post();
                $post->owner = Auth::id();
                if ($request->file('uploadImage' . $postAnsweredID) != null) {
                    if ($this->isImage($request->file('uploadImage' . $postAnsweredID))) {
                        $postImg = Storage::put('public/img/postImg', $request->file('uploadImage' . $postAnsweredID));
                        $post->image = $postImg;
                    }
                }
                $post->content = $request->input('post');
                $post->post_rep_id = $postAnsweredID;
                $content = explode(" ", $request->input('post'));
                $topicStr = null;
                $found = false;
                foreach ($content as $c) {
                    if (preg_match_all('/^\s*#([\p{Pc}\p{N}\p{L}\p{Mn}]{1,50})$/um', $c)) {
                        $topicStr = $c;
                        $found = true;
                        break;
                    }
                }

                if ($found) {
                    $post = Post::where('id', '=', $postAnsweredID)->first();
                    if (Topic::where('topic', '=', $topicStr)->exists()) {
                        $topic = Topic::where('topic', '=', $topicStr)->first();
                        $post->topic_id = $topic->id;
                        $post->save();
                        return redirect()->route('showReplies',$postAnsweredID);
                    } else {
                        $topic = new Topic();
                        $topic->topic = $topicStr;
                        $topic->save();
                        $post->topic_id = $topic->id;
                        $post->save();
                        return redirect()->route('showReplies',$postAnsweredID);
                    }
                } else {
                    $post->topic_id = null;
                    $post->save();
                    return redirect()->route('showReplies',$postAnsweredID);
                }
            }
        }
    }

    public function showPostReplies($id)
    {
       $post = Post::where('id', '=', $id)->first();
       $replies = Post::where('post_rep_id', '=', $id)->get();
       $users = User::all();
       return view('post.post_replies', compact('post','users', 'replies'));
    }


}
