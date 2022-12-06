<div class="weui-form__control-area">
  <div class="weui-cells__group weui-cells__group_form">
    <div class="weui-cells__title">联系电话</div>
    <div class="" style="border: none;">
     <label for="js_input3" class="">
        <div class="weui-cell__bd">
            <input id="js_input3" class="weui-input" placeholder="点此输入， 不带—和（）" type="number" pattern="[0-9]*" 
            maxlength="10"
            wire:model.defer="social.telephone"
            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
            style="padding: 24px; font-size:18px; letter-spacing: 0.5em; text-align: center;" 
            />
        </div>
      </label>
      </div>
  </div>
</div>