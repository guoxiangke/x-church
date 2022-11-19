@section('title', $event->name.'|'.$organization->system_name)

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
		                <li role="option" class="weui-form-preview__item"><label class="weui-form-preview__label">时间</label><p class="weui-form-preview__value">{{$event->begin_at->format("D M j H:i")}} ～ {{$event->begin_at->addHours($event->duration_hours)->format("D M j H:i")}}</p></li>
		              </ul>
		              <br><br>
		            </div>
		            @endif

			        @if(!$isBind)
			        <div class="weui-msg__tips-area">
			          <p class="weui-msg__tips">及时获取该Event动态</p>
			        </div>
			        <div class="weui-msg__opr-area">
			            <p class="weui-btn-area">
			                <a href="{{$organization->wechat_qr_url?:'https://www.yilindeli.com/assets/WechatIMG551.jpeg'}}" role="button" class="weui-btn weui-btn_primary">{{$organization->wechat_ai_title??'AI助理'}}微信</a>
			            </p>
			        </div>
			        <div class="weui-msg__tips-area">
			          <p class="weui-msg__tips">请微信发送 <span style="font-size: 24px">{{$code6}}</span>  ⬆️给{{$organization->wechat_ai_title??'AI助理'}}<br/>此验证码60s内有效！</p>
			        </div>
			        @endif

			        @if(isset($enrollId))
			        <div class="weui-msg__opr-area">
			            <p class="weui-btn-area">
			                <a href="http://maps.google.com/maps?q={{$event->address}}" role="button" class="weui-btn weui-btn_primary">导航🧭</a>
			            </p>

				            	<br/>
				                <span class="weui-cells__tips showIOSDialog" id="showDialog3" ><a class="weui-link weui-wa-hotarea" href="javascript:">取消报名</a></span>
				                <span class="weui-cells__tips showIOSDialog" id="showDialog4" ><a class="weui-link weui-wa-hotarea" href="javascript:">携带家眷</a></span>
				                <span class="weui-cells__tips showIOSDialog" id="showDialog2" ><a class="weui-link weui-wa-hotarea" href="javascript:">报名附言</a></span>
			        </div>
			        @endif

		            @if(!in_array($status,[3,7,0]))
		            <div class="weui-msg__custom-area">
		              <ul class="weui-form-preview__list">
		                <li role="option" class="weui-form-preview__item"><label class="weui-form-preview__label">活动时间</label><p class="weui-form-preview__value">{{$event->begin_at->format("M j H:i")}} ～ {{$event->begin_at->addHours($event->duration_hours)->format("M j H:i")}}（{{$event->duration_hours}}小时）</p></li>
		                <li role="option" class="weui-form-preview__item"><label class="weui-form-preview__label">签到时间</label><p class="weui-form-preview__value">开始前{{$event->check_in_ahead}}分钟</p></li>
		                <li role="option" class="weui-form-preview__item"><label class="weui-form-preview__label">当前时间</label><p class="weui-form-preview__value">{{now()->format("M j H:i Y D")}}</p></li>
		              </ul>
		            </div>
		            @endif

		        </div>

		        <div class="weui-msg__extra-area">
		            <div class="weui-footer">
		                <p class="weui-footer__links">
		                    <a href="javascript:" class="weui-wa-hotarea weui-footer__link">{{$organization->system_name??$organization->name_abbr}}活动签到管理系统</a>
		                </p>
		                <p class="weui-footer__text">Copyright &copy; 2008-2016 yilindeli.com</p>
		            </div>
		        </div>
		    </div>

		    <div id="dialogs">
		      <!--BEGIN dialog1-->

		        <div id="dialogWrap2" class="js_dialog_wrap" ref="showDialog2" aria-label="弹窗标题" role="dialog" aria-modal="false" aria-hidden="true" style="display: none;">
		            <div aria-label="关闭" role="button" class="js_close weui-mask"></div>
		            <div id="js_dialog_2" class="js_dialog weui-half-screen-dialog">
		                <div class="weui-half-screen-dialog__hd">
		                  <div class="weui-half-screen-dialog__hd__main">
		                    <div class="weui-flex" style="align-items: center; font-size: 14px;">
		                      <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC4AAAAuCAMAAABgZ9sFAAAAVFBMVEXx8fHMzMzr6+vn5+fv7+/t7e3d3d2+vr7W1tbHx8eysrKdnZ3p6enk5OTR0dG7u7u3t7ejo6PY2Njh4eHf39/T09PExMSvr6+goKCqqqqnp6e4uLgcLY/OAAAAnklEQVRIx+3RSRLDIAxE0QYhAbGZPNu5/z0zrXHiqiz5W72FqhqtVuuXAl3iOV7iPV/iSsAqZa9BS7YOmMXnNNX4TWGxRMn3R6SxRNgy0bzXOW8EBO8SAClsPdB3psqlvG+Lw7ONXg/pTld52BjgSSkA3PV2OOemjIDcZQWgVvONw60q7sIpR38EnHPSMDQ4MjDjLPozhAkGrVbr/z0ANjAF4AcbXmYAAAAASUVORK5CYII=" alt="" style="width: 24px; margin-right: 8px; border-radius: 50%; display: block;">
		                      昵称2
		                    </div>
		                  </div>
		                </div>
		                <div class="weui-half-screen-dialog__bd">
		                  	<br>
					        <div class="weui-cells weui-cells_form">
					            <div class="weui-cell weui-cell_active">
					                <div class="weui-cell__bd">
					                    <textarea class="weui-textarea" placeholder="您的声音至关重要" rows="3"></textarea>
					                    <div role="option" aria-live="polite" class="weui-textarea-counter"><span>0</span>/200</div>
					                </div>
					            </div>
					        </div>
		                  <br>
		                </div>
		                <div class="weui-half-screen-dialog__ft">
		                  <div id="js_wrap_btn_area" class="weui-half-screen-dialog__btn-area">
		                    <a id="js_wrap_btn_1" href="javascript:" class="js_close weui-btn weui-btn_default">取消</a>
		                    <a href="javascript:" class="js_close weui-btn weui-btn_primary">确定</a>
		                  </div>
		                </div>
		            </div>
		        </div>

		        <div id="dialogWrap3" class="js_dialog_wrap" ref="showDialog4" aria-label="弹窗标题" role="dialog" aria-modal="false" aria-hidden="true" style="display: none;">
		            <div aria-label="关闭" role="button" class="js_close weui-mask"></div>
		            <div id="js_dialog_4" class="js_dialog weui-half-screen-dialog">
		                <div class="weui-half-screen-dialog__hd">
		                  <div class="weui-half-screen-dialog__hd__main">
		                    <div class="weui-flex" style="align-items: center; font-size: 14px;">
		                      <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC4AAAAuCAMAAABgZ9sFAAAAVFBMVEXx8fHMzMzr6+vn5+fv7+/t7e3d3d2+vr7W1tbHx8eysrKdnZ3p6enk5OTR0dG7u7u3t7ejo6PY2Njh4eHf39/T09PExMSvr6+goKCqqqqnp6e4uLgcLY/OAAAAnklEQVRIx+3RSRLDIAxE0QYhAbGZPNu5/z0zrXHiqiz5W72FqhqtVuuXAl3iOV7iPV/iSsAqZa9BS7YOmMXnNNX4TWGxRMn3R6SxRNgy0bzXOW8EBO8SAClsPdB3psqlvG+Lw7ONXg/pTld52BjgSSkA3PV2OOemjIDcZQWgVvONw60q7sIpR38EnHPSMDQ4MjDjLPozhAkGrVbr/z0ANjAF4AcbXmYAAAAASUVORK5CYII=" alt="" style="width: 24px; margin-right: 8px; border-radius: 50%; display: block;">
		                      昵称
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
		                    <a id="js_wrap_btn_1" href="javascript:" class="js_close weui-btn weui-btn_default">不要取消</a>
		                    <a href="javascript:" class="js_close weui-btn weui-btn_primary">确定取消</a>
		                  </div>
		                </div>
		            </div>
		        </div>

		        <div id="dialogWrap4" class="js_dialog_wrap" ref="showDialog4" aria-label="弹窗标题" role="dialog" aria-modal="false" aria-hidden="true" style="display: none;">
		            <div aria-label="关闭" role="button" class="js_close weui-mask"></div>
		            <div id="js_dialog_3" class="js_dialog weui-half-screen-dialog">
		                <div class="weui-half-screen-dialog__hd">
		                  <div class="weui-half-screen-dialog__hd__main">
		                    <div class="weui-flex" style="align-items: center; font-size: 14px;">
		                      <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC4AAAAuCAMAAABgZ9sFAAAAVFBMVEXx8fHMzMzr6+vn5+fv7+/t7e3d3d2+vr7W1tbHx8eysrKdnZ3p6enk5OTR0dG7u7u3t7ejo6PY2Njh4eHf39/T09PExMSvr6+goKCqqqqnp6e4uLgcLY/OAAAAnklEQVRIx+3RSRLDIAxE0QYhAbGZPNu5/z0zrXHiqiz5W72FqhqtVuuXAl3iOV7iPV/iSsAqZa9BS7YOmMXnNNX4TWGxRMn3R6SxRNgy0bzXOW8EBO8SAClsPdB3psqlvG+Lw7ONXg/pTld52BjgSSkA3PV2OOemjIDcZQWgVvONw60q7sIpR38EnHPSMDQ4MjDjLPozhAkGrVbr/z0ANjAF4AcbXmYAAAAASUVORK5CYII=" alt="" style="width: 24px; margin-right: 8px; border-radius: 50%; display: block;">
		                      统计 吃饭人数，携带家眷？可以帮助家人报名。
		                    </div>
		                  </div>
		                </div>
		                <div class="weui-half-screen-dialog__bd">
		                  	<br>
							<div class="weui-cells__group weui-cells__group_form">
						        <div class="weui-cells">
						          
						          <div id="showPicker1" role="button" aria-haspopup="listbox" class=" weui-cell weui-cell_active weui-cell_select weui-cell_select-after">
						            <div class="weui-cell__hd"><label class="weui-label">成人</label></div>
						              <div class="weui-cell__bd">
						                  <input name="count_adult" class="showPicker weui-input  weui-cell__control weui-cell__control_flex" type="text" pattern="0-9" placeholder="请输入成人数量" value=""/>
						              </div>
						          </div>

						          <div id="showPicker2" role="button" aria-haspopup="listbox" class=" weui-cell weui-cell_active weui-cell_select weui-cell_select-after">
						            <div class="weui-cell__hd"><label class="weui-label">儿童</label></div>
						              <div class="weui-cell__bd">
						                  <input name="count_child" class="showPicker2 weui-input  weui-cell__control weui-cell__control_flex" type="text" pattern="[0-9]" placeholder="请输入儿童数量" value=""/>
						              </div>
						          </div>


						        </div>
						      </div>
       						<br>
		                </div>
		                <div class="weui-half-screen-dialog__ft">
		                  <div id="js_wrap_btn_area" class="weui-half-screen-dialog__btn-area">
		                    <a id="js_wrap_btn_1" href="javascript:" class="js_close weui-btn weui-btn_default">取消</a>
		                    <a href="javascript:" class="js_close weui-btn weui-btn_primary">确定</a>
		                  </div>
		                </div>
		            </div>
		        </div>

			</div>

		</div>
	<script type="text/javascript">
		 $(function(){
	        const $dialog2 = $('#js_dialog_2');
	        const $dialog3 = $('#js_dialog_3');
	        const $dialog4 = $('#js_dialog_4');
	        const $dialogWrap2 = $('#dialogWrap2');
	        const $dialogWrap3 = $('#dialogWrap3');
	        const $dialogWrap4 = $('#dialogWrap4');

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
	        $('#showDialog4').on('click', function(){
	            $dialogWrap4.attr('aria-hidden','false');
	            $dialogWrap4.attr('aria-modal','true');
	            $dialogWrap4.attr('tabindex','0');
	            $dialogWrap4.fadeIn(200);
	            $dialog4.addClass('weui-half-screen-dialog_show');
	            wrapArea.style.visibility = 'hidden';
	            setTimeout(function(){
	              if(wrapBtn1.offsetHeight > 48){
	                $dialog4.addClass('weui-half-screen-dialog_btn-wrap');
	              }
	              wrapArea.style.visibility = 'visible';
	            },100);
	            setTimeout(function(){
	              $dialogWrap4.trigger('focus');
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