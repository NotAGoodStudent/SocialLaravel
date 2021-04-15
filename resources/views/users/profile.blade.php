@extends('layouts.app')

@section('content')
    <link href="{{ asset('css/profileStyle.css') }}" rel="stylesheet">
    <div class="m-auto" style="width: 100%; " >
        <div class="profile_container m-auto">
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
                <div style=" margin-top: 50px;border-bottom: 1px solid rgb(47, 51, 54); text-align: center; background-color: rebeccapurple"></div>
                <div class="options">
                    <div class="posts">
                        <a class="linkSelected m-auto" id="postsLink" style="cursor: pointer" onclick="showPosts()">Posts</a>
                    </div>
                    <div class="likes">
                        <a class="links m-auto" id="likesLink" style="cursor: pointer" onclick="showLikes()">Likes</a>
                    </div>
                    <div class="retweets">
                        <a class="links m-auto" id="retweetsLink" style="cursor: pointer" onclick="showRetweets()">Retweets</a>
                    </div>
                </div>
                <div style=" margin-top: 50px;border-bottom: 1px solid rgb(47, 51, 54); text-align: center; background-color: rebeccapurple"></div>
                <div class="data m-auto d-flex" id="myPostsP">
                    @foreach($user->posts as $p)
                        <div class="postP col-md-4 m-auto">
                            <div class="userData">
                                <a><img src="{{Storage::url('public/img/pfp/'.$us->pfp)}}"></a>
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
                                            @if($user->id == $r->user_id)
                                                <li><a class="is-retweeted" id="rp{{$p->id}}" onclick="retweetP({{$p->id}})"><i class="fas fa-retweet"></i></a></li>
                                                @php
                                                    $retweeted = true;
                                                @endphp
                                                @break
                                            @endif
                                        @endforeach
                                        @if(!$retweeted)
                                            <li><a class="retweet" id="rp{{$p->id}}" onclick="retweetP({{$p->id}})"><i class="fas fa-retweet"></i></a></li>
                                        @endif
                                    @else
                                        <li><a class="retweet" id="rp{{$p->id}}" onclick="retweetP({{$p->id}})"><span class="text-muted">{{count($p->retweets)}}</span><i class="fas fa-retweet"></i></a></li>
                                    @endif

                                    <!--CHECK IF POST IS LIKED BY CURRENT USER-->
                                    @if(count($p->likes) > 0)
                                        @php
                                            $liked = false;
                                            @endphp
                                    @foreach($p->likes as $l)
                                        @if($user->id == $l->user_id)
                                        <li><a class="is-liked" id="lp{{$p->id}}" onclick="likeP({{$p->id}})"></a></li>
                                                @php
                                                    $liked = true;
                                                @endphp
                                            @break
                                        @endif
                                    @endforeach
                                        @if(!$liked)
                                            <li><a class="like" id="lp{{$p->id}}" onclick="likeP({{$p->id}})"></a></li>
                                        @endif
                                    @else
                                        <li><a class="like" id="lp{{$p->id}}" onclick="likeP({{$p->id}})"></a></li>
                                    @endif

                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
