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
                    <img class="profile_img m-auto" src="{{Storage::url($user->pfp)}}" alt="">
                </div>
                <img class="background_img" src="{{Storage::url($user->background)}}" alt="">
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
                <div style=" margin-top: 50px;border-bottom: 1px solid rgb(47, 51, 54); text-align: center"></div>
                <div class="data m-auto d-flex flex-column" id="myPostsP">
                    @foreach($user->posts as $p)
                        <div class="postP col-md-4 m-auto" data-id="{{$p->id}}">
                            <div class="userData">
                                <a><img src="{{Storage::url($user->pfp)}}"></a>
                                <div class="postText">
                                    <p class="postUsername">{{$user->username}} <span class="text-muted" style="font-size: 15px">{{'@'.$user->username}} . {{$p->created_at->diffForHumans()}}</span></p>
                                    <p class="postContent">{{$p->content}}</p>
                                </div>
                                @if($p->image != null)
                                    <div class="divImg">
                                        <img src="{{Storage::url($p->image)}}" class="imgPost">
                                    </div>
                                @endif
                            </div>
                            <div class="hideIconsP col-md-12">
                                <ul class="social-network social-circle">
                                    <li><a class="comment"  onclick="replyP({{$p->id}})"><i class="far fa-comment"></i></a></li>
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
                        <div class="reply_to_post col-md-4 m-auto" id="reply_to_postP{{$p->id}}" style="display: none">
                            <div class="reply_data d-inline-block justify-content-around" style="width: 100%; margin: 10px 20px">
                                <div style="width: 10%" class="float-right">
                                    <a onclick="closeReplyP({{$p->id}})" class="close_reply_div fas fa-times"></a>
                                </div>
                                <a class="link" href="{{ route('profile', auth()->user()->username)}}"><img style="width: 50px; height: 50px; border-radius: 50%" src="{{Storage::url(auth()->user()->pfp)}}"></a>
                                <p class="postUsername" style="margin-left: 70px">{{auth()->user()->username}} <span class="text-muted" style="font-size: 15px">{{'@'.auth()->user()->username}}</span></p>
                            </div>
                            <form action="{{ route('makePost',['answer_id'=> $p->id, 'comesFromReplyTab'=>0])}}" enctype="multipart/form-data" method="post">
                                @csrf
                                <textarea onclick="activateArea()" id="txtarea_reply" name="post" class="txt-area mx-auto d-block" placeholder="Tweet your reply"></textarea>
                                <div class="replyButton float-right" style="width: 20%">
                                    <input class='buttonPost' id="buttonPostReply" onclick="" type="submit" name="" value="Post">
                                </div>
                                <div class="ic_reply m-auto d-flex justify-content-around">
                                    <span onclick="replyUploadImgP({{$p->id}})" id="iconClicked}" class="imgIcon_reply fas fa-image"></span>
                                    <input type="file" class="uploadImg" name="uploadImageP{{$p->id}}" id="uploadImage{{$p->id}}" hidden>
                                    <span onclick="" id="iconClicked2" class="imgIcon_reply fa fa-file-video"></span>
                                    <input type="file" class="uploadGIF" id="uploadGIF" hidden>
                                    <span onclick="" id="iconClicked3" class="imgIcon_reply fas fa-video"></span>
                                    <input type="file" class="uploadVideo" id="uploadVideo" hidden>
                                </div>
                            </form>
                        </div>
                    @endforeach
                </div>
