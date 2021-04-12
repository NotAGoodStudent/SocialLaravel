@extends('layouts.app')

@section('content')
{{--    bug when looping, doesn't mark liked and retweeted posts--}}
        <link href="{{ asset('css/homeStyle.css') }}" rel="stylesheet">
        <div class="home m-auto d-flex justify-content-between">
            <div class="box1">
                <div class="cardSug">
                    <div class="whotofollow_title m-auto">
                        <h4 class="m-auto">Who to follow</h4>
                    </div>
                    <?php
                        $counter = 0;
                        $exists = false;
                    ?>
                    @foreach($users as $us)
                        @foreach(auth()->user()->following as $f)
                            @if($us->id != auth()->user()->id and $us->id == $f->id)
                                <?php
                                $exists = true;
                                ?>
                                @break
                            @endif
                        @endforeach
                    @if(!$exists and $us->id != auth()->user()->id)
                                <div class="suggested">
                                        <a class="link" href="{{ route('profile', $us->username) }}"><img style="width: 50px; height: 50px; border-radius: 50%" src="https://pngimage.net/wp-content/uploads/2018/06/no-photo-avatar-png-6.png"></a>
                                        <p class="postUsername">{{$us->username}} <span class="text-muted" style="font-size: 15px">{{'@'.$us->username}}</span></p>
                                        <a class="followButton float-right" id="f{{$us->id}}" onclick="follow({{$us->id}})">Follow</a>
                                </div>
                                <?php
                                    $counter++;
                                ?>
                        @else
                                <?php
                                    $exists = false;
                                ?>
                        @endif
                            @if($counter > 2)
                                @break
                            @endif
                    @endforeach
                    <div class="showMore">
                        <a onclick="showMore()" class="show_less" id="show">Show more</a>
                    </div>
                </div>
                <div class="suggested_card_full" id="suggested_card_full" style="display: none">
                    <?php
                    $exists = false;
                    ?>
                    @foreach($users as $us)
                        @foreach(auth()->user()->following as $f)
                            @if($us->id != auth()->user()->id and $us->id == $f->id)
                                <?php
                                $exists = true;
                                ?>
                                @break
                            @endif
                        @endforeach
                        @if(!$exists and $us->id != auth()->user()->id)
                            <div class="suggestedFull">
                                <a class="link" href="{{ route('profile', $us->username) }}"><img style="width: 50px; height: 50px; border-radius: 50%" src="https://pngimage.net/wp-content/uploads/2018/06/no-photo-avatar-png-6.png"></a>
                                <p class="postUsername">{{$us->username}} <span class="text-muted" style="font-size: 15px">{{'@'.$us->username}}</span></p>
                                <a class="followButton float-right" id="f{{$us->id}}" onclick="follow({{$us->id}})">Follow</a>
                            </div>
                            <?php
                            ?>
                        @else
                            <?php
                            $exists = false;
                            ?>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="box2">
                <div class="content">
                    <div class="homeTitle">
                        <h5>Home</h5>
                    </div>
                    <div class="thoughts">
                        <div class="textfield_container">
                            <div class="imgDiv" style="width: 100%; margin: 10px 20px;">
                                <a class="link" href="{{ route('profile', auth()->user()->username) }}"><img class="mx-auto d-block" style="width: 50px; height: 50px; border-radius: 50%;" src="https://pngimage.net/wp-content/uploads/2018/06/no-photo-avatar-png-6.png"></a>
                            </div>
                            <form action="{{ route('makePost', auth()->user()->id)}}" method="post">
                                <input class='buttonPost float-right' id="buttonAct" type="submit" name="" value="Post" disabled>
                                @csrf
                                <textarea onclick="activateArea()" id="txtarea" name="post" class="txt-area mx-auto d-block" placeholder="What are your thoughts {{auth()->user()->username}}?"></textarea>
                            </form>
                        </div>
                    </div>
                            @foreach(auth()->user()->following as $f)
                                @foreach($posts as $p)
                                    @if($p->owner == $f->id)
                                                @foreach($users as $us)
                                                    @if($us->id == $f->id)
                                            <div class="postL">
                                                        <div class="userData">
                                                            <a href="{{ route('profile', $us->username) }}"><img src="https://pngimage.net/wp-content/uploads/2018/06/no-photo-avatar-png-6.png" alt=""></a>
                                                            <div class="postText">
                                                                <p class="postUsername">{{$us->username}} <span class="text-muted" style="font-size: 15px">{{'@'.$us->username}} . {{$p->created_at->diffForHumans()}}</span></p>
                                                                <p class="postContent">{{$p->content}}</p>
                                                            </div>
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
                                            </div>
                                                        @endif
                                                @endforeach
                                            @endif
                                            @endforeach
                                        @endforeach
                </div>
            </div>
              <div class="box3">
                <div class="search_bar">
                    <input type="text" class="search" id="search" placeholder="Search">
                    <div class="results" id="results" hidden>
                    </div>
                </div>
            </div>
        </div>
        <script>
           $(document).ready(function (){

               topics = new Array();
               $.ajax({
                   url: 'http://localhost:3300/topics/getTopics',
                   success: function (data){
                       topics = data;
                       console.log(topics[0]);
                   },
                   dataType: "json"
               });

               users = new Array()
               {
                   $.ajax({
                       url: 'http://localhost:3300/users/getUsers',
                       success: function (data){
                           users = data;
                           console.log(users[0]);
                       },
                       dataType: "json"
                   });
               }

               $('#search').keydown(function(){

                   {
                       if($.trim($('#search').val()))
                       {
                            for(var x = 0; x < users.length;x++)
                            {
                                console.log(users[x][1])
                            }
                       }
                   }
               });

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

           function showMore()
           {
               if($('#show').hasClass('show_less'))
               {
                   console.log('here');
                   $('#show').removeClass('show_less');
                   $('#show').addClass('show_more');
                   $('#show').text('Show less');
                   $('#suggested_card_full').show();
               }
               else
               {
                   console.log('here2');
                   $('#show').removeClass('show_more');
                   $('#show').addClass('show_less');
                   $('#show').text('Show more');
                   $('#suggested_card_full').hide();
               }
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