<!--                HIDDEN UNTIL LIKE IS CLICKED-->

                <div class="data m-auto d-flex" id="myPostsL" style="display: none">
                    @foreach($posts as $p)

                                <!--CHECK IF POST IS LIKED BY CURRENT USER-->
                                    @if(count($p->likes) > 0)
                                        @php
                                            $liked = false;
                                        @endphp

                                        @foreach($p->likes as $l)
                                             @if($l->user_id == $user->id)
                                            <div class="postL m-auto col-md-4" style="display: none">
                                                <div class="userData">
                                                        @foreach($users as $us)
                                                            @foreach($us->posts as $po)
                                                                @if($po->id == $l->post_id)
                                                                <a href="{{ route('profile', $us->username) }}"><img src="https://pngimage.net/wp-content/uploads/2018/06/no-photo-avatar-png-6.png" alt=""></a>
                                                                <div class="postText">
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
                                                        @if($user->id == $r->user_id)
                                                            <li><a class="is-retweeted" id="rl{{$p->id}}" onclick="retweetL({{$p->id}})"><i class="fas fa-retweet"></i></a></li>
                                                            @php
                                                                $retweeted = true;
                                                            @endphp
                                                            @break
                                                        @endif
                                                    @endforeach
                                                    @if(!$retweeted)
                                                        <li><a class="retweet" id="rl{{$p->id}}" onclick="retweetL({{$p->id}})"><i class="fas fa-retweet"></i></a></li>
                                                    @endif
                                                @else
                                                    <li><a class="retweet" id="rl{{$p->id}}" onclick="retweetL({{$p->id}})"><span class="text-muted">{{count($p->retweets)}}</span><i class="fas fa-retweet"></i></a></li>
                                                @endif
                                                        <li><a class="is-liked" id="ll{{$p->id}}" onclick="likeL({{$p->id}})"></a></li>
                                                        @endif
                                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
