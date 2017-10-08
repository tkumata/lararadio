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
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                    @foreach ($channels as $channel)
                    <form method="post" id="channel-form{{$channel->id}}">
                        <input type="hidden" name="channel_id" value="{{$channel->id}}">
                        <input type="hidden" name="channel_name" value="{{$channel->channel_name}}">
                        <input type="hidden" name="channel_url" value="{{$channel->channel_url}}">
                        <input type="hidden" class="chid" value="{{$channel->id}}">
                        {{ csrf_field() }}
                        <tr class="row">
                            <td><span>{{$channel->id}}</span></td>
                            <td>
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
                            </td>
                            <td><span>{{$channel->channel_name}}</span></td>
                        </tr>
                    </form>
                    @endforeach
                    </table>
                </div>
                <div class="text-center">{{ $channels->links() }}</div>
            </div>
        </div>
    </div>
</div>
<script>
$(function(){
    $(document).on('click', '.play', function(e){
        var clickIndex = $('.play').index(this);
        var playIndex = $('.chid').eq(clickIndex).val();
        var data = $("#channel-form"+playIndex).serialize();
        $('.play').eq(clickIndex).css({'display':'none'});
        $('.stop').eq(clickIndex).css({'display':'inline-block'});
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
        $('.play').eq(clickIndex).css({'display':'inline-block'});
        $('.stop').eq(clickIndex).css({'display':'none'});
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
