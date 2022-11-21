@section('title', '请点击右下角 使用完整服务')

<x-wechat-layout>
    <div class="page">
        <div class="page__hd">
        </div>
        <div class="page__bd page__bd_spacing">
            <br/>

            <a href="javascript:" role="button" title="等待中" class="weui-btn weui-btn_primary weui-btn_loading"><span class="weui-primary-loading weui-primary-loading_transparent"><i class="weui-primary-loading__dot"></i></span> 请点击右下角</a>
        </div>

        <!--BEGIN toast-->
        <div role="alert" id="textMoreToast" style="display: block;">
            <div class="weui-mask_transparent"></div>
            <div class="weui-toast weui-toast_text-more">
                <i class="weui-icon-warn weui-icon_toast"></i>
                <p class="weui-toast__content">等待 授权获您的昵称</p>
            </div>
        </div>

 

 
    </div>
    <script type="text/javascript">
        // toast
        $(function(){
            var $toast = $('#toast');
            $('#showToast').on('click', function(){
                if ($toast.css('display') != 'none') return;

                $toast.fadeIn(100);
                setTimeout(function () {
                    $toast.fadeOut(100);
                }, 2000);
            });
        });

        // warn
        $(function(){
            var $warnToast = $('#warnToast');
            $('#showWarnToast').on('click', function(){
                if ($warnToast.css('display') != 'none') return;

                $warnToast.fadeIn(100);
                setTimeout(function () {
                    $warnToast.fadeOut(100);
                }, 2000);
            });
        });

        // text-more
        $(function(){
            var $textMoreToast = $('#textMoreToast');
            $('#showTextMoreToast').on('click', function(){
                if ($textMoreToast.css('display') != 'none') return;

                $textMoreToast.fadeIn(100);
                setTimeout(function () {
                    $textMoreToast.fadeOut(100);
                }, 2000);
            });
        });

        // loading
        $(function(){
            var $loadingToast = $('#loadingToast');
            $('#showLoadingToast').on('click', function(){
                if ($loadingToast.css('display') != 'none') return;

                $loadingToast.fadeIn(100);
                setTimeout(function () {
                    $loadingToast.fadeOut(100);
                }, 2000);
            });
        });

        // text
        $(function(){
            var $textToast = $('#textToast');
            $('#showTextToast').on('click', function(){
                if ($textToast.css('display') != 'none') return;

                $textToast.fadeIn(100);
                setTimeout(function () {
                    $textToast.fadeOut(100);
                }, 2000);
            });
        });
    </script>
</x-wechat-layout>