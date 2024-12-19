@section('title', $title)
<x-wechat-layout>
<div class="page article js_show">
  <article class="weui-article">

    <div style="text-align: center; margin-bottom: 1em;">
        <img 
            style="width: 50px; margin: 0 auto; display: block;" 
            src="https://www.mbcotc.org/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fmbcotc_logo.fed8915b.png&w=256&q=75" 
            alt="配图">
    </div> 
    {!! $content !!}

    <div style="text-align: center;">
        <i role="img" title="成功" aria-describedby="tip_1" class="weui-icon-success weui-icon_msg"></i>
            <h3 class="icon-box__title">🎉 恭喜，挑战成功 🎉</h3>
            <div class="icon-box__desc" id="tip_1">已有 {{count($eventEnrolls)}} 人完成了今日打卡！</div>
            <br>
        <section class="avatars-group stacked">
          @foreach($eventEnrolls as $eer)
          <div class="avatars-group__item"><span class="v-tooltip v-tooltip--bottom"><span><div class="v-avatar"> <img src="{{$eer->profile_photo_url}}" alt=""></div></span></span></div>
          @endforeach
      </section>
    </div>
    </section>

  </article>
</div>

<div class="weui-footer">
    <p class="weui-footer__links">
        <a href="javascript:" class="weui-wa-hotarea weui-footer__link">{{$systemName}}</a>
    </p>
    <p class="weui-footer__text">Copyright &copy; {{$dateY}}</p>
</div>


    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
    <script type="text/javascript">
        // 定义标志位，初始为 false
        let hasReachedBottom = false;

        // 添加滚动事件监听器
        window.addEventListener('scroll', function () {
            // 如果已触发过事件，则不再执行
            if (hasReachedBottom) return;

            // 获取页面的总高度
            const scrollHeight = document.documentElement.scrollHeight;

            // 获取用户当前的滚动位置 + 视口高度
            const scrollPosition = window.innerHeight + window.scrollY;

            // 如果滚动位置接近底部（允许误差1像素）
            if (scrollPosition >= scrollHeight - 1) {
                console.log('用户已滚动到页面底部！');
                confetti({
                  particleCount: 100,
                  spread: 70,
                  origin: { y: 0.6 }
                });
                // 设置标志位为 true
                hasReachedBottom = true;
            }
        });
    </script>
    <style type="text/css">
        p {
            text-align: justify; /* 设置文字两端对齐 */
            letter-spacing: 0.1em; /* 增加字间距，0.1em是一个常见的适中值，可根据需要调整 */
            line-height: 1.6; /* 设置行高以提高可读性（可选） */
            color: rgba(0, 0, 0, 0.8);
        }
        h2 a{
            color: rgba(0, 0, 0, 0.8);
        }
        .weui-footer {
            display: flex;               /* 使用 Flexbox */
            flex-direction: column;      /* 垂直排列子元素 */
            align-items: center;         /* 水平居中 */
            justify-content: center;     /* 垂直居中 */
            padding: 20px 0;             /* 上下内边距 */
            background-color: #f9f9f9;   /* 可选：背景颜色 */
            color: #666;                 /* 可选：文字颜色 */
            font-size: 14px;             /* 字体大小 */
            text-align: center;          /* 文本居中 */
        }
    </style>

    <style type="text/css">
        .v-avatar{
            width: 30px;
            height: 30px;
        }
        .avatars-group.grid {
          display: grid;
          grid-gap: 8px;
          grid-template-columns: repeat(auto-fit, minmax(48px, 1fr));
        }
        .avatars-group.stacked {
          display: flex;
          flex-direction: row;
          direction: ltr;
          max-width: 100%;
          overflow: hidden;
          overflow-x: auto;
          white-space: nowrap;
        }
        .avatars-group.stacked > * {
          margin-right: -8px;
        }
        .avatars-group.stacked > *:last-of-type {
          padding-right: 16px;
        }
        .avatars-group__item {
          cursor: default;
          transition: all 0.1s ease-out;
        }
        .avatars-group__item.more {
          align-items: center;
          display: flex;
        }
        .avatars-group__item.more:hover {
          transform: none;
        }
        .avatars-group__item:hover {
          transform: translateY(0px);
          z-index: 1;
        }
         

        .avatars-group .v-avatar span {
          align-items: center;
          display: flex;
          font-size: 110%;
          font-weight: 700;
          height: 100%;
          justify-content: center;
          letter-spacing: 0.1rem;
          text-shadow: 0px 0px 2px rgba(0,0,0,0.56);
          width: inherit;
        }


        .v-avatar img {
          border-radius: 50%;
          display: inline-flex;
          height: inherit;
          width: inherit;
        }
         
        .avatars-group .v-avatar img {
          padding: 2px;
        }
        .avatars-group .v-avatar span {
          align-items: center;
          display: flex;
          font-size: 110%;
          font-weight: 700;
          height: 100%;
          justify-content: center;
          letter-spacing: 0.1rem;
          text-shadow: 0px 0px 2px rgba(0,0,0,0.56);
          width: inherit;
        }
        .v-avatar.bordered {
          box-shadow: 0px 0px 0px 2px #fff inset;
        }
        .v-avatar.bordered img {
          padding: 2px;
        }
        .v-avatar.bordered.small {
          box-shadow: 0px 0px 0px 1px #fff inset;
        }
        .v-avatar.bordered.small img {
          padding: 1px;
        }

        .avatars-group .v-avatar {
          box-shadow: 0px 0px 0px 2px #fff inset;
        }
        .v-avatar {
          align-items: center;
          border-radius: 50%;
          display: inline-flex;
          justify-content: center;
          position: relative;
          text-align: center;
          vertical-align: middle;
        }
    </style>

</x-wechat-layout>