<!--            ONLY VISIBLE WHEN RETWEET MENU IS SELECTED-->

            <div class="data m-auto d-flex" id="myPostsR" style="display: none">
            @foreach($posts as $p)

                <!--CHECK IF POST IS LIKED BY CURRENT USER-->
                    @if(count($p->retweets) > 0)
                        @php
                            $retweeted = false;
                        @endphp

                        @foreach($p->retweets as $r)
                            @if($r->user_id == $user->id)
                                <div class="postR m-auto col-md-4" style="display: none">
                                    <div class="userData">
                                        @foreach($users as $us)
                                                @foreach($us->posts as $po)
                                                    @if($po->id == $r->post_id)
                                                <a href="{{ route('profile', $us->username) }}"><img src="https://pngimage.net/wp-content/uploads/2018/06/no-photo-avatar-png-6.png" alt=""></a>
                                                <div class="postText">
                                                        <p class="postUsername">{{$us->username}} <span class="text-muted" style="font-size: 15px">{{'@'.$us->username}} . {{$po->created_at->diffForHumans()}}</span></p>
                                                        <p class="postContent">{{$p->content}}</p>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="hideIconsR col-md-12" style="display: none">
                                        <ul class="social-network social-circle">
                                            <li><a class="comment"><i class="far fa-comment"></i></a></li>
                                            <li><a class="is-retweeted" id="rr{{$p->id}}" onclick="retweetR({{$p->id}})"><span class="text-muted">{{count($p->retweets)}}</span><i class="fas fa-retweet"></i></a></li>
                                            @if(count($p->likes) > 0)
                                                @php
                                                    $liked = false;
                                                @endphp
                                                @foreach($p->likes as $l)
                                                    @if($user->id== $l->user_id)
                                                        <li><a class="is-liked" id="lr{{$p->id}}" onclick="likeR({{$p->id}})"></a></li>
                                                        @php
                                                            $liked = true;
                                                        @endphp
                                                        @break
                                                    @endif
                                                @endforeach
                                                @if(!$liked)
                                                    <li><a class="like" id="lt{{$p->id}}" onclick="likeR({{$p->id}})"></a></li>
                                                @endif
                                            @else
                                                <li><a class="like" id="lr{{$p->id}}" onclick="likeR({{$p->id}})"></a></li>
                                            @endif
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
    </div>
    <script>
        function showPosts()
        {
            $("#retweetsLink").removeClass('linkSelected');
            $("#retweetsLink").addClass('links');
            $("#likesLink").removeClass('linkSelected');
            $("#likesLink").addClass('links');
            $("#postsLink").removeClass('links');
            $("#postsLink").addClass('linkSelected');
            $("#myPostsP").show();
            $(".postP").show();
            $(".hideIconsP").show();
            $("#myPostsL").hide();
            $(".postL").hide();
            $(".hideIconsL").hide();
            $("#myPostsR").hide();
            $(".postR").hide();
            $(".hideIconsR").hide();


        }

        function showLikes()
        {

            $("#retweetsLink").removeClass('linkSelected');
            $("#retweetsLink").addClass('links');
            $("#postsLink").removeClass('linkSelected');
            $("#postsLink").addClass('links');
            $("#likesLink").removeClass('links');
            $("#likesLink").addClass('linkSelected');
            $("#myPostsL").show();
            $(".postL").show();
            $(".hideIconsL").show();
            $("#myPostsP").hide();
            $(".postP").hide();
            $(".hideIconsP").hide();
            $("#myPostsR").hide();
            $(".postR").hide();
            $(".hideIconsR").hide();

        }

        function showRetweets()
        {
            $("#likesLink").removeClass('linkSelected');
            $("#likesLink").addClass('links');
            $("#postsLink").removeClass('linkSelected');
            $("#postsLink").addClass('links');
            $("#retweetsLink").removeClass('links');
            $("#retweetsLink").addClass('linkSelected');
            $("#myPostsR").show();
            $(".postR").show();
            $(".hideIconsR").show();
            $("#myPostsL").hide();
            $(".postL").hide();
            $(".hideIconsL").hide();
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

        function retweetP(id)
        {
            if($("#rp"+id).hasClass('is-retweeted'))
            {
                $.ajax({
                    url: 'http://localhost:3300/post/unretweet/'+id,
                    success: function (data){
                        $("#rp"+id).removeClass('is-retweeted');
                        $("#rp"+id).addClass('retweet');
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
                        $("#rp"+id).toggleClass('is-retweeted');
                    },
                    error: function(){
                        console.log('here ERR');
                    }
                });
            }
        }

        function retweetL(id)
        {
            if($("#rl"+id).hasClass('is-retweeted'))
            {
                $.ajax({
                    url: 'http://localhost:3300/post/unretweet/'+id,
                    success: function (data){
                        $("#rl"+id).removeClass('is-retweeted');
                        $("#rl"+id).addClass('retweet');
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
                        $("#rl"+id).toggleClass('is-retweeted');
                    },
                    error: function(){
                        console.log('here ERR');
                    }
                });
            }
        }

        function retweetR(id)
        {
            if($("#rr"+id).hasClass('is-retweeted'))
            {
                $.ajax({
                    url: 'http://localhost:3300/post/unretweet/'+id,
                    success: function (data){
                        $("#rr"+id).removeClass('is-retweeted');
                        $("#rr"+id).addClass('retweet');
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
                        $("#rr"+id).toggleClass('is-retweeted');
                    },
                    error: function(){
                        console.log('here ERR');
                    }
                });
            }
        }





        function likeP(id){
            if($("#lp"+id).hasClass('is-liked'))
            {
                $.ajax({
                    url: 'http://localhost:3300/post/dislike/'+id,
                    success: function (data){
                        $("#lp"+id).removeClass('is-liked');
                        $("#lp"+id).addClass('like');
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
                        $("#lp"+id).toggleClass('is-liked');
                    },
                    error: function(){
                        console.log('here ERR');
                    }
                });
            }
        }

        function likeL(id){
            if($("#ll"+id).hasClass('is-liked'))
            {
                $.ajax({
                    url: 'http://localhost:3300/post/dislike/'+id,
                    success: function (data){
                        $("#ll"+id).removeClass('is-liked');
                        $("#ll"+id).addClass('like');
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
                        $("#ll"+id).toggleClass('is-liked');
                    },
                    error: function(){
                        console.log('here ERR');
                    }
                });
            }
        }

        function likeR(id){
            if($("#lr"+id).hasClass('is-liked'))
            {
                $.ajax({
                    url: 'http://localhost:3300/post/dislike/'+id,
                    success: function (data){
                        $("#lr"+id).removeClass('is-liked');
                        $("#lr"+id).addClass('like');
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
                        $("#lr"+id).toggleClass('is-liked');
                    },
                    error: function(){
                        console.log('here ERR');
                    }
                });
            }
        }
    </script>
@endsection
