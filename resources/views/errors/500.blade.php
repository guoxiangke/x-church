@section('title', '请点击右下角 使用完整服务')

<x-wechat-layout>
    <div class="page">
        <div class="page__bd page__bd_spacing">
            <br/>
            <a href="javascript:" role="button" title="等待中" class="weui-btn weui-btn_primary weui-btn_loading"><span class="weui-primary-loading weui-primary-loading_transparent"><i class="weui-primary-loading__dot"></i></span> 请点击右下角授权访问您的昵称</a>

            <img  src="{{ asset('/images/pointer.webp') }}" style="transform: rotate(90deg); position: fixed; bottom: 0;right: 0;">
        </div>
    </div>
</x-wechat-layout>