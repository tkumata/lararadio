@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Channels
                </div>
                @foreach ($channels as $channel)
                <form method="post" id="channel-form{{$channel->id}}">
                    <input type="hidden" name="channel_id" value="{{$channel->id}}">
                    <input type="hidden" name="channel_name" value="{{$channel->channel_name}}">
                    <input type="hidden" name="channel_url" value="{{$channel->channel_url}}">
                    {{ csrf_field() }}
                    <div class="panel-body">
                        @if ($channel->play == 1)
                        <button class="btn btn-primary play hidden" type="button">
                            <span class="glyphicon glyphicon-play"></span> Play
                        </button>
                        <button class="btn btn-default stop" type="button">
                            <span class="glyphicon glyphicon-stop"></span> Stop
                        </button>
                        @else
                        <button class="btn btn-primary play" type="button">
                            <span class="glyphicon glyphicon-play"></span> Play
                        </button>
                        <button class="btn btn-default stop hidden" type="button">
                            <span class="glyphicon glyphicon-stop"></span> Stop
                        </button>
                        @endif
                        <span class="ch">{{$channel->channel_name}}</span>
                    </div>
                </form>
                @endforeach
            </div>
        </div>
    </div>
</div>
<script>
$(function(){
    $(document).on('click', '.play', function(e){
//        $('.icon').html('');
        var playIndex = $('.play').index(this) + 1;
        var data = $("#channel-form"+playIndex).serialize();
        $('.play').eq(playIndex - 1).css({'display':'none'});
        $('.stop').eq(playIndex - 1).css({'display':'inline-block'});
        $.ajax({
            type: "post",
            url: "/play",
            data: data,
            async: true,
            success: function(json){
            },
            error:function(){
                $("#"+htmlid).html('処理に失敗しました');
            }
        });
        setTimeout(function(){
            location.reload();
        },3000);
    });

    $(document).on('click', '.stop', function(e){
//        $('.icon').html('');
        var stopIndex = $('.stop').index(this) + 1;
        var data2 = $("#channel-form"+stopIndex).serialize();
        $('.play').eq(stopIndex - 1).css({'display':'inline-block'});
        $('.stop').eq(stopIndex - 1).css({'display':'none'});
        $.ajax({
            type: "post",
            url: "/stop",
            data: data2,
            async: true,
            success: function(json){
            //    var htmlid = 'icon' + json['channel_id'];
            //    $("#"+htmlid).html('');
            },
            error:function(){
                $("#"+htmlid).html('処理に失敗しました');
            }
        });
    });
});
</script>
@endsection
