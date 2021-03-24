<?php

namespace App\Http\Controllers;

use App\Follower;
use App\Following;
use App\Post;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function returnModifyProfileView($username)
    {
        return view('users.updateProfileData');
    }

    public function returnProfile($username)
    {
        $user = User::where('username', '=', $username)->first();
        return view('users.profile', compact('user'));
    }

    public function followUser($id, $id2)
    {
        $user = User::findOrFail($id2);
        $follow = new Following();
        $follow->user_id = $id;
        $follow->followed_id = $id2;
        $follow->save();
        $follower = new Follower();
        $follower->user_id = $id2;
        $follower->follower_id = $id;
        $follower->save();
        return redirect()->route('profile',[$user->username]);
    }

    public function unfollowUser($id, $id2)
    {
        Following::where('user_id', '=', $id)->where('followed_id', '=', $id2)->delete();
        Follower::where('user_id', '=', $id2)->where('follower_id', '=', $id)->delete();
        $user = User::findOrFail($id2);
        return redirect()->route('profile',[$user->username]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUserData(Request $request, $id)
    {
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
