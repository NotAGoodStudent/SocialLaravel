@extends('layouts.app')
@section('content')
    <link href="{{ asset('css/updateProfileStyle.css') }}" rel="stylesheet">
    <body>
    <div class="m-auto">
        <div class="card" style="background-color: #202020;">
            <form class="box" method="post" action="{{ route('updateUserData', auth()->user()->username) }}">
            @csrf
                @method('PATCH')
                <h2 class="title">Edit Profile</h2>
                <img class="mx-auto d-block" style="width: 200px; height: 200px; border-radius: 50%" src="https://pngimage.net/wp-content/uploads/2018/06/no-photo-avatar-png-6.png">
                <label class="mx-auto d-block labels" for="username">Username</label>
                <input class="mx-auto d-block" type="text" name="username" value="{{auth()->user()->username}}">
                @if(auth()->user()->bio == null)
                    <label class="mx-auto d-block labels" for="bio">Bio</label>
                    <textarea name="bio" class="txt-area mx-auto d-block" placeholder="Add a bio!"></textarea>
                @else
                    <label class="mx-auto d-block labels" for="bio">Bio</label>
                    <textarea class="txt-area mx-auto d-block" placeholder="Add a bio!">{{auth()->user()->bio}}</textarea>
                @endif
                <label class="mx-auto d-block labels" for="name">Name</label>
                <input class="mx-auto d-block" type="text" name="name" value="{{auth()->user()->name}}">
                <label class="mx-auto d-block labels" for="surname">Surname</label>
                <input class="mx-auto d-block" type="text" name="surname" value="{{auth()->user()->surname}}">
                <label class="mx-auto d-block labels" for="email">Email</label>
                <input class="mx-auto d-block" type="email" name="email" value="{{auth()->user()->email}}">
                <input type="submit" name="update" value="Update!">
                @if($message ?? '')
                    <p style="color: red; text-align: center">{{$message}}</p>
                @endif
            </form>
        </div>
    </div>
    </body>
@endsection
