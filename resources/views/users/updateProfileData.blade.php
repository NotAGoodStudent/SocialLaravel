@extends('layouts.app')
@section('content')
    <link href="{{ asset('css/updateProfileStyle.css') }}" rel="stylesheet">
    <body>
    <div class="m-auto">
        <div class="card" style="background-color: #202020;">
            <form class="box"  enctype="multipart/form-data" method="post" action="{{ route('updateUserData', auth()->user()->id) }}">
            @csrf
                @method('PATCH')
                <h2 class="title">Edit Profile</h2>
                <div class="m-auto d-flex" style="width: 100%; position: center">
                    <div class="hideInput d-inline-block m-auto">
                        <input type="file" class="imageUpload m-auto" name="pfp" id="pfp" hidden>
                        <img src="{{Storage::url(auth()->user()->pfp)}}" id="uploadPFP" name="uploadPFP" class="uploadPFP m-auto d-flex" alt="">
                        <input type="file" class="backgroundUpload m-auto" name="background" id="background" hidden>
                        <img src="{{Storage::url(auth()->user()->background)}}" id="uploadBackground" name="uploadBAckground" class="uploadBackground m-auto d-flex" alt="">
                    </div>
                </div>
                <label class="mx-auto d-block labels" for="username">Username</label>
                <input class="mx-auto d-block" type="text" name="username" value="{{auth()->user()->username}}">
                @if(auth()->user()->bio == null)
                    <label class="mx-auto d-block labels" for="bio">Bio</label>
                    <textarea name="bio" class="txt-area mx-auto d-block" placeholder="Add a bio!"></textarea>
                @else
                    <label class="mx-auto d-block labels" for="bio">Bio</label>
                    <textarea class="txt-area mx-auto d-block" name="bio" placeholder="Add a bio!">{{auth()->user()->bio}}</textarea>
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
    <script>
        $(document).ready(function (){
            $('#uploadPFP').click(function (){
                $('#pfp').click();
            });

            $('#uploadBackground').click(function (){
                $('#background').click();
            });
        });

    </script>
@endsection
