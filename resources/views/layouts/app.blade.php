<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" ng-app="myApp">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <?php
    /**
     * Checking home url (url('/')) is subdir or not.
     *
     * ex, http://localhost or http://localhost/lalaradio
     */
    preg_match('@https?://.*?/(.*$)@', url('/'), $tmp);
    ?>
    @if (!empty($tmp) && $tmp[1])
    <link href="{{ asset('css/app_subdir.css') }}" rel="stylesheet">
    @else
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @endif
    <link href="{{ asset('css/my.css') }}" rel="stylesheet">

    <!-- angularJS -->
    <script src="//code.angularjs.org/1.5.7/angular.min.js"></script>

    <!-- angularJS Slide -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/angularjs-slider/6.6.1/rzslider.css" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/angularjs-slider/6.6.1/rzslider.min.js"></script>
</head>
<body ng-controller="MyController" data-ng-init="init()">
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }} with angularJS
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
    // app.js
    angular.module('myApp', ['rzModule'], function($interpolateProvider){
        $interpolateProvider.startSymbol('<%');
        $interpolateProvider.endSymbol('%>');
    })
    .controller('MyController', ['$scope', '$http', function($scope, $http){
        // Set root uri on this site from Laravel.
        var top = "{{ url('/') }}";
        var now = 1;

        // Initial function.
        $scope.init = function(){
            // Get channels that included url, name, id, status from API.
            $http({
                method: 'get',
                url: top + '/api/topindex',
            })
            .success(function(res, status, headers, config){
                // data binding for ng-repeat.
                $scope.channels = res.channels;
                now = res.ch;

                if (res.name) {
                    $scope.result = res.name;
                    $scope.slider = {
                        value: now,
                        options: {
                            floor: 1,
                            ceil: 12
                        }
                    };
                } else {
                    $scope.result = "----";
                }
            })
            .error(function(res, status, headers, config){
                $scope.result = '通信失敗！' + status;
            });
        };

        // Slide options.
        $scope.slider = {
            value: now,
            options: {
                floor: 1,
                ceil: 12
            }
        };

        $scope.playing = function(id, status) {
            if (status == 1) {
                return true;
            } else {
                return false;
            }
        };

        // play radio by ng-click.
        $scope.play = function(value){
            $http({
                method: 'post',
                url: top + '/api/play2',
                data: { 'channel_id':value }
            })
            .success(function(res, status, headers, config){
                $scope.result = res.channel_name;
                $scope.playing = function(id, status) {
                    if (res.channel_id == id) {
                        return true;
                    } else {
                        return false;
                    }
                };
            })
            .error(function(res, status, headers, config){
                $scope.result = '通信失敗！ err code: ' + status;
            });
        };
        // stop radio by ng-click.
        $scope.stop = function(value){
            $http({
                method: 'post',
                url: top + '/api/stop2',
                data: { 'channel_id':value }
            })
            .success(function(res, status, headers, config){
                $scope.result = res.channel_name;
                $scope.playing = function(id, status) {
                    return false;
                };
            })
            .error(function(res, status, headers, config){
                $scope.result = '通信失敗！ err code: ' + status;
            });
        };
    }]);
    </script>
</body>
</html>
