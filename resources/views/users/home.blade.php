@extends('layouts.app')

@section('content')
{{--    bug when looping, doesn't mark liked and retweeted posts--}}
        <link href="{{ asset('css/homeStyle.css') }}" rel="stylesheet">
        <div class="m-auto">
            <div class="content" style="background-color: #060606">
                <div class="thoughts">
                    <div class="textfield_container m-auto">
                        <a class="link" href="{{ route('profile', auth()->user()->username) }}"><img class="mx-auto d-block" style="width: 50px; height: 50px; border-radius: 50%" src="https://pngimage.net/wp-content/uploads/2018/06/no-photo-avatar-png-6.png"></a>
                        <form action="{{ route('makePost', auth()->user()->id)}}" method="post">
                            @csrf
                        <textarea id="txtarea" name="post" class="txt-area mx-auto d-block" placeholder="What are your thoughts {{auth()->user()->username}}?"></textarea>
                            <input class='buttonPost float-right' id="buttonAct" type="submit" name="" value="Post" disabled>
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
                                        @else
                                            @if(!$retweeted)
                                                <li><a class="retweet" id="r{{$p->id}}" onclick="retweet({{$p->id}})"><i class="fas fa-retweet"></i></a></li>
                                            @endif
                                        @endif
                                    @endforeach
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
                                @else
                                    @if(!$liked)
                                        <li><a class="like" id="l{{$p->id}}" onclick="like({{$p->id}})"></a></li>
                                    @endif
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

           function likeP(id){
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
