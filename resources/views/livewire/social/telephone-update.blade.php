<div class="weui-msg__custom-area">
  <div class="weui-cells__group weui-cells__group_form">
    <div class="weui-cells__title" style="text-align: center;" >您的联系信息 (更新后，请在小键盘上点击 完成)</div>
    <div class="weui-cells" style="padding-left: 16px;">

      @if($event->is_need_name)
      <label for="js_input2" class="weui-cell weui-cell_active">
        <div class="weui-cell__hd"><span class="weui-label">您的姓名</span></div>
        <div class="weui-cell__bd weui-flex">
            <input id="js_input2" class="weui-input" value="{{$social->nickname}}" type="text" placeholder="点此输入" 
            wire:model.lazy="social.nickname"
            />
        </div>
      </label>
      @endif

      @if($event->is_need_telephone)
      <label for="js_input1" class="weui-cell weui-cell_active" id="js_cell">
        <div class="weui-cell__hd"><span class="weui-label">您的电话</span></div>
        <div class="weui-cell__bd weui-flex">
            <input id="js_input1" class="weui-input" type="text" pattern="[0-9]*" maxlength="11" 
            wire:model.lazy="social.telephone"
            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
            style="font-size:20px; letter-spacing: 0.2em;"
            />
        </div>
      </label>
      @endif
    </div>
  </div>
</div>