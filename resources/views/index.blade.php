@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Channels
                </div>
                <div class="panel-heading">
                    Now playing... <div id="now"></div>
                </div>
                @foreach ($channels as $channel)
                <form method="post" id="channel-form{{$channel->id}}">
                {{ csrf_field() }}
                <div class="panel-body">
                    {{$channel->channel_name}}
                    <input type="hidden" name="channel_id" value="{{$channel->id}}">
                    <input type="hidden" name="channel_name" value="{{$channel->channel_name}}">
                    <input type="hidden" name="channel_url" value="{{$channel->channel_url}}">
                    <button type="button" class="play">play</button>
                    <button type="button" class="stop">stop</button>
                    <div id="icon{{$channel->id}}" class="icon"></div>
                </div>
                </form>
                @endforeach
            </div>
        </div>
    </div>
</div>
<script>
$(function(){
    // load 時
    $('.stop').css({'display':'none'});

    // click 時
    $(document).on('click', '.play', function(e){
        $('.icon').html('');
        var playIndex = $('.play').index(this) + 1;
        var data = $("#channel-form"+playIndex).serialize();
        $.ajax({
            type: "post",
            url: "/play",
            data: data,
            success: function(json){
                var htmlid = 'icon' + json['channel_id'];
                $("#"+htmlid).html('playing...');
                $("#now").html(json['channel_name']);
                $('.play').eq(playIndex - 1).css({'display':'none'});
                $('.stop').eq(playIndex - 1).css({'display':'inline-block','color':'#c00'});
            },
            error:function(){
                $("#"+htmlid).html('処理に失敗しました');
            }
        });
    });

    $(document).on('click', '.stop', function(e){
        $('.icon').html('');
        var stopIndex = $('.stop').index(this) + 1;
        var data = $("#channel-form"+stopIndex).serialize();
        $.ajax({
            type: "post",
            url: "/stop",
            data: data,
            success: function(json){
                var htmlid = 'icon' + json['channel_id'];
                $("#"+htmlid).html('');
                $("#now").html('');
                $('.play').eq(stopIndex - 1).css({'display':'inline-block'});
                $('.stop').eq(stopIndex - 1).css({'display':'none'});
            },
            error:function(){
                $("#"+htmlid).html('処理に失敗しました');
            }
        });
    });
});
</script>
@endsection
