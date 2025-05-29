@section('title', $event->name.'|'.$event->organization->system_name)

<x-wechat-layout>
		<div class="page">
		    <div class="weui-msg">
		        <div class="weui-msg__icon-area"><i class="weui-icon-{{$success?'success':'warn'}}  weui-icon_msg"></i></div>
		        <div class="weui-msg__text-area">
		            <h2 class="weui-msg__title">{{$title}}</h2>
		            <p class="weui-msg__desc">{{$message}}</p>

		            @if($isBind && $success)
		            <div class="weui-msg__custom-area">
		              <ul class="weui-form-preview__list">
		                <li role="option" class="weui-form-preview__item"><label class="weui-form-preview__label">名称</label><p class="weui-form-preview__value">{{$event->name}}</p></li>
		                <li role="option" class="weui-form-preview__item"><label class="weui-form-preview__label">简介</label><p class="weui-form-preview__value">{{$event->description}}</p></li>
		              </ul>
		            </div>
		            @endif
		        </div>
			        @if($event->is_need_telephone || $event->is_need_name)
			        	@if(!($social->nickname && $social->telephone))
			        	<livewire:social-telephone-update :social="$social" :event="$event"/>
			        	@endif
			        @endif
				<div class="weui-msg__text-area">
			        @if(!$isBind)
			        <div class="weui-msg__tips-area">
			          <p class="weui-msg__tips">及时获取活动动态？请微信发送 <span style="font-size: 24px">{{$code6}}</span>  给<a style="color: #fff;" href="{{$organization->wechat_qr_url?:'https://www.yilindeli.com/assets/WechatIMG551.jpeg'}}" role="button" class="weui-btn weui-btn_mini weui-btn_primary weui-wa-hotarea">{{$organization->wechat_ai_title??'AI助理'}}微信</a></p>
			        </div>
			        @endif

			        @if(isset($eventEnroll) && !$eventEnroll->canceled_at)
			        <div class="weui-msg__opr-area">
		                @if($event->is_need_remark)
		                <div class="" id="showDialog2" ><a class="weui-btn weui-btn_primary" href="javascript:">您的留言</a></div>
		                @endif
		            	@if($event->cancel_ahead_hours)
		                	<div class="weui-cells__tips showIOSDialog" id="showDialog3" ><a class="weui-link weui-wa-hotarea" href="javascript:">取消报名</a></div>
		                @endif

			        </div>
			        @endif


		            @if(!in_array($status,[3,7,0]))
		            <div class="weui-msg__custom-area">
		              <ul class="weui-form-preview__list">
		                <li role="option" class="weui-form-preview__item"><label class="weui-form-preview__label">活动时间</label><p class="weui-form-preview__value">{{$event->begin_at->format("M j H:i")}} ～ {{$event->begin_at->addHours($event->duration_hours)->format("M j H:i")}}</p></li><li role="option" class="weui-form-preview__item"><label class="weui-form-preview__label">活动时长</label><p class="weui-form-preview__value">{{$event->duration_hours}}小时</p></li>
		                <li role="option" class="weui-form-preview__item"><label class="weui-form-preview__label">签到时间</label><p class="weui-form-preview__value">开始前{{$event->check_in_ahead}}分钟</p></li>
		                <li role="option" class="weui-form-preview__item"><label class="weui-form-preview__label">当前时间</label><p class="weui-form-preview__value">{{now()->format("M j H:i Y D")}}</p></li>
		                @if($event->is_show_avatar && isset($eventEnrolls))
		                <li role="option" class="weui-form-preview__item"><label class="weui-form-preview__label">报名人数</label><p class="weui-form-preview__value">{{$eventEnrolls->sum('count_adult') + $eventEnrolls->sum('count_child')}}</p></li>
		                <li role="option" class="weui-form-preview__item"><label class="weui-form-preview__label">报名人员</label><p class="weui-form-preview__value"> 
		                	<section class="avatars-group p-3 stacked">
						      @foreach($eventEnrolls as $eer)
						      <div class="avatars-group__item"><span class="v-tooltip v-tooltip--bottom"><span><div class="v-avatar"> <img src="{{$eer->user->profile_photo_url}}" alt=""></div></span></span></div>
						      @endforeach
						  </section>
		                </p></li>
		                @endif
		              </ul>
		            </div>
		            <div><a href="http://maps.google.com/maps?q={{$event->address}}" role="button" class="weui-btn weui-btn_disabled weui-btn_primary">查看活动地点</a></div>
		            @endif
		        </div>

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

		        <div class="weui-msg__extra-area">
		            <div class="weui-footer">
		                <p class="weui-footer__links">
		                    <a href="javascript:" class="weui-wa-hotarea weui-footer__link">{{$organization->system_name??$organization->name_abbr}}活动签到管理系统</a>
		                </p>
		                <p class="weui-footer__text">Copyright &copy; 2022 {{$organization->website_url??'yilindeli.com'}}</p>
		            </div>
		        </div>
		    </div>

		    @if(isset($eventEnroll))
		    <div id="dialogs">
		      <!--BEGIN dialog1-->

		        <div id="dialogWrap2" class="js_dialog_wrap" ref="showDialog2" aria-label="弹窗标题" role="dialog" aria-modal="false" aria-hidden="true" style="display: none;">
		            <div aria-label="关闭" role="button" class="js_close weui-mask"></div>
		            <div id="js_dialog_2" class="js_dialog weui-half-screen-dialog">
		                <div class="weui-half-screen-dialog__hd">
		                  <div class="weui-half-screen-dialog__hd__main">
		                    <div class="weui-flex" style="align-items: center; font-size: 14px;">
		                      <img src="{{$user->profile_photo_path??'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC4AAAAuCAMAAABgZ9sFAAAAVFBMVEXx8fHMzMzr6+vn5+fv7+/t7e3d3d2+vr7W1tbHx8eysrKdnZ3p6enk5OTR0dG7u7u3t7ejo6PY2Njh4eHf39/T09PExMSvr6+goKCqqqqnp6e4uLgcLY/OAAAAnklEQVRIx+3RSRLDIAxE0QYhAbGZPNu5/z0zrXHiqiz5W72FqhqtVuuXAl3iOV7iPV/iSsAqZa9BS7YOmMXnNNX4TWGxRMn3R6SxRNgy0bzXOW8EBO8SAClsPdB3psqlvG+Lw7ONXg/pTld52BjgSSkA3PV2OOemjIDcZQWgVvONw60q7sIpR38EnHPSMDQ4MjDjLPozhAkGrVbr/z0ANjAF4AcbXmYAAAAASUVORK5CYII='}}" alt="" style="width: 24px; margin-right: 8px; border-radius: 50%; display: block;">
		                      {{$user->name??'昵称'}}
		                    </div>
		                  </div>
		                </div>
				    	
				    	<livewire:enrollment-more-info :eventEnroll="$eventEnroll"/>
				    	
		            </div>
		        </div>

		        <div id="dialogWrap3" class="js_dialog_wrap" ref="showDialog4" aria-label="弹窗标题" role="dialog" aria-modal="false" aria-hidden="true" style="display: none;">
		            <div aria-label="关闭" role="button" class="js_close weui-mask"></div>
		            <div id="js_dialog_4" class="js_dialog weui-half-screen-dialog">
		                <div class="weui-half-screen-dialog__hd">
		                  <div class="weui-half-screen-dialog__hd__main">
		                    <div class="weui-flex" style="align-items: center; font-size: 14px;">
		                      <img src="{{$social->avatar??'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC4AAAAuCAMAAABgZ9sFAAAAVFBMVEXx8fHMzMzr6+vn5+fv7+/t7e3d3d2+vr7W1tbHx8eysrKdnZ3p6enk5OTR0dG7u7u3t7ejo6PY2Njh4eHf39/T09PExMSvr6+goKCqqqqnp6e4uLgcLY/OAAAAnklEQVRIx+3RSRLDIAxE0QYhAbGZPNu5/z0zrXHiqiz5W72FqhqtVuuXAl3iOV7iPV/iSsAqZa9BS7YOmMXnNNX4TWGxRMn3R6SxRNgy0bzXOW8EBO8SAClsPdB3psqlvG+Lw7ONXg/pTld52BjgSSkA3PV2OOemjIDcZQWgVvONw60q7sIpR38EnHPSMDQ4MjDjLPozhAkGrVbr/z0ANjAF4AcbXmYAAAAASUVORK5CYII='}}" alt="" style="width: 24px; margin-right: 8px; border-radius: 50%; display: block;">
		                      {{$social->name??'昵称'}}
		                    </div>
		                  </div>
		                </div>
		                <div class="weui-half-screen-dialog__bd">
		                  <br>
		                  取消后，将不可以再次报名：确定要取消吗？
		                  <br>
		                </div>
		                <div class="weui-half-screen-dialog__ft">
		                  <div id="js_wrap_btn_area" class="weui-half-screen-dialog__btn-area">
		                  	<livewire:enrollment-cancel :eventEnroll="$eventEnroll"/>
		                  </div>
		                </div>
		            </div>
		        </div>
			</div>
			@endif

		</div>
		<style type="text/css">
			#js_input3:focus,#textarea:focus {
			    outline: none !important;
			    border:1px solid var(--weui-BRAND);
			    box-shadow: 0 0 10px var(--weui-BRAND);
			  }
			#js_input3,#textarea {
			    outline: none !important;
			    border:1px solid var(--weui-BRAND);
			  }

		</style>
	<script type="text/javascript">
		 $(function(){
	        const $dialog2 = $('#js_dialog_2');
	        const $dialog3 = $('#js_dialog_3');
	        const $dialogWrap2 = $('#dialogWrap2');
	        const $dialogWrap3 = $('#dialogWrap3');

	        function closeDialog(o){
	          const $jsDialogWrap = o.parents('.js_dialog_wrap');
	          $jsDialogWrap.attr('aria-hidden','true').attr('aria-modal','false').removeAttr('tabindex');
	          $jsDialogWrap.fadeOut(300);
	          $jsDialogWrap.find('.js_dialog').removeClass('weui-half-screen-dialog_show');
	          setTimeout(function(){
	            $('#' + $jsDialogWrap.attr('ref')).trigger('focus');
	          }, 300);
	        }

	        $('.js_dialog_wrap').on('touchmove', function(e) {
	            if($.contains(document.getElementById('js_wrap_content'), e.target)){
	            } else {
	              e.preventDefault();
	            }
	        });

	        $('.js_close').on('click', function() {
	          closeDialog($(this));
	        });


	        $('#showDialog2').on('click', function(){
	            $dialogWrap2.attr('aria-hidden','false');
	            $dialogWrap2.attr('aria-modal','true');
	            $dialogWrap2.attr('tabindex','0');
	            $dialogWrap2.fadeIn(200);
	            $dialog2.addClass('weui-half-screen-dialog_show');
	            setTimeout(function(){
	              $dialogWrap2.trigger('focus');
	            },200)
	        });
	        $('#showDialog3').on('click', function(){
	            $dialogWrap3.attr('aria-hidden','false');
	            $dialogWrap3.attr('aria-modal','true');
	            $dialogWrap3.attr('tabindex','0');
	            $dialogWrap3.fadeIn(200);
	            $dialog3.addClass('weui-half-screen-dialog_show');
	            setTimeout(function(){
	              $dialogWrap3.trigger('focus');
	            },200)
	        });

	        $('#js_close').on('click', function(){
	          closeDialog($(this));
	          $dialog5.css('transform','translate3d(0, 100%, 0)');
	        });


	        var js_line = document.getElementById('js_line');
	        var js_arrow = document.getElementById('js_arrow');
	        var start = 0
	        var end = 0

	         

	        const wrapBtn = document.getElementById('js_wrap_btn');
	        const wrapBtn1 = document.getElementById('js_wrap_btn_1');
	        const wrapPage = document.getElementById('js_wrap_wrp');
	        const wrapArea = document.getElementById('js_wrap_btn_area');


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
	          WeixinJSBridge.on('menu:setfont', function(res){
	            document.body.style.webkitTextSizeAdjust = res.fontScale + '%';
	            if(wrapBtn.offsetHeight > 48){
	              wrapPage.classList.add('weui-bottom-fixed-opr-page_btn-wrap');
	            }else{
	              wrapPage.classList.remove('weui-bottom-fixed-opr-page_btn-wrap');
	            }
	            if(wrapBtn1.offsetHeight > 48){
	              $dialog4.addClass('weui-half-screen-dialog_btn-wrap');
	            }else{
	              $dialog4.removeClass('weui-half-screen-dialog_btn-wrap');
	            }
	          });
	        });

	    });
	</script>
</x-wechat-layout>