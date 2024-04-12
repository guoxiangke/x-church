@section('title', '授权访问，使用完整服务')

<x-wechat-layout>
    <div class="page">
        <div class="page__bd page__bd_spacing">
            <br/><br/>
            <p class="page__desc"  style="text-align: center;">要使用完整服务，请授权访问您的头像和昵称</p>
            <br/>
            <a href="javascript:" role="button" title="等待中" class="weui-btn weui-btn_default">请点右下角↘</a>
            
            

            <img  src="{{ asset('/images/pointer.webp') }}" style="transform: rotate(90deg); position: fixed; bottom: 60px;right: 0;">
            <div style="padding:10px; font-size:10px; color: #404040; position: fixed; bottom: 10px; text-align:left; width:100%"><a href="https://beian.miit.gov.cn/" target="_blank">京ICP备2024061280号-1</a></div>
            <style type="text/css">
                a,a:visited {
                  color: rgba(255, 255, 255, 0.5);
                }
            </style>
        </div>
    </div>
</x-wechat-layout>