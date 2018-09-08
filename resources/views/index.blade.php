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
                <div class="table-responsive">
                    <div class="ch_cell panel-body" ng-repeat="channel in channels">
                        <span ng-if="playing(channel.id, channel.play)">*</span>
                        <span class="channel_name">ch.<% channel.id %>: <% channel.channel_name %></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
