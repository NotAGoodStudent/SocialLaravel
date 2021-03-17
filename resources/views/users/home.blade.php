@extends('layouts.app')

@section('content')
    <body>
        <link href="{{ asset('css/homeStyle.css') }}" rel="stylesheet">
        <div class="m-auto">
            <div class="thoughts">
                <div class="textfield_container m-auto">
                    <img class="mx-auto d-block" style="width: 50px; height: 50px; border-radius: 50%" src="https://pngimage.net/wp-content/uploads/2018/06/no-photo-avatar-png-6.png">
                    <form action="{{ route('makePost', auth()->user()->id)}}" method="post">
                        @csrf
                    <textarea id="txtarea" name="post" class="txt-area mx-auto d-block" placeholder="What are your thoughts {{auth()->user()->username}}?"></textarea>
                        <input class='buttonPost float-right' id="buttonAct" type="submit" name="" value="Post" disabled>
                    </form>
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
        </script>
    </body>
@endsection
