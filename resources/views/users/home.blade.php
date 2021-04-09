@extends('layouts.app')

@section('content')
{{--    bug when looping, doesn't mark liked and retweeted posts--}}
        <link href="{{ asset('css/homeStyle.css') }}" rel="stylesheet">
        <div class="m-auto">
            <div class="content" style="background-color: #060606">
                <div class="homeTitle m-auto">
                        <h5>Home</h5>
                </div>
                <div class="thoughts">
                    <div class="search_bar float-right">
                        <input type="text" class="search" placeholder="Search">
                    </div>
                    <div class="results" hidden>

                    </div>
                    <div class="recommended">
                        <div class="cart">
                            <h4>Who to follow</h4>
                            @php
                            $counter = 0;
                            @endphp
                            @foreach($users as $us)
                                @foreach(auth()->user()->following as $f)
                                    @if($us->id != auth()->user()->id && $us->id != $f->id)
                                        <div class="suggested">
                                            <div class="text_suggested">
                                                <a class="link" href="{{ route('profile', $us->username) }}"><img style="width: 50px; height: 50px; border-radius: 50%" src="https://pngimage.net/wp-content/uploads/2018/06/no-photo-avatar-png-6.png"></a>
                                                <p class="postUsername">{{$us->username}} <span class="text-muted" style="font-size: 15px">{{'@'.$us->username}}</span></p>
                                            </div>
                                            <div class="button_suggested">
                                                <a class="followButton m-auto" id="f{{$us->id}}" onclick="follow({{$us->id}})">Follow</a>
                                            </div>

                                        </div>
                                        {{$counter++}}
                                        @if($counter == 3)
                                            @break
                                            @endif
                                        @endif
                                @endforeach
                            @endforeach

                            <a href="" style="margin-top: 5px">Show more</a>
                        </div>
                    </div>
                    <div class="textfield_container m-auto">
                        <a class="link" href="{{ route('profile', auth()->user()->username) }}"><img class="mx-auto d-block" style="width: 50px; height: 50px; border-radius: 50%" src="https://pngimage.net/wp-content/uploads/2018/06/no-photo-avatar-png-6.png"></a>
                        <form action="{{ route('makePost', auth()->user()->id)}}" method="post">
                            @csrf
                        <textarea onclick="activateArea()" id="txtarea" name="post" class="txt-area mx-auto d-block" placeholder="What are your thoughts {{auth()->user()->username}}?"></textarea>
                            <div class="m-auto ">
                            <input class='buttonPost float-right' id="buttonAct" type="submit" name="" value="Post" disabled>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="feed">
                    <div class="data m-auto col-md-12">
                        @foreach(auth()->user()->following as $f)
                        @foreach($posts as $p)
                                @if($p->owner == $f->id)
                                    <div class="postL m-auto">
                                        <div class="userData">
                                    @foreach($users as $us)
                                        @if($us->id == $f->id)
                                        <a href="{{ route('profile', $us->username) }}"><img src="https://pngimage.net/wp-content/uploads/2018/06/no-photo-avatar-png-6.png" alt=""></a>
                                        <div class="postText">
                                            <p class="postUsername">{{$us->username}} <span class="text-muted" style="font-size: 15px">{{'@'.$us->username}} . {{$p->created_at->diffForHumans()}}</span></p>
                                            <p class="postContent">{{$p->content}}</p>
                                        </div>
                                        @endif
                                    @endforeach
                                        </div>
                        <div class="hideIconsL col-md-12">
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
                                    <li><a class="retweet" id="r{{$p->id}}" onclick="retweet({{$p->id}})"><i class="fas fa-retweet"></i></a></li>

                                @endif

                                @if(count($p->likes) > 0)
                                    @php
                                        $liked = false;
                                    @endphp
                                    @foreach($p->likes as $l)
                                        @if($l->user_id == auth()->user()->id)
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
                    @endif
                                    </div>
                    </div>
                    @endforeach
                @endforeach
                </div>
            </div>
        </div>
        <script>
           $(document).ready(function (){
                $('#txtarea').keydown(function (){
                    if ($.trim($("#txtarea").val()))
                    {
                       /* var newHTML = "";
                        if($("#txtarea:contains('#')" ))
                        {
                            newHTML = "<span class='statement'>" + val + "&nbsp;</span>"
                            $("#txtarea").css('color', 'rgba(46, 204, 113, 1)');
                            $.found = true;
                        }
                        if($('.found') && $("#txtarea:contains(' ')" ))
                        {

                        }*/
                        $('#buttonAct').removeClass('buttonPost');
                        $('#buttonAct').addClass('buttonPostAct');
                        $('#buttonAct').removeAttr('disabled');

                    }
                    else
                        {
                            console.log('here' + " "+$(this).val().length);
                            $('#buttonAct').removeClass('buttonPostAct');
                            $('#buttonAct').addClass('buttonPost');
                            $('#buttonAct').attr('disabled');
                        }
                });
           });


           function activateArea()
           {
               $('#txtarea').removeClass('txt-area');
               $('#txtarea').addClass('txt-area-active');
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
