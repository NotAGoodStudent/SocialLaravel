@extends('layouts.app')

@section('content')
    <link href="{{ asset('css/replyStyle.css') }}" rel="stylesheet">
    <div class="home m-auto d-flex justify-content-around">
        <div class="box1 col-md-3">
            <div class="cardSug">
                <div class="whotofollow_title m-auto">
                    <h4 class="m-auto">Who to follow</h4>
                </div>
                <?php
                use Illuminate\Support\Facades\Storage;
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
                            <a class="link" href="{{ route('profile', $us->username) }}"><img style="width: 50px; height: 50px; border-radius: 50%" src="{{Storage::url($us->pfp)}}"></a>
                            <p class="postUsername">{{$us->username}} <span class="text-muted" style="font-size: 15px">{{'@'.$us->username}}</span></p>
                            <a class="followButton float-right" id="f{{$us->id}}" onclick="follow({{$us->id}})">Follow</a   >
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
                            <a class="link" href="{{ route('profile', $us->username) }}"><img style="width: 100%; height: 50px; border-radius: 50%" src="{{Storage::url($us->pfp)}}"></a>
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
        <div class="box2 col-md-4 m-auto">
            <div class="content">
                <div class="homeTitle">
                    <h5>Replies</h5>
                </div>
                    @foreach($users as $us)

                        @if($us->id == $post->owner)
                        <div class="post_to_be_replied" id="postL">
                            <div class="userData">
                                <a href="{{ route('profile', $us->username) }}"><img src="{{Storage::url($us->pfp)}}" alt=""></a>
                                <div class="postText">
                                    <p class="postUsername">{{$us->username}} <span class="text-muted" style="font-size: 15px">{{'@'.$us->username}} . {{$post->created_at->diffForHumans()}}</span></p>
                                    <p class="postContent">{{$post->content}}</p>
                                </div>
                                @if($post->image != null)
                                    <div class="divImg m-auto">
                                        <img src="{{Storage::url($post->image)}}" class="imgPost">
                                    </div>
                                @endif
                            </div>
                            <div class="hideIconsL col-md-12">
                                <ul class="social-network social-circle">
                                    <li><a class="comment" onclick="reply({{$post->id}})"><i class="far fa-comment"></i></a></li>
                                    @if(count($post->retweets) > 0)
                                        @php
                                            $retweeted = false;
                                        @endphp
                                        @foreach($post->retweets as $r)
                                            @if(auth()->user()->id == $r->user_id)
                                                <li><a class="is-retweeted" id="r{{$post->id}}" onclick="retweet({{$post->id}})"><i class="fas fa-retweet"></i></a></li>
                                                @php
                                                    $retweeted = true;
                                                @endphp
                                                @break
                                            @endif
                                        @endforeach
                                        @if(!$retweeted)
                                            <li><a class="retweet" id="r{{$post->id}}" onclick="retweet({{$post->id}})"><i class="fas fa-retweet"></i></a></li>
                                        @endif
                                    @else
                                        <li><a class="retweet" id="r{{$post->id}}" onclick="retweet({{$post->id}})"><i class="fas fa-retweet"></i></a></li>
                                    @endif

                                    @if(count($post->likes) > 0)
                                        @php
                                            $liked = false;
                                        @endphp
                                        @foreach($post->likes as $l)
                                            @if($l->user_id == auth()->user()->id)
                                                <li><a class="is-liked" id="l{{$post->id}}" onclick="like({{$post->id}})"></a></li>
                                                @php
                                                    $liked = true;
                                                @endphp
                                                @break
                                            @endif
                                        @endforeach
                                        @if(!$liked)
                                            <li><a class="like" id="l{{$post->id}}" onclick="like({{$post->id}})"></a></li>
                                        @endif
                                    @else
                                        <li><a class="like" id="l{{$post->id}}" onclick="like({{$post->id}})"></a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        @endif
                    @endforeach
            </div>
        </div>
                <div class="box3 col-md-3">
                    <div class="search_bar">
                        <input type="text" class="search" id="search" placeholder="Search">
                        <div class="results" id="results" hidden>
                        </div>
                    </div>
                </div>
    </div>
                <script>
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
