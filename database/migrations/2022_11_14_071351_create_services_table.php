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
        // 根据上课计划创建classRecords
        // 根据 service计划 创建当天的 event
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->comment('所属组织');
            $table->string('name')->comment('名字');
            $table->text('description')->nullable()->comment('描述');
            $table->string('live_url')->nullable()->comment('直播链接');
            $table->date('begin_at')->comment('开始时间');
            $table->unsignedSmallInteger('check_in_ahead')->default(180)->comment('提前几分钟开始check-in？');
            $table->unsignedTinyInteger('duration_hours')->nullable()->comment('持续时间，为了checked_in截止时间');// 持续3天的活动？
            $table->string('address')->nullable()->comment('地点');
            $table->string('rrule')->nullable()->comment('一次性活动？重复性活动');
            $table->boolean('is_need_check_out')->nullable()->comment('是否需要checkout：儿童service');
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
        Schema::dropIfExists('services');
    }
};
