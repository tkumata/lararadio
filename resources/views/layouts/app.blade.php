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
    angular.module('myApp', [], function($interpolateProvider){
        $interpolateProvider.startSymbol('<%');
        $interpolateProvider.endSymbol('%>');
    })
    .controller('MyController', ['$scope', '$http', function($scope, $http){
        // Set root uri on this site from Laravel.
        var top = "{{ url('/') }}";

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

                if (data.name) {
                    $scope.result = res.name;
                } else {
                    $scope.result = "----";
                }
            })
            .error(function(data, status, headers, config){
                $scope.result = '通信失敗！' + status;
            });
        };

        // toggle play/stop button.
        $scope.playing = function(status) {
            if (status == 1) {
                return true;
            } else {
                return false;
            }
        };
        $scope.stoping = function(status) {
            if (status != 1) {
                return true;
            } else {
                return false;
            }
        };

        // play radio by ng-click.
        $scope.start = function(event){
            // Get clicked index.
            var clickIndex = $('.play').index(event.target);
            var formIndex = $('.chid').eq(clickIndex).val();
            var postData = $("#channel-form"+formIndex).serialize();

            $('.play').eq(clickIndex).css({'display':'none'});
            $('.stop').eq(clickIndex).css({'display':'inline-block'});

            $http({
                // need 'headers' because using serialize in above.
                method: 'post',
                url: top + '/api/play',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                data: postData
            })
            .success(function(res, status, headers, config){
                $scope.result = res.channel_name;
            })
            .error(function(res, status, headers, config){
                $scope.result = '通信失敗！ err code: ' + status;
            });
        };

        // stop radio by ng-click.
        $scope.stop = function(event){
            // Get clicked index.
            var clickIndex = $('.stop').index(event.target);
            var formIndex = $('.chid').eq(clickIndex).val();
            var postData = $("#channel-form"+formIndex).serialize();

            $('.play').eq(clickIndex).css({'display':'inline-block'});
            $('.stop').eq(clickIndex).css({'display':'none'});

            $http({
                // need 'headers' because using serialize in above.
                method: 'post',
                url: top + '/api/stop',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                data: postData
            })
            .success(function(res, status, headers, config){
                $scope.result = res.channel_name;
            })
            .error(function(res, status, headers, config){
                $scope.result = '通信失敗！ err code: ' + status;
            });
        };
    }]);

    // $(function(){
    //     $(document).on('click', '.play', function(e){
    //         var clickIndex = $('.play').index(this);
    //         var playIndex = $('.chid').eq(clickIndex).val();
    //         var data = $("#channel-form"+playIndex).serialize();
    //         console.log(data);

    //         $('.play').eq(clickIndex).css({'display':'none'});
    //         $('.stop').eq(clickIndex).css({'display':'inline-block'});

    //         $.ajax({
    //             type: "post",
    //             dataType: 'json',
    //             url: '{{ url('/') }}/api/play',
    //             data: data,
    //             async: true,
    //             timeout: 1000,
    //             success:function(json){
    //                 $("#messages").html('Now playing '+json.channel_name);
    //             },
    //             error:function(json){
    //             }
    //         });
    //     });

    //     $(document).on('click', '.stop', function(e){
    //         var clickIndex = $('.stop').index(this);
    //         var stopIndex = $('.chid').eq(clickIndex).val();
    //         var data = $("#channel-form"+stopIndex).serialize();

    //         $('.play').eq(clickIndex).css({'display':'inline-block'});
    //         $('.stop').eq(clickIndex).css({'display':'none'});

    //         $.ajax({
    //             type: 'post',
    //             dataType: 'json',
    //             url: '{{ url('/') }}/api/stop',
    //             data: data,
    //             async: true,
    //             timeout: 3000,
    //             success:function(){
    //                 $("#messages").html('');
    //             },
    //             error:function(){
    //                 $("#messages").html('処理に失敗しました');
    //             }
    //         });

    //     });
    // });
    </script>
</body>
</html>
