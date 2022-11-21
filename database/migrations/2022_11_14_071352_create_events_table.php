<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // check-in Events! & services
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->comment('所属教会｜组织');
            $table->foreignId('service_id')->nullable()->comment('null：自定义Event');
            $table->string('name')->comment('活動名字orYouthServiceWeekly52');
            $table->text('description')->nullable()->comment('活動描述');
            $table->string('live_url')->nullable()->comment('活動直播链接');
            $table->timestamp('begin_at')->comment('活動开始时间');
            $table->unsignedSmallInteger('check_in_ahead')->default(180)->comment('提前几分钟开始check-in？');//65535
            $table->unsignedSmallInteger('duration_hours')->nullable()->comment('活動持续时间，为了checked_in截止时间');//255 持续3天的活动？
            $table->string('address')->nullable()->comment('活動地点');
            $table->string('rrule')->nullable()->comment('一次性活动？重复性活动');
            $table->boolean('is_need_check_out')->nullable()->comment('是否需要checkout：儿童service');
            $table->unsignedTinyInteger('cancel_ahead_hours')->nullable()->comment('提前几小时，null关闭功能');
            // 统计 吃饭人数，携带家眷？可以帮助家人报名
            $table->boolean('is_multi_enroll')->nullable()->comment('成人+儿童参，一人报名多人，null关闭功能');
            $table->boolean('is_need_remark')->nullable()->comment('报名时是否收集留言，null关闭功能');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
};
