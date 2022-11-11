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
            $table->foreignId('church_id')->comment('所属教会');
            $table->foreignId('service_id')->nullable()->comment('null：自定义Event');
            $table->string('name')->comment('活動名字orYouthServiceWeekly52');
            $table->text('description')->nullable()->comment('活動描述');
            $table->string('url')->nullable()->comment('活動直播链接');
            $table->date('begin_at')->comment('活動开始时间');
            $table->date('duration_hours')->nullable()->comment('活動持续时间，为了check_in截止时间');// 持续3天的活动？
            $table->string('address')->nullable()->comment('活動地点');
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
        Schema::dropIfExists('events');
    }
};
