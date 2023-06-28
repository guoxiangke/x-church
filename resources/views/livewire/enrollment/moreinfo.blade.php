<div>
    @if ($errors->any())
    <div class="alert alert-danger" style="color: #fff;">
            @foreach ($errors->all() as $error)
                {{ $error }}<br/>
            @endforeach
    </div>
    @endif

    @if($eventEnroll->event->is_multi_enroll)
    <div>
        <style type="text/css">
          .d-input{
            padding: 15px;
            font-size: 16px;
            width: 20%;
          }
        </style>
        <div class="weui-cells__group weui-cells__group_form">
              <p class="page__desc">订餐提醒</p>
            <div class="weui-cells">
              <div id="showPicker1" role="button" aria-haspopup="listbox" class=" weui-cell weui-cell_active weui-cell_select weui-cell_select-after">
                <div class="weui-cell__hd"><label class="weui-label">大人</label></div>
                  <div class="weui-cell__bd">
                      <input wire:model.lazy="eventEnroll.count_adult"  name="count_adult" class="d-input" type="number" pattern="0-9" placeholder="请输入成人数量" min="1" max="5"  maxlength="2" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength); if(!this.value) this.value=1;" />
                  </div>
              </div>

              <div id="showPicker2" role="button" aria-haspopup="listbox" class=" weui-cell weui-cell_active weui-cell_select weui-cell_select-after">
                <div class="weui-cell__hd"><label class="weui-label">孩子</label></div>
                  <div class="weui-cell__bd">
                      <input wire:model.lazy="eventEnroll.count_child" name="count_child" class="d-input" type="number" pattern="[0-9]" placeholder="请输入儿童数量" min="0"  max="6"  maxlength="2"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength); if(!this.value) this.value=0;" />
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
                        <textarea id="textarea" wire:model.lazy="eventEnroll.remark" class="weui-textarea" placeholder="您的留言：如1个米饭，1个面条" rows="3"></textarea>
                        <div role="option" aria-live="polite" class="weui-textarea-counter"></div>
                        <!-- <span>0</span>/200 -->
                    </div>
                </div>
            </div>
          <br>

        <div class="weui-half-screen-dialog__ft">
          <div id="js_wrap_btn_area" class="weui-half-screen-dialog__btn-area">
            <button wire:click="submit" class="js_close weui-btn weui-btn_primary">确定</button>
          </div>
        </div>
        </div>
    </div>


</div>
