@extends('layouts.app')

@section('content')
    <link href="{{ asset('css/profileStyle.css') }}" rel="stylesheet">
    <div class="m-auto" style="width: 100%">
        <div class="profile_container m-auto">
            <div class="content m-auto">
                <div class="username">
                    <h2 class="m-auto">{{$user->username}}</h2>
                    <h5 class="text-muted m-auto">{{$user->posts}} Posts</h5>
                    <h5 class="text-muted m-auto">{{$user->followers}} Followers</h5>
                    <h5 class="text-muted m-auto">{{$user->followers}} Following</h5>
                    <img class="profile_img m-auto" src="https://pngimage.net/wp-content/uploads/2018/06/no-photo-avatar-png-6.png" alt="">
                </div>
                <img class="background_img" src="https://cdnb.artstation.com/p/assets/images/images/024/538/827/original/pixel-jeff-clipa-s.gif?1582740711" alt="">
                <div class="buttonProf">
                    <h5 class="bio m-auto">{{$user->bio}}</h5>
                    <!--How much time has it been since user created!-->
                    {{--{{$user->created_at->diffForHumans()}}--}}
                    <h5 class="m-auto text-muted">
                        <span class="cal">
                        <i class="fas fa-calendar-alt text-muted" style="font-weight: bold; margin-right: 3px"></i>
                        Joined {{$user->created_at->format('m/d/Y')}}
                     </span></h5>

                    <a class="button m-auto" href="{{ route('modifyProfile') }}">Edit</a>
                </div>

            </div>
        </div>
    </div>
@endsection
