
<x-wechat-layout>
	
	<div class="page">
  <div class="weui-form">
    <div class="weui-form__text-area">
      <h2 class="weui-form__title">请选择人数</h2>
      <div class="weui-form__desc">统计 吃饭人数，携带家眷？可以帮助家人报名。</div>
    </div>
    <div class="weui-form__control-area">
      <div class="weui-cells__group weui-cells__group_form">
        <div class="weui-cells">
          
          <div id="showPicker1" role="button" aria-haspopup="listbox" class=" weui-cell weui-cell_active weui-cell_select weui-cell_select-after">
            <div class="weui-cell__hd"><label class="weui-label">成人</label></div>
              <div class="weui-cell__bd">
                  <input name="count_adult" class="showPicker weui-input  weui-cell__control weui-cell__control_flex" type="number" pattern="0-9" placeholder="请输入成人数量" value=""/>
              </div>
          </div>

          <div id="showPicker2" role="button" aria-haspopup="listbox" class=" weui-cell weui-cell_active weui-cell_select weui-cell_select-after">
            <div class="weui-cell__hd"><label class="weui-label">儿童</label></div>
              <div class="weui-cell__bd">
                  <input name="count_child" class="showPicker2 weui-input  weui-cell__control weui-cell__control_flex" type="number" pattern="[0-9]" placeholder="请输入儿童数量" value=""/>
              </div>
          </div>


        </div>
      </div>
      <br>
    <div class="weui-form__opr-area">
      <button class="weui-btn weui-btn_primary" type="button" id="showTooltips">确定</button>
    </div>
    
    </div>
  </div>
</div>
<script type="text/javascript">
    $('.showPicker3').on('click', function () {
        weui.picker([{
            label: '0',
            value: 0
        }, {
            label: '1',
            value: 1
        }, {
            label: '2',
            value: 2
        },{
            label: '3',
            value: 3
        }, {
            label: '4',
            value: 4
        }, {
            label: '5',
            value: 5
        }], {
            onChange: function (result) {
                console.log(result[0].value);
                $('input[name="count_adult"]').val(result[0]).attr('value',result[0].value);
            },
            onConfirm: function (result) {
                $('input[name="count_adult"]').val(result[0]).attr('value',result[0].value);
            },
            title: '请选择数量'
        });
    });
    $('.showPicker4').on('click', function () {
        weui.picker([{
            label: '0',
            value: 0
        }, {
            label: '1',
            value: 1
        }, {
            label: '2',
            value: 2
        },{
            label: '3',
            value: 3
        }, {
            label: '4',
            value: 4
        }, {
            label: '5',
            value: 5
        }], {
            onChange: function (result) {
                console.log(result[0].value);
                $('input[name="count_child"]').val(result[0]).attr('value',result[0].value);
            },
            onConfirm: function (result) {
                $('input[name="count_child"]').val(result[0]).attr('value',result[0].value);
            },
            title: '请选择数量'
        });
    });
</script>
</x-wechat-layout>