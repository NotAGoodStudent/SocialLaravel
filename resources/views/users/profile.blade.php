@extends('layouts.app')

@section('content')
    <link href="{{ asset('css/profileStyle.css') }}" rel="stylesheet">
    <div class="m-auto" style="width: 100%; " >
        <div class="profile_container m-auto" style="background-color: #060606">
            <div class="content m-auto">
                <div class="username">
                    <h2 class="m-auto">{{$user->username}}</h2>
                    <h5 class="text-muted m-auto"><span style="color: white">{{count($user->posts)}}</span> Posts</h5>
                    <h5 class="text-muted m-auto"><span id="followerCounter" style="color: white">{{count($user->followers)}}</span> Followers</h5>
                    <h5 class="text-muted m-auto"><span id="followingCounter" style="color: white">{{count($user->following)}}</span> Following</h5>
                    <img class="profile_img m-auto" src="https://pngimage.net/wp-content/uploads/2018/06/no-photo-avatar-png-6.png" alt="">
                </div>
                <img class="background_img" src="https://steamuserimages-a.akamaihd.net/ugc/940586530515504757/CDDE77CB810474E1C07B945E40AE4713141AFD76/" alt="">
                <div class="buttonProf">
                    <div class="calCont">
                    <h5 class="m-auto text-muted">
                        <span class="cal">
                        <i class="fas fa-calendar-alt text-muted" style="font-weight: bold; margin-right: 3px"></i>
                        Joined <span style="color: white">{{$user->created_at->format('d/m/Y')}}</span>
                     </span></h5>
                    </div>
                    <div class="bioCont">
                    <h4 class="bio m-auto">{{$user->bio}}</h4>
                    </div>
                    <!--How much time has it been since user created!-->
                    {{--{{$user->created_at->diffForHumans()}}--}}

                    <div class="editCont">
                        @if(Auth::user()->id == $user->id)
                            <a class="button m-auto" href="{{ route('modifyProfile')}}">Edit</a>
                        @endif
                        @if(count(Auth::user()->following) > 0 && Auth::user()->id != $user->id)
                            @php
                            $exists = true;
                             @endphp
                            @foreach(Auth::user()->following as $f)
                                @if($f->id == $user->id)
                                    <a class="is-followed m-auto" id="f{{$user->id}}" onclick="follow({{$user->id}})">Following</a>
                                        @php
                                            $exists = false;
                                        @endphp
                                    @break
                                    @endif
                            @endforeach
                            @if($exists == true)
                                    <a class="followButton m-auto" id="f{{$user->id}}" onclick="follow({{$user->id}})">Follow</a>
                                @endif
                            @elseif(Auth::user()->id != $user->id)
                                <a class="followButton m-auto" id="f{{$user->id}}" onclick="follow({{$user->id}})">Follow</a>
                            @endif
                    </div>
                </div>
                <hr class="text-muted" style=" margin-top: 50px;border: 1px solid; text-align: center">
                <div class="options">
                    <div class="posts">
                        <a class="links m-auto" style="cursor: pointer" onclick="showPosts()">Posts</a>
                    </div>
                    <div class="likes">
                        <a class="links m-auto" style="cursor: pointer" onclick="showLikes()">Likes</a>
                    </div>
                    <div class="retweets">
                        <a class="links m-auto" href="{{ route('modifyProfile')}}">Retweets</a>
                    </div>
                </div>
                <hr class="text-muted" style=" margin-top: 50px;border: 1px solid; text-align: center">
                <div class="data m-auto col-md-12" id="myPostsP">
                    @foreach($user->posts as $p)
                        <div class="postP m-auto">
                            <div class="userData">
                                <img src="https://pngimage.net/wp-content/uploads/2018/06/no-photo-avatar-png-6.png" alt="">
                                <div class="postText">
                                    <p class="postUsername">{{$user->username}} <span class="text-muted" style="font-size: 15px">{{'@'.$user->username}} . {{$p->created_at->diffForHumans()}}</span></p>
                                    <p class="postContent">{{$p->content}}</p>
                                </div>
                            </div>
                            <div class="hideIconsP col-md-12">
                                <ul class="social-network social-circle">
                                    <li><a class="comment"><i class="far fa-comment"></i></a></li>
                                    <!--CHECK IF POST IS RETWEETED BY CURRENT USER-->
                                    @if(count($p->retweets) > 0)
                                        @php
                                            $retweeted = false;
                                        @endphp
                                        @foreach($p->retweets as $r)
                                            @if(auth()->user()->id == $r->user_id)
                                                <li><a class="is-retweeted" id="r{{$p->id}}" onclick="retweet({{$p->id}})"><i class="fas fa-retweet"></i></a></li>
                                                @php
                                                    $retweeted = true;
                                                @endphp
                                                @break
                                            @endif
                                        @endforeach
                                        @if(!$retweeted)
                                            <li><a class="retweet" id="r{{$p->id}}" onclick="retweet({{$p->id}})"><i class="fas fa-retweet"></i></a></li>
                                        @endif
                                    @else
                                        <li><a class="retweet" id="r{{$p->id}}" onclick="retweet({{$p->id}})"><span class="text-muted">{{count($p->retweets)}}</span><i class="fas fa-retweet"></i></a></li>
                                    @endif

                                    <!--CHECK IF POST IS LIKED BY CURRENT USER-->
                                    @if(count($p->likes) > 0)
                                        @php
                                            $liked = false;
                                            @endphp
                                    @foreach($p->likes as $l)
                                        @if(auth()->user()->id == $l->user_id)
                                        <li><a class="is-liked" id="l{{$p->id}}" onclick="like({{$p->id}})"></a></li>
                                                @php
                                                    $liked = true;
                                                @endphp
                                            @break
                                        @endif
                                    @endforeach
                                        @if(!$liked)
                                            <li><a class="like" id="l{{$p->id}}" onclick="like({{$p->id}})"></a></li>
                                        @endif
                                    @else
                                        <li><a class="like" id="l{{$p->id}}" onclick="like({{$p->id}})"></a></li>
                                    @endif

                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
