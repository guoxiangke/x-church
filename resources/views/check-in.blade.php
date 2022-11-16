<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,viewport-fit=cover">
    <meta name="wechat-enable-text-zoom-em" content="true">
    <title>WeUI</title>
    <link rel="stylesheet" href="https://res.wx.qq.com/t/wx_fed/weui-source/res/2.5.14/weui.css">
    <style>
        .body {
		    background-color: var(--weui-BG-0);
		}
		.weui-msg {
			min-height: 100vh;
		}
    </style>

	<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
</head>
<body ontouchstart class="body">
    <div class="container" id="container">
		<div class="page">
		    <div class="weui-msg">
		        <div class="weui-msg__icon-area"><i class="weui-icon-{{$success?'success':'warn'}}  weui-icon_msg"></i></div>
		        <div class="weui-msg__text-area">
		            <h2 class="weui-msg__title">{{$title}}</h2>
		            <p class="weui-msg__desc">{{$message}}<a class="weui-wa-hotarea weui-link" href="{{ route('weixin.bind') }}">绑定操作</a></p>

		            <div class="weui-msg__custom-area">
		              <ul class="weui-form-preview__list">
		                <li role="option" class="weui-form-preview__item"><label class="weui-form-preview__label">姓名</label><p class="weui-form-preview__value">{{$user->name}}</p></li>
		                <li role="option" class="weui-form-preview__item"><label class="weui-form-preview__label">绑定ID</label><p class="weui-form-preview__value">{{$socialId}}</p></li>
		                <li role="option" class="weui-form-preview__item"><label class="weui-form-preview__label">微信号</label><p class="weui-form-preview__value">{{$isBind?'isBind':'false'}}</p></li>
		              </ul>
		            </div>
		        </div>
		        <div class="weui-msg__opr-area">
		            <p class="weui-btn-area">
		                <a href="{{ route('weixin.bind') }}" role="button" class="weui-btn weui-btn_primary">加牧师微信</a>
		            </p>
		        </div>
		        <div class="weui-msg__tips-area">
		          <p class="weui-msg__tips">请复制 绑定ID 发送给您的牧师，完成绑定操作。<a class="weui-wa-hotarea weui-link" href="javascript:">操作视频</a></p>
		        </div>
		        <div class="weui-msg__extra-area">
		            <div class="weui-footer">
		                <p class="weui-footer__links">
		                    <a href="javascript:" class="weui-wa-hotarea weui-footer__link">{{$organization}}活动签到管理系统</a>
		                </p>
		                <p class="weui-footer__text">Copyright &copy; 2008-2016 yilindeli.com</p>
		            </div>
		        </div>
		    </div>
		</div>
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
      });

    </script>

</body>
</html>