<!--                HIDDEN UNTIL LIKE IS CLICKED-->

                <div class="data d-flex flex-column" id="myPostsL" style="display: none">
                    @foreach($posts as $p)
                                <!--CHECK IF POST IS LIKED BY CURRENT USER-->
                                    @if(count($p->likes) > 0)
                                        @php
                                            $liked = false;
                                        @endphp

                                        @foreach($p->likes as $l)
                                             @if($l->user_id == $user->id)
                                            <div class="postL m-auto col-md-4" data-id="{{$p->id}}" style="display: none">
                                                <div class="userData">
                                                        @foreach($users as $us)
                                                            @foreach($us->posts as $po)
                                                                @if($po->id == $l->post_id)
                                                                <a href="{{ route('profile', $us->username) }}"><img src="{{Storage::url($us->pfp)}}" alt=""></a>
                                                                <div class="postText">
                                                                    <p class="postUsername">{{$us->username}} <span class="text-muted" style="font-size: 15px">{{'@'.$us->username}} . {{$po->created_at->diffForHumans()}}</span></p>
                                                                    <p class="postContent">{{$p->content}}</p>
                                                                </div>
                                                                @if($p->image != null)
                                                                    <div class="divImg">
                                                                        <img src="{{Storage::url($p->image)}}" class="imgPost">
                                                                    </div>
                                                                @endif
                                                                @endif
                                                            @endforeach
                                                        @endforeach
                                                </div>
                                                <div class="hideIconsL col-md-12" style="display: none">
                                                    <ul class="social-network social-circle">
                                                        <li><a class="comment" onclick="replyL({{$p->id}})"><i class="far fa-comment"></i></a></li>
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
                                                    </ul>
                                                </div>
                                            </div>
                                                <div class="reply_to_post" id="reply_to_postL{{$p->id}}" style="display: none">
                                                    <div class="reply_data d-inline-block justify-content-around" style="width: 100%; margin: 10px 20px">
                                                        <div style="width: 10%" class="float-right">
                                                            <a onclick="closeReplyL({{$p->id}})" class="close_reply_div fas fa-times"></a>
                                                        </div>
                                                        <a class="link" href="{{ route('profile', auth()->user()->username)}}"><img style="width: 50px; height: 50px; border-radius: 50%" src="{{Storage::url(auth()->user()->pfp)}}"></a>
                                                        <p class="postUsername" style="margin-left: 70px">{{auth()->user()->username}} <span class="text-muted" style="font-size: 15px">{{'@'.auth()->user()->username}}</span></p>
                                                    </div>
                                                    <form action="{{ route('makePost',['answer_id'=> $p->id, 'comesFromReplyTab'=>0])}}" enctype="multipart/form-data" method="post">
                                                        @csrf
                                                        <textarea onclick="activateArea()" id="txtarea_reply" name="post" class="txt-area mx-auto d-block" placeholder="Tweet your reply"></textarea>
                                                        <div class="replyButton float-right" style="width: 20%">
                                                            <input class='buttonPost' id="buttonPostReply" onclick="" type="submit" name="" value="Post">
                                                        </div>
                                                        <div class="ic_reply m-auto d-flex justify-content-around">
                                                            <span onclick="replyUploadImgL({{$p->id}})" id="iconClicked}" class="imgIcon_reply fas fa-image"></span>
                                                            <input type="file" class="uploadImg" name="uploadImageL{{$p->id}}" id="uploadImage{{$p->id}}" hidden>
                                                            <span onclick="" id="iconClicked2" class="imgIcon_reply fa fa-file-video"></span>
                                                            <input type="file" class="uploadGIF" id="uploadGIF" hidden>
                                                            <span onclick="" id="iconClicked3" class="imgIcon_reply fas fa-video"></span>
                                                            <input type="file" class="uploadVideo" id="uploadVideo" hidden>
                                                        </div>
                                                    </form>
                                                </div>
                                    @endif
                    @endforeach
                </div>
