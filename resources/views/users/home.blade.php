
@extends('layouts.app')

@section('content')
{{--    bug when looping, doesn't mark liked and retweeted posts--}}
        <link href="{{ asset('css/homeStyle.css') }}" rel="stylesheet">
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
                        <h5>Home</h5>
                    </div>
                    <div class="thoughts">
                        <div class="textfield_container">
                            <div class="imgDiv" style="width: 100%; margin: 10px 20px;">
                                <a class="link" href="{{ route('profile', auth()->user()->username)}}"><img class="mx-auto d-block" style="width: 100%; height: 50px; border-radius: 50%;" src="{{Storage::url(auth()->user()->pfp)}}"></a>
                            </div>
                            <form action="{{ route('makePost',['answer_id'=> 0, 'comesFromReplyTab'=>0])}}" enctype="multipart/form-data" method="post">
                                <input class='buttonPost float-right' id="buttonAct" type="submit" name="" value="Post" disabled>
                                @csrf
                                <textarea onclick="activateArea()" id="txtarea" name="post" class="txt-area mx-auto d-block" placeholder="What are your thoughts {{auth()->user()->username}}?"></textarea>
                                <div class="ic m-auto d-flex justify-content-around">
                                    <span onclick="" id="iconClicked" class="imgIcon fas fa-image"></span>
                                    <input type="file" class="uploadImg" name="uploadImage" id="uploadImage" hidden>
                                    <span onclick="" id="iconClicked2" class="imgIcon fa fa-file-video"></span>
                                    <input type="file" class="uploadGIF" id="uploadGIF" hidden>
                                    <span onclick="" id="iconClicked3" class="imgIcon fas fa-video"></span>
                                    <input type="file" class="uploadVideo" id="uploadVideo" hidden>
                                </div>
                            </form>
                        </div>
                    </div>
                            @foreach(auth()->user()->following as $f)
                                @foreach($posts as $p)
                                    @if($p->owner == $f->id)
                                                @foreach($users as $us)
                                                    @if($us->id == $f->id)
                                                        @php
                                                        $counter = 0;
                                                        $counter++;
                                                        @endphp
                                            <div class="postL" id="postL{{$p->id}}" data-id="{{$p->id}}">
                                                        <div class="userData">
                                                            <a href="{{ route('profile', $us->username) }}"><img src="{{Storage::url($us->pfp)}}" alt=""></a>
                                                            <div class="postText">
                                                                <p class="postUsername">{{$us->username}} <span class="text-muted" style="font-size: 15px">{{'@'.$us->username}} . {{$p->created_at->diffForHumans()}}</span></p>
                                                                <p class="postContent">{{$p->content}}</p>
                                                            </div>
                                                            @if($p->image != null)
                                                                <div class="divImg m-auto">
                                                                    <img src="{{Storage::url($p->image)}}" class="imgPost">
                                                                </div>
                                                                @endif
                                                        </div>
                                                        <div class="hideIconsL col-md-12">
                                                            <ul class="social-network social-circle">
                                                                <li><a class="comment" onclick="reply({{$p->id}})"><i class="far fa-comment"></i></a></li>
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
                                        <div class="reply_to_post" id="reply_to_post{{$p->id}}" style="display: none">
                                            <div class="reply_data d-inline-block justify-content-around" style="width: 100%; margin: 10px 20px">
                                                <div style="width: 10%" class="float-right">
                                                    <a onclick="closeReply({{$p->id}})" class="close_reply_div fas fa-times"></a>
                                                </div>
                                                <a class="link" href="{{ route('profile', auth()->user()->username)}}"><img style="width: 100%; height: 50px; border-radius: 50%" src="{{Storage::url(auth()->user()->pfp)}}"></a>
                                                <p class="postUsername" style="margin-left: 70px">{{auth()->user()->username}} <span class="text-muted" style="font-size: 15px">{{'@'.auth()->user()->username}}</span></p>
                                            </div>
                                            <form action="{{ route('makePost',['answer_id'=> $p->id, 'comesFromReplyTab'=>0])}}" enctype="multipart/form-data" method="post">
                                                @csrf
                                                <textarea onclick="activateArea()" id="txtarea_reply" name="post" class="txt-area mx-auto d-block" placeholder="Tweet your reply"></textarea>
                                                <div class="replyButton float-right" style="width: 20%">
                                                    <input class='buttonPost' id="buttonPostReply" onclick="" type="submit" name="" value="Post">
                                                </div>
                                                <div class="ic_reply m-auto d-flex justify-content-around">
                                                    <span onclick="replyUploadImg({{$p->id}})" id="iconClicked}" class="imgIcon_reply fas fa-image"></span>
                                                    <input type="file" class="uploadImg" name="uploadImage{{$p->id}}" id="uploadImage{{$p->id}}" hidden>
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

            function checkReplies(id)
            {

            }


            function closeReply(id)
            {
                $('#reply_to_post'+id).slideUp("slow", "linear");
            }

            function reply(id)
            {
                $('#reply_to_post'+id).slideDown("slow", "linear");
            }

            function replyUploadImg(id)
            {
                $('#uploadImage'+id).click();
            }

           $(document).ready(function (){
               $('.postL').click(function (e) {
                   let id = $(this).attr('data-id');
                   console.log(e.target.className);
                   if (e.target == e.currentTarget || e.target.className == 'divImg m-auto' || e.target.className == 'postText' || e.target.className == 'postContent' || e.target.className =='postUsername') {
                       $(location).attr('href', "/post/showReplies/" + id);
                   }
                   else return;
               });


               $('#iconClicked').click(function (){
                   console.log('detected');
                    $('#uploadImage').click();
               });

              //$('#uploadImage').change(function () {
                   //console.log('yuppp');
                  //var file = this.files[0];
                  //var fileType = file["type"];
                  //var validImageTypes = ["image/jpg", "image/jpeg", "image/png"];
                  //if ($.inArray(fileType, validImageTypes) < 0) {
                      // invalid file type code goes here.

                  //}
                  //else{
                      //var img = $('<img />',
                          //{
                              //class: 'imgPost',
                              //src: $('#uploadImgPost').val(),
                          //})
                          //.appendTo($('#linkIMG'));

                  //}
              //});



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

               $('#txtarea_reply').keydown(function (){
                   if ($.trim($("#txtarea").val()))
                   {

                       $('#buttonPostReply').removeClass('buttonPost');
                       $('#buttonPostReply').addClass('buttonPostAct');
                       $('#buttonPostReply').removeAttr('disabled');

                   }
                   else
                   {
                       $('#buttonPostReply').removeClass('buttonPostAct');
                       $('#buttonPostReply').addClass('buttonPost');
                       $('#buttonPostReply').attr('disabled');
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
