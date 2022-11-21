@section('title', '操作成功')
<x-wechat-layout>
	<div class="page">
	    <div class="weui-msg">
	        <div class="weui-msg__icon-area"><i class="weui-icon-success weui-icon_msg"></i></div>
	        <div class="weui-msg__text-area">
	            <h2 class="weui-msg__title">操作成功</h2>
	        </div>
	        <div class="weui-msg__tips-area">
	          <p class="weui-msg__tips">您可以放心关闭本页</p>
	        </div>
	        <div class="weui-msg__opr-area">
	            <p class="weui-btn-area">
	                <a id="close" href="javascript:" role="button" class="weui-btn weui-btn_primary">关闭</a>
	            </p>
	        </div>

	        <div class="weui-msg__extra-area">
	            <div class="weui-footer">
	                <p class="weui-footer__links">
	                    <a href="javascript:" class="weui-wa-hotarea weui-footer__link">活动签到管理系统</a>
	                </p>
	                <p class="weui-footer__text">Copyright &copy; 2022 </p>
	            </div>
	        </div>
	    </div>
	</div>
</x-wechat-layout>