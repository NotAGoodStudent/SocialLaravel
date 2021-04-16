<?php

namespace App\Http\Controllers;

use App\Follower;
use App\Following;
use App\Post;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
class UserController extends Controller
{

    public function isImage($img)
    {
        $allowed = array(
            '.jpg',
            '.jpeg',
            '.gif',
            '.png',
            '.flv'
        );
        if (!in_array(strtolower(strrchr($img, '.')), $allowed)) {
            return false;
        }else {
            echo 'validated';
            return true;
        }
    }

    public function getUsers()
    {
        $users = User::all();
        return $users;
    }
    public function returnModifyProfileView()
    {
        return view('users.updateProfileData');
    }

    public function returnProfile($username)
    {
        $user = User::where('username', '=', $username)->first();
        $posts = Post::all();
        $users = User::all();
        return view('users.profile', compact('user', 'posts', 'users'));
    }

    public function followUser($id2)
    {
        $user = User::findOrFail($id2);
        $follow = new Following();
        $follow->user_id = auth()->user()->id;
        $follow->followed_id = $id2;
        $follow->save();
        $follower = new Follower();
        $follower->user_id = $id2;
        $follower->follower_id = auth()->user()->id;
        $follower->save();
    }

    public function unfollowUser($id2)
    {
        Following::where('user_id', '=', auth()->user()->id)->where('followed_id', '=', $id2)->delete();
        Follower::where('user_id', '=', $id2)->where('follower_id', '=', auth()->user()->id)->delete();
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */

    public function updateUserData(Request $request, $id)
    {
        //dump($request->ip());
        //diplays all thr request data and stops the execution of the code!
        //dd($request);
        $user = User::findOrFail($id);
        if(User::where('username', '=', $request->input('username'))->where('id', '!=', $id)->exists())
        {
            return redirect()->route('modifyProfile')->with('message', 'The desired username is already taken!');
        }
        else
            {
                if(User::where('email', '=', $request->input('email'))->where('id', '!=', $id)->exists())
                {
                    return redirect()->route('modifyProfile')->with('message', 'The desired email is already taken!');
                }
                else{
                    if($request->filled('bio') && $request->filled('name') && $request->filled('surname') && $request->filled('email') && $request->filled('username'))
                    {
                        $getExtension = $request->file('pfp')->getClientOriginalExtension();
                       /* if(auth()->user()->pfp != 'default.png'){
                            Storage::delete('public/img/pfp/', auth()->user()->pfp);
                        }*/
                        $fName = auth()->user()->id.$request->get('pfp');
                        $vari = Storage::put('public/img/pfp', $request->file('pfp'));
                        print_r($vari);
                        //Storage::move('public/img/pfp/'.$request->file('pfp'), 'public/img/pfp/'.'pfp'.auth()->user()->id).'.'.$getExtension;
                        $user->pfp = $vari;
                        $user->username = $request->input('username');
                        $user->bio = $request->input('bio');
                        $user->name = $request->input('name');
                        $user->surname = $request->input('surname');
                        $user->email = $request->input('email');
                        $user->save();
                        return redirect()->route('modifyProfile')->with('message', 'Data updated successfully!');
                    }
                    else return redirect()->route('modifyProfile')->with('message', 'Some fields might be empty!');
                }
            }

    }
}
