@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Channels
                    <div id="messages">
                        Now playing: <% result %>
                    </div>
                </div>
                {{-- Laravel --}}
                <div class="table-responsive">
                    <div class="panel-body">Shown by Laravel, blade</div>
                    @foreach ($channels as $channel)
                    <form method="post" id="channel-form{{ $channel->id }}">
                        <input type="hidden" name="channel_id" value="{{ $channel->id }}">
                        <input type="hidden" name="channel_name" value="{{ $channel->channel_name }}">
                        <input type="hidden" name="channel_url" value="{{ $channel->channel_url }}">
                        <input type="hidden" class="chid" value="{{ $channel->id }}">
                        {{ csrf_field() }}
                        <div class="ch_cell panel-body">
                            @if ($channel->play == 1)
                            <button class="none btn btn-primary play" type="button" ng-click="start($event)">
                                <span class="glyphicon glyphicon-play"></span> Play
                            </button>
                            <button class="btn btn-default stop" type="button" ng-click="stop($event)">
                                <span class="glyphicon glyphicon-stop"></span> Stop
                            </button>
                            @else
                            <button class="btn btn-primary play" type="button" ng-click="start($event)">
                                <span class="glyphicon glyphicon-play"></span> Play
                            </button>
                            <button class="none btn btn-default stop" type="button" ng-click="stop($event)">
                                <span class="glyphicon glyphicon-stop"></span> Stop
                            </button>
                            @endif
                            <span class="channel_name">{{ $channel->channel_name }}</span>
                        </div>
                    </form>
                    @endforeach
                </div>
                <div class="text-center">{{ $channels->links() }}</div>

                {{-- AngularJS --}}
                <div class="table-responsive">
                    <div class="panel-body">Shown by AngularJS, data binding</div>
                    <form ng-repeat="channel in channels">
                        <input type="hidden" name="channel_id" value="<% channel.id %>">
                        <input type="hidden" name="channel_name" value="<% channel.channel_name %>">
                        <input type="hidden" name="channel_url" value="<% channel.channel_url %>">
                        <input type="hidden" class="chid" value="<% channel.id %>">
                        {{ csrf_field() }}
                        <div class="ch_cell panel-body">
                            @if ($channel->play == 1)
                            <button class="none btn btn-primary play" type="button" ng-click="start($event)">
                                <span class="glyphicon glyphicon-play"></span> Play
                            </button>
                            <button class="btn btn-default stop" type="button" ng-click="stop($event)">
                                <span class="glyphicon glyphicon-stop"></span> Stop
                            </button>
                            @else
                            <button class="btn btn-primary play" type="button" ng-click="start($event)">
                                <span class="glyphicon glyphicon-play"></span> Play
                            </button>
                            <button class="none btn btn-default stop" type="button" ng-click="stop($event)">
                                <span class="glyphicon glyphicon-stop"></span> Stop
                            </button>
                            @endif
                            <span class="channel_name"><% channel.channel_name %></span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
