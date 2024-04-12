<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>
          @if(isset($title)) {{ $title }}
          @else
            @yield('title')
          @endif
          | {{ config('app.name', 'Laravel') }}
        </title>


        <link rel="icon" type="image/png" sizes="16x16" href="https://wj.qq.com/favicon-16x16.png">
        <link rel="icon" type="image/png" sizes="16x16" href="https://wj.qq.com/favicon-16x16.png">
        <link rel="icon" type="image/png" sizes="32x32" href="https://wj.qq.com/favicon-32x32.png">
        <link rel="mask-icon" href="https://wj.qq.com/safari-pinned-tab.svg" color="#5bbad5">
        <link rel="manifest" href="https://wj.qq.com/site.webmanifest">
        
        <!-- Scripts -->
        @vite(['resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles

        <!-- Fonts -->
        <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,viewport-fit=cover">
        <meta name="wechat-enable-text-zoom-em" content="true">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="stylesheet" href="https://res.wx.qq.com/t/wx_fed/weui-source/res/2.5.14/weui.css">
        <style>
            .weui-msg {
                min-height: 100vh;
            }
            .page__desc {
              margin-top: 4px;
              color: var(--weui-FG-1);
              text-align: left;
              font-size: 14px;
            }
            a {
              color: rgba(255, 255, 255, 0.5);
            }
            a:visited {
              color: rgba(255, 255, 255, 0.5);
            }
        </style>

        <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

        <!-- Scripts -->
        @vite(['resources/js/app.js'])
    </head>
    <body ontouchstart class="body">
        <div class="container" id="container">
             {{ $slot }}
        </div>
        
        <script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
        <script src="https://res.wx.qq.com/t/wx_fed/cdn_libs/res/weui/1.2.8/weui.min.js"></script>

        <script type="text/javascript">
          function wxReady(callback) {
            if (
              typeof WeixinJSBridge === 'object' &&
              typeof window.WeixinJSBridge.invoke === 'function'
            ) {
              callback()
            } else {
              document.addEventListener('WeixinJSBridgeReady', callback, false)
            }
          }
          wxReady(function() {
            WeixinJSBridge.invoke('getUserConfig', {}, function(res) {
              if (res.isCareMode) {
                document.body.setAttribute('data-weui-mode','care');
              }
            });
            // 隐藏所有非基础按钮接口
            wx.hideAllNonBaseMenuItem();
            // 批量隐藏功能按钮接口
            wx.hideMenuItems({
            });

            $('#close').click(function(){
              wx.closeWindow();
            });
          });

        </script>
        @stack('modals')

        @livewireScripts
    </body>
</html>