<!--            ONLY VISIBLE WHEN RETWEET MENU IS SELECTED-->

            <div class="data m-auto d-flex flex-column" id="myPostsR" style="display: none">
            @foreach($posts as $p)

                <!--CHECK IF POST IS LIKED BY CURRENT USER-->
                    @if(count($p->retweets) > 0)
                        @php
                            $retweeted = false;
                        @endphp

                        @foreach($p->retweets as $r)
                            @if($r->user_id == $user->id)
                                <div class="postR m-auto col-md-4" data-id="{{$p->id}}" style="display: none">
                                    <div class="userData">
                                        @foreach($users as $us)
                                                @foreach($us->posts as $po)
                                                    @if($po->id == $r->post_id)
                                                <a href="{{ route('profile', $us->username) }}"><img src="{{Storage::url($us->pfp)}}" alt=""></a>
                                                <div class="postText">
                                                        <p class="postUsername">{{$us->username}} <span class="text-muted" style="font-size: 15px">{{'@'.$us->username}} . {{$po->created_at->diffForHumans()}}</span></p>
                                                        <p class="postContent">{{$p->content}}</p>
                                                </div>
                                                    @if($p->image != null)
                                                        <div class="divImg">
                                                            <img src="{{Storage::url($p->image)}}" class="imgPost">
                                                        </div>
                                                    @endif
                                                    @endif
                                                @endforeach
                                            @endforeach
                                    </div>
                                    <div class="hideIconsR col-md-12" style="display: none">
                                        <ul class="social-network social-circle">
                                            <li><a class="comment"  onclick="replyR({{$p->id}})"><i class="far fa-comment"></i></a></li>
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
                                        </ul>
                                    </div>
                                </div>
                                <div class="reply_to_post" id="reply_to_postR{{$p->id}}" style="display: none">
                                    <div class="reply_data d-inline-block justify-content-around" style="width: 100%; margin: 10px 20px">
                                        <div style="width: 10%" class="float-right">
                                            <a onclick="closeReplyR({{$p->id}})" class="close_reply_div fas fa-times"></a>
                                        </div>
                                        <a class="link" href="{{ route('profile', auth()->user()->username)}}"><img style="width: 50px; height: 50px; border-radius: 50%" src="{{Storage::url(auth()->user()->pfp)}}"></a>
                                        <p class="postUsername" style="margin-left: 70px">{{auth()->user()->username}} <span class="text-muted" style="font-size: 15px">{{'@'.auth()->user()->username}}</span></p>
                                    </div>
                                    <form action="{{ route('makePost',['answer_id'=> $p->id, 'comesFromReplyTab'=>0])}}" enctype="multipart/form-data" method="post">
                                        @csrf
                                        <textarea onclick="activateArea()" id="txtarea_reply" name="post" class="txt-area mx-auto d-block" placeholder="Tweet your reply"></textarea>
                                        <div class="replyButton float-right" style="width: 20%">
                                            <input class='buttonPost' id="buttonPostReply" onclick="" type="submit" name="" value="Post">
                                        </div>
                                        <div class="ic_reply m-auto d-flex justify-content-around">
                                            <span onclick="replyUploadImgR({{$p->id}})" id="iconClicked}" class="imgIcon_reply fas fa-image"></span>
                                            <input type="file" class="uploadImg" name="uploadImageR{{$p->id}}" id="uploadImage{{$p->id}}" hidden>
                                            <span onclick="" id="iconClicked2" class="imgIcon_reply fa fa-file-video"></span>
                                            <input type="file" class="uploadGIF" id="uploadGIF" hidden>
                                            <span onclick="" id="iconClicked3" class="imgIcon_reply fas fa-video"></span>
                                            <input type="file" class="uploadVideo" id="uploadVideo" hidden>
                                        </div>
                                    </form>
                                </div>
                                            @endif
                                            @endforeach
                                            @endif
                                @endforeach
            </div>


        </div>
        </div>
    </div>
    <script>

        function closeReplyP(id)
        {
            $('#reply_to_postP'+id).slideUp("slow", "linear");
        }

        function closeReplyL(id)
        {
            $('#reply_to_postL'+id).slideUp("slow", "linear");
        }

        function closeReplyR(id)
        {
            $('#reply_to_postR'+id).slideUp("slow", "linear");
        }

        function replyP(id)
        {
            $('#reply_to_postP'+id).slideDown("slow", "linear");
        }

        function replyR(id)
        {
            $('#reply_to_postP'+id).slideDown("slow", "linear");
        }

        function replyL(id)
        {
            $('#reply_to_postP'+id).slideDown("slow", "linear");
        }



        $(document).ready(function (){
            $('.postL').click(function (e) {
                let id = $(this).attr('data-id');
                console.log(e.target.className);
                if (e.target == e.currentTarget || e.target.className == 'userData' || e.target.className == 'divImg' || e.target.className == 'postText' || e.target.className == 'postContent' || e.target.className =='postUsername' || e.target.className == 'social-network social-circle') {
                    $(location).attr('href', "/post/showReplies/" + id);
                }
                else return;
            });

            $('.postP').click(function (e) {
                let id = $(this).attr('data-id');
                console.log(e.target.className);
                if (e.target == e.currentTarget || e.target.className == 'userData' || e.target.className == 'divImg' || e.target.className == 'postText' || e.target.className == 'postContent' || e.target.className =='postUsername' || e.target.className == 'social-network social-circle') {
                    $(location).attr('href', "/post/showReplies/" + id);
                }
                else return;
            });

            $('.postR').click(function (e) {
                let id = $(this).attr('data-id');
                console.log(e.target.className);
                if (e.target == e.currentTarget || e.target.className == 'userData' || e.target.className == 'divImg' || e.target.className == 'postText' || e.target.className == 'postContent' || e.target.className =='postUsername' || e.target.className == 'social-network social-circle') {
                    $(location).attr('href', "/post/showReplies/" + id);
                }
                else return;
            });

        });
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
