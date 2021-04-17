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
use Illuminate\Support\Facades\Validator;
use function PHPUnit\Framework\isEmpty;

class UserController extends Controller
{

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
        } else
        {
            return true;
        };
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
                    if($request->file('pfp') != null && $request->file('background') != null) {
                        if ($request->filled('bio') && $request->filled('name') && $request->filled('surname') && $request->filled('email') && $request->filled('username') && $this->isImage($request->file('pfp')) && $this->isImage($request->file('background'))) {
                            if (auth()->user()->pfp != 'default.png' && auth()->user()->background != 'default.jpg') {
                                Storage::delete(auth()->user()->pfp);
                                Storage::delete(auth()->user()->background);
                            }
                            $vari = Storage::put('public/img/pfp', $request->file('pfp'));
                            $varx = Storage::put('public/img/background', $request->file('background'));
                            $user->pfp = $vari;
                            $user->background = $varx;
                            $user->username = $request->input('username');
                            $user->bio = $request->input('bio');
                            $user->name = $request->input('name');
                            $user->surname = $request->input('surname');
                            $user->email = $request->input('email');
                            $user->save();
                            return redirect()->route('modifyProfile')->with('message', 'Data updated successfully!');
                        } else return redirect()->route('modifyProfile')->with('message', 'Some fields might be empty!');
                    }

                        if($request->file('pfp') != null) {
                            if ($request->filled('bio') && $request->filled('name') && $request->filled('surname') && $request->filled('email') && $request->filled('username') && $this->isImage($request->file('pfp'))) {
                                if(auth()->user()->pfp != 'default.png'){
                                    Storage::delete(auth()->user()->pfp);
                                }
                                $vari = Storage::put('public/img/pfp', $request->file('pfp'));
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

                    if($request->file('background') != null) {
                        if ($request->filled('bio') && $request->filled('name') && $request->filled('surname') && $request->filled('email') && $request->filled('username') && $this->isImage($request->file('background'))) {
                            if(auth()->user()->background != 'default.jpg'){
                                Storage::delete(auth()->user()->background);
                            }
                            $vari = Storage::put('public/img/background', $request->file('background'));
                            $user->background = $vari;
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
                    else{
                        if ($request->filled('bio') && $request->filled('name') && $request->filled('surname') && $request->filled('email') && $request->filled('username')){

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
}
