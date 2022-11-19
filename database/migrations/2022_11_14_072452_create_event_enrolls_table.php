<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// EventEnroll::truncate()
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_enrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('event_id');
            // 同时，也可以找出 所有 该 service 的 所有会员。以供double-check0in
            $table->foreignId('service_id')->nullable()->comment('如果一个event属于services的话');
            $table->date('enrolled_at')->comment('报名时间');
            $table->date('double_checked_at')->nullable()->comment('确认时间');
            $table->date('checked_in_at')->nullable()->comment('签入时间');
            $table->date('checked_out_at')->nullable()->comment('签出时间');
            // Event开始后，不可以取消报名
            // Event开始前X小时，才可以取消报名
            $table->date('canceled_at')->nullable()->comment('取消报名需要提前的时间');
            $table->string('remark')->nullable()->comment('留言备注');
            // 携带家眷 $event->is_multi_enroll
            $table->unsignedTinyInteger('count_adult')->default(1)->comment('几个成人');
            $table->unsignedTinyInteger('count_child')->default(0)->comment('几个儿童');
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
        Schema::dropIfExists('event_enrolls');
    }
};
