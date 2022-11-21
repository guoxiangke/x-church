@section('title', '操作失败')
<x-wechat-layout>
	<div class="page">
	    <div class="weui-msg">
	        <div class="weui-msg__icon-area"><i class="weui-icon-warn weui-icon_msg"></i></div>
	        <div class="weui-msg__text-area">
	            <h2 class="weui-msg__title">操作失败</h2>
	            <p class="weui-msg__desc">解释失败的原因<a class="weui-wa-hotarea weui-link" href="javascript:">帮助页面，联系我们</a></p>
	        </div>


	        <div class="weui-msg__opr-area">
	            <p class="weui-btn-area">
	                <a href="javascript:history.back();" role="button" class="weui-btn weui-btn_default">辅助操作</a>
	            </p>
	        </div>
	        <div class="weui-msg__extra-area">
	            <div class="weui-footer">
	                <p class="weui-footer__links">
	                    <a href="javascript:" class="weui-wa-hotarea weui-footer__link">活动签到管理系统</a>
	                </p>
	                <p class="weui-footer__text">Copyright &copy; 2022</p>
	            </div>
	        </div>
	    </div>
	</div>
</x-wechat-layout>