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
		            <p class="weui-msg__desc">{{$message}}
		            	@if(isset($enrollId))
		            	<br/>
		            	<a class="weui-wa-hotarea weui-link" href="/event_enrolls/{{$enrollId}}/cancel">取消报名？</a></p>
		            	@endif
		            </p>

		            @if(!$isBind)
		            <div class="weui-msg__custom-area">
		              <ul class="weui-form-preview__list">
		                <li role="option" class="weui-form-preview__item"><label class="weui-form-preview__label">微信号</label><p class="weui-form-preview__value">未绑定</p></li>
		                <li role="option" class="weui-form-preview__item"><label class="weui-form-preview__label">绑定码</label><p class="weui-form-preview__value">{{$code6}}</p></li>
		              </ul>
		            </div>
		            @endif

		            @if($isBind && $success)
		            <div class="weui-msg__custom-area">
		              <ul class="weui-form-preview__list">
		                <li role="option" class="weui-form-preview__item"><label class="weui-form-preview__label">名称</label><p class="weui-form-preview__value">{{$event->name}}</p></li>
		                <li role="option" class="weui-form-preview__item"><label class="weui-form-preview__label">时间</label><p class="weui-form-preview__value">{{$event->begin_at->format("D M j H:i")}} ～ {{$event->begin_at->addHours($event->duration_hours)->format("D M j H:i")}}</p></li>
		              </ul>
		            </div>
		            @endif
		        </div>
		        @if(!$isBind)
		        <div class="weui-msg__opr-area">
		            <p class="weui-btn-area">
		                <a href="{{$organization->wechat_qr_url?:'https://www.yilindeli.com/assets/WechatIMG551.jpeg'}}" role="button" class="weui-btn weui-btn_primary">牧师微信</a>
		            </p>
		        </div>
		        <div class="weui-msg__tips-area">
		          <p class="weui-msg__tips">请复制本段信息 {{$code6}} 发送到牧师微信，获取活动详情、通知提醒、导航信息。此码60s内有效</p>
		        </div>
		        @endif
		        @if(isset($enrollId))
		        <div class="weui-msg__opr-area">
		            <p class="weui-btn-area">
		                <a href="http://maps.google.com/maps?q={{$event->address}}" role="button" class="weui-btn weui-btn_primary">导航🧭</a>
		            </p>
		        </div>
		        @endif
		        <div class="weui-msg__extra-area">
		            <div class="weui-footer">
		                <p class="weui-footer__links">
		                    <a href="javascript:" class="weui-wa-hotarea weui-footer__link">{{$organization->name_cn}}活动签到管理系统</a>
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
