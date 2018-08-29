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
                        {{ config('app.name', 'Laravel') }}
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
        @if (!empty($name))
        $scope.result = "{{ $name }}";
        @else
        $scope.result = "";
        @endif

        $scope.init = function(){
            // check if there is query in url
            // and fire search in case its value is not empty
            var top = "{{ url('/') }}";
            $http({
                method: 'get',
                url: top+'/api/topindex',
            })
            .success(function(data, status, headers, config){
                $scope.channels = data.channels.data;
            })
            .error(function(data, status, headers, config){
                $scope.result = '通信失敗！' + status;
            });
        };

        $scope.start = function(event){
            var clickIndex = $('.play').index(event.target);
            var playIndex = $('.chid').eq(clickIndex).val();
            var ddd = $("#channel-form"+playIndex).serialize();
            var top = "{{ url('/') }}";
            $('.play').eq(clickIndex).css({'display':'none'});
            $('.stop').eq(clickIndex).css({'display':'inline-block'});

            $http({
                method: 'post',
                url: top+'/api/play',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                data: ddd
            })
            .success(function(data, status, headers, config){
                $scope.result = data.channel_name;
            })
            .error(function(data, status, headers, config){
                $scope.result = '通信失敗！' + status;
            });
        };

        $scope.stop = function(event){
            var clickIndex = $('.stop').index(event.target);
            var playIndex = $('.chid').eq(clickIndex).val();
            var ddd = $("#channel-form"+playIndex).serialize();
            var top = "{{ url('/') }}";
            $('.play').eq(clickIndex).css({'display':'inline-block'});
            $('.stop').eq(clickIndex).css({'display':'none'});

            $http({
                method: 'post',
                url: top+'/api/stop',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                data: ddd
            })
            .success(function(data, status, headers, config){
                $scope.result = "";
            })
            .error(function(data, status, headers, config){
                $scope.result = '通信失敗！' + status;
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
