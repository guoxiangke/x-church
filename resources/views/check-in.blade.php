<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Check-IN</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        
        <!-- Styles -->
        <link rel="stylesheet" href="https://res.wx.qq.com/t/wx_fed/weui-source/res/2.5.14/weui.css">
        <style>
        </style>


        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
            .body {
			    background-color: var(--weui-BG-0);
			}
        </style>

<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    </head>
	<body class="antialiased body">
    <div class="page">
	    <div class="page__hd">

	    </div>
	    <div class="page__bd page__bd_spacing">
	    </div>

	    <div id="dialogs">
	        <!--BEGIN dialog1-->
	        <div class="js_dialog" role="dialog"  aria-hidden="true" aria-modal="true" aria-labelledby="js_title1" id="iosDialog1" style="display: block;">
	            <div class="weui-mask"></div>
	            <div class="weui-dialog">

	                <div class="weui-dialog__hd"><strong class="weui-dialog__title" id="js_title1">{{$title}}</strong></div>
	                <div class="weui-dialog__bd">{{$message}}</div>
	                <div class="weui-dialog__ft">
	                    <a role="button" href="{{ route('weixin.bind') }}" class="weui-dialog__btn weui-dialog__btn_default">绑定操作</a>
	                    <a role="button" href="{{ route('weixin.bind') }}" class="weui-dialog__btn weui-dialog__btn_primary">加牧师微信</a>
	                </div>
	            </div>
	        </div>
	        <!--END dialog1-->
    	</div>
	</div>

	<script type="text/javascript">
	    $(function(){
	        var $iosDialog1 = $('#iosDialog1'),
	            $iosDialog2 = $('#iosDialog2'),
	            $androidDialog1 = $('#androidDialog1'),
	            $androidDialog2 = $('#androidDialog2');

	        $('#dialogs').on('click', '.weui-dialog__btn', function(){
	            $(this).parents('.js_dialog').fadeOut(200);
	            $(this).parents('.js_dialog').attr('aria-hidden','true');
	            $(this).parents('.js_dialog').removeAttr('tabindex');
	        });

	        $('#showIOSDialog1').on('click', function(){
	            $iosDialog1.fadeIn(200);
	            $iosDialog1.attr('aria-hidden','false');
	            $iosDialog1.attr('tabindex','0');
	            $iosDialog1.trigger('focus');
	        });
	    });
	</script>
	<script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script src="https://res.wx.qq.com/t/wx_fed/cdn_libs/res/weui/1.2.8/weui.min.js"></script>
    </body>
</html>