<!--                HIDDEN UNTIL LIKE IS CLICKED-->

                <div class="data m-auto col-md-12" id="myPostsL" style="display: none">
                    @foreach($posts as $p)

                                <!--CHECK IF POST IS LIKED BY CURRENT USER-->
                                    @if(count($p->likes) > 0)
                                        @php
                                            $liked = false;
                                        @endphp

                                        @foreach($p->likes as $l)
                                             @if($l->user_id == auth()->user()->id)
                                            <div class="postL m-auto" style="display: none">
                                                <div class="userData">
                                                    <img src="https://pngimage.net/wp-content/uploads/2018/06/no-photo-avatar-png-6.png" alt="">
                                                    <div class="postText">
                                                        @foreach($users as $us)
                                                            @foreach($us->posts as $po)
                                                                @if($po->id == $l->post_id)
                                                        <p class="postUsername">{{$us->username}} <span class="text-muted" style="font-size: 15px">{{'@'.$us->username}} . {{$po->created_at->diffForHumans()}}</span></p>
                                                        <p class="postContent">{{$p->content}}</p>
                                                                @endif
                                                            @endforeach
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="hideIconsL col-md-12" style="display: none">
                                                    <ul class="social-network social-circle">
                                                        <li><a class="comment"><i class="far fa-comment"></i></a></li>
                                                @if(count($p->retweets) > 0)
                                                    @php
                                                        $retweeted = false;
                                                    @endphp
                                                    @foreach($p->retweets as $r)
                                                        @if(auth()->user()->id == $r->user_id)
                                                            <li><a class="is-retweeted" id="r{{$p->id}}" onclick="retweet({{$p->id}})"><i class="fas fa-retweet"></i></a></li>
                                                            @php
                                                                $retweeted = true;
                                                            @endphp
                                                            @break
                                                        @endif
                                                    @endforeach
                                                    @if(!$retweeted)
                                                        <li><a class="retweet" id="r{{$p->id}}" onclick="retweet({{$p->id}})"><i class="fas fa-retweet"></i></a></li>
                                                    @endif
                                                @else
                                                    <li><a class="retweet" id="r{{$p->id}}" onclick="retweet({{$p->id}})"><span class="text-muted">{{count($p->retweets)}}</span><i class="fas fa-retweet"></i></a></li>
                                                @endif
                                                        <li><a class="is-liked" id="l{{$p->id}}" onclick="like({{$p->id}})"></a></li>
                                                        @endif
                                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    <script>
        function showPosts()
        {
            $("#myPostsP").show();
            $(".postP").show();
            $(".hideIconsP").show();
            $("#myPostsL").hide();
            $(".postL").hide();
            $(".hideIconsL").hide();

        }

        function showLikes()
        {

            $("#myPostsL").show();
            $(".postL").show();
            $(".hideIconsL").show();
            $("#myPostsP").hide();
            $(".postP").hide();
            $(".hideIconsP").hide();

        }


        function follow(id)
        {
            if($("#f"+id).hasClass('is-followed'))
            {
                $.ajax({
                    url: 'http://localhost:3300/user/unfollow/'+id,
                    success: function (data){
                        $("#f"+id).removeClass('is-followed');
                        $("#f"+id).addClass('followButton');
                        $("#f"+id).text('Follow');
                        $("#followerCounter").text( parseInt($("#followerCounter").text())-1)
                    },
                    error: function(){
                        console.log('here ERR');
                    }
                });
            }
            else{
                $.ajax({
                    url: 'http://localhost:3300/user/follow/'+id,
                    success: function (data){
                        $("#f"+id).text('Following');
                        $("#f"+id).toggleClass('is-followed');
                        $("#followerCounter").text( parseInt($("#followerCounter").text())+1)
                    },
                    error: function(){
                        console.log('here ERR');
                    }
                });
            }
        }

        function retweet(id)
        {
            if($("#r"+id).hasClass('is-retweeted'))
            {
                $.ajax({
                    url: 'http://localhost:3300/post/unretweet/'+id,
                    success: function (data){
                        $("#r"+id).removeClass('is-retweeted');
                        $("#r"+id).addClass('retweet');
                    },
                    error: function(){
                        console.log('here ERR');
                    }
                });
            }
            else{
                $.ajax({
                    url: 'http://localhost:3300/post/retweet/'+id,
                    success: function (data){
                        $("#r"+id).toggleClass('is-retweeted');
                    },
                    error: function(){
                        console.log('here ERR');
                    }
                });
            }
        }



        function like(id){
            if($("#l"+id).hasClass('is-liked'))
            {
                $.ajax({
                    url: 'http://localhost:3300/post/dislike/'+id,
                    success: function (data){
                        $("#l"+id).removeClass('is-liked');
                        $("#l"+id).addClass('like');
                    },
                    error: function(){
                        console.log('here ERR');
                    }
                });
            }
            else{
                $.ajax({
                    url: 'http://localhost:3300/post/like/'+id,
                    success: function (data){
                        $("#l"+id).toggleClass('is-liked');
                    },
                    error: function(){
                        console.log('here ERR');
                    }
                });
            }
        }
    </script>
@endsection
