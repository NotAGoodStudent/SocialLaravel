@extends('layouts.app')

@section('content')
    <link href="{{ asset('css/profileStyle.css') }}" rel="stylesheet">
    <div class="m-auto" style="width: 100%">
        <div class="profile_container m-auto">
            <div class="content m-auto">
                <div class="username">
                    <h2 class="m-auto">{{$user->username}}</h2>
                    <h5 class="text-muted m-auto"><span style="color: white">{{$user->posts}}</span> Posts</h5>
                    <h5 class="text-muted m-auto"><span style="color: white">{{$user->followers}}</span> Followers</h5>
                    <h5 class="text-muted m-auto"><span style="color: white">{{$user->following}}</span> Following</h5>
                    <img class="profile_img m-auto" src="https://pngimage.net/wp-content/uploads/2018/06/no-photo-avatar-png-6.png" alt="">
                </div>
                <img class="background_img" src="https://steamuserimages-a.akamaihd.net/ugc/940586530515504757/CDDE77CB810474E1C07B945E40AE4713141AFD76/" alt="">
                <div class="buttonProf">
                    <div class="calCont">
                    <h5 class="m-auto text-muted">
                        <span class="cal">
                        <i class="fas fa-calendar-alt text-muted" style="font-weight: bold; margin-right: 3px"></i>
                        Joined <span style="color: white">{{$user->created_at->format('m/d/Y')}}</span>
                     </span></h5>
                    </div>
                    <div class="bioCont">
                    <h4 class="bio m-auto">{{$user->bio}}</h4>
                    </div>
                    <!--How much time has it been since user created!-->
                    {{--{{$user->created_at->diffForHumans()}}--}}

                    <div class="editCont">
                    <a class="button m-auto" href="{{ route('modifyProfile') }}">Edit</a>
                    </div>
                </div>
                <hr class="text-muted" style=" margin-top: 50px;border: 1px solid; text-align: center">
                <div class="options">
                    <div class="posts">
                        <a class="links m-auto" href="{{ route('modifyProfile') }}">Posts</a>
                    </div>
                    <div class="likes">
                        <a class="links m-auto" href="{{ route('modifyProfile') }}">Likes</a>
                    </div>
                    <div class="retweets">
                        <a class="links m-auto" href="{{ route('modifyProfile') }}">Retweets</a>
                    </div>
                </div>
                <hr class="text-muted" style=" margin-top: 50px;border: 1px solid; text-align: center">
                <div class="data m-auto col-md-12">
                    @foreach($posts as $p)
                        <div class="post m-auto">
                            <div class="userData">
                                <img src="https://pngimage.net/wp-content/uploads/2018/06/no-photo-avatar-png-6.png" alt="">
                                <p class="postUsername">{{$user->username}} <span class="text-muted" style="font-size: 15px">{{'@'.$user->username}} . {{$p->created_at->diffForHumans()}}</span></p>
                                <p class="postContent">{{$p->content}}</p>
                            </div>
                            <div class="col-md-12">
                                <ul class="social-network social-circle">
                                    <li><a href="#" class="icoFacebook" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="#" class="icoTwitter" title="Twitter"><i class="fab fa-twitter"></i></a></li>
                                    <li><a href="#" class="icoGoogle" title="Google +"><i class="fab fa-google-plus"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
