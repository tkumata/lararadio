@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div>
                        <rzslider rz-slider-model="slider.value" rz-slider-options="slider.options"></rzslider>
                    </div>
                    <span>
                        <button class="btn btn-primary" type="button" ng-click="play(slider.value)">
                            <span class="glyphicon glyphicon-play"></span> Play
                        </button>
                        <button class="btn btn-default" type="button" ng-click="stop(slider.value)">
                            <span class="glyphicon glyphicon-stop"></span> Stop
                        </button>
                    </span>
                    <div id="messages">
                        Now playing: <% result %>
                    </div>
                </div>
                {{-- AngularJS --}}
                <div class="table-responsive">
                    <form ng-repeat="channel in channels" id="channel-form<% channel.id %>">
                        <input type="hidden" name="channel_id" value="<% channel.id %>">
                        <input type="hidden" name="channel_name" value="<% channel.channel_name %>">
                        <input type="hidden" name="channel_url" value="<% channel.channel_url %>">
                        <input type="hidden" class="chid" value="<% channel.id %>">
                        {{ csrf_field() }}
                        <div class="ch_cell panel-body">
                            <!--
                            {{-- Play button --}}
                            <span ng-if="playing(channel.play)">
                                <button class="none btn btn-primary play" type="button" ng-click="start($event)">
                                    <span class="glyphicon glyphicon-play"></span> Play
                                </button>
                                <button class="btn btn-default stop" type="button" ng-click="stop($event)">
                                    <span class="glyphicon glyphicon-stop"></span> Stop
                                </button>
                            </span>
                            {{-- Stop button --}}
                            <span ng-if="stoping(channel.play)">
                                <button class="btn btn-primary play" type="button" ng-click="start($event)">
                                    <span class="glyphicon glyphicon-play"></span> Play
                                </button>
                                <button class="none btn btn-default stop" type="button" ng-click="stop($event)">
                                    <span class="glyphicon glyphicon-stop"></span> Stop
                                </button>
                            </span>
                            -->
                            {{-- Channel name --}}
                            <span class="channel_name">ch.<% channel.id %>: <% channel.channel_name %></span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
