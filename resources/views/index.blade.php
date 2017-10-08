@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Channels
                    <div id="messages"></div>
                </div>
                @foreach ($channels as $channel)
                <form method="post" id="channel-form{{$channel->id}}">
                    <input type="hidden" name="channel_id" value="{{$channel->id}}">
                    <input type="hidden" name="channel_name" value="{{$channel->channel_name}}">
                    <input type="hidden" name="channel_url" value="{{$channel->channel_url}}">
                    <input type="hidden" class="chid" value="{{$channel->id}}">
                    {{ csrf_field() }}
                    <div class="panel-body">
                        @if ($channel->play == 1)
                        <button class="btn btn-primary play" type="button" style="display:none;">
                            <span class="glyphicon glyphicon-play"></span> Play
                        </button>
                        <button class="btn btn-default stop" type="button">
                            <span class="glyphicon glyphicon-stop"></span> Stop
                        </button>
                        @else
                        <button class="btn btn-primary play" type="button">
                            <span class="glyphicon glyphicon-play"></span> Play
                        </button>
                        <button class="btn btn-default stop" type="button" style="display:none;">
                            <span class="glyphicon glyphicon-stop"></span> Stop
                        </button>
                        @endif
                        <span class="ch">{{$channel->channel_name}}</span>
                    </div>
                </form>
                @endforeach
                {{ $channels->links() }}
            </div>
        </div>
    </div>
</div>
<script>
$(function(){
    $(document).on('click', '.play', function(e){
        // $('.icon').html('');
        var clickIndex = $('.play').index(this);
        var playIndex = $('.chid').eq(clickIndex).val();
        var data = $("#channel-form"+playIndex).serialize();
        $('.play').eq(playIndex).css({'display':'none'});
        $('.stop').eq(playIndex).css({'display':'inline-block'});
        $.ajax({
            type: "post",
            url: "/play",
            data: data,
            async: true,
            success: function(json){
            },
            error:function(){
                $("#messages").html('処理に失敗しました');
            }
        });
        setTimeout(function(){
            location.reload();
        },777);
    });

    $(document).on('click', '.stop', function(e){
        // $('.icon').html('');
        var clickIndex = $('.stop').index(this);
        var stopIndex = $('.chid').eq(clickIndex).val();
        var data2 = $("#channel-form"+stopIndex).serialize();
        $('.play').eq(stopIndex).css({'display':'inline-block'});
        $('.stop').eq(stopIndex).css({'display':'none'});
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
                $("#messages").html('処理に失敗しました');
            }
        });
    });
});
</script>
@endsection
