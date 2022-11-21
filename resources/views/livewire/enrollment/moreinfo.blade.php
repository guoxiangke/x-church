<div>
    @if($eventEnroll->event->is_multi_enroll)
    <div>
        <div class="weui-cells__group weui-cells__group_form">
              <p class="page__desc">按成人儿童统计人数，可用于订餐等需求</p>
            <div class="weui-cells">
              <div id="showPicker1" role="button" aria-haspopup="listbox" class=" weui-cell weui-cell_active weui-cell_select weui-cell_select-after">
                <div class="weui-cell__hd"><label class="weui-label">成人</label></div>
                  <div class="weui-cell__bd">
                      <input wire:model="eventEnroll.count_adult" name="count_adult" class="showPicker weui-input  weui-cell__control weui-cell__control_flex" type="text" pattern="0-9" placeholder="请输入成人数量" value=""/>
                  </div>
              </div>

              <div id="showPicker2" role="button" aria-haspopup="listbox" class=" weui-cell weui-cell_active weui-cell_select weui-cell_select-after">
                <div class="weui-cell__hd"><label class="weui-label">儿童</label></div>
                  <div class="weui-cell__bd">
                      <input wire:model="eventEnroll.count_child" name="count_child" class="showPicker2 weui-input  weui-cell__control weui-cell__control_flex" type="text" pattern="[0-9]" placeholder="请输入儿童数量" value=""/>
                  </div>
              </div>


            </div>
          </div>
    </div>
    @endif
    <div>
        <div class="weui-half-screen-dialog__bd">
            <br>
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell weui-cell_active">
                    <div class="weui-cell__bd">
                        <textarea wire:model="eventEnroll.remark" class="weui-textarea" placeholder="您的声音至关重要" rows="3"></textarea>
                        <div role="option" aria-live="polite" class="weui-textarea-counter"><span>0</span>/200</div>
                    </div>
                </div>
            </div>
          <br>

        <div class="weui-half-screen-dialog__ft">
          <div id="js_wrap_btn_area" class="weui-half-screen-dialog__btn-area">
            <a href="javascript:" class="js_close weui-btn weui-btn_primary">确定</a>
          </div>
        </div>
        </div>
    </div>


</div>
