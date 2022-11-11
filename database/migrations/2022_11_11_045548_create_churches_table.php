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
        Schema::create('churches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->comment('管理员owner');
            $table->string('name')->comment('教会名称')->index();
            $table->string('name_abbr')->nullable()->comment('教会名称缩写')->index();
            $table->string('name_pastor')->nullable()->comment('主任牧师姓名')->index();
            $table->string('telephone', 22)->unique()->index();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('website_url')->nullable()->comment('教会网址');
            $table->string('logo_url')->nullable()->comment('教会logo网址');
            $table->string('live_url')->nullable()->comment('直播链接');//直播时间
            //TODO 一个教会会有多个直播，多个重复事件
            // $table->string('live_rrule')->nullable()->comment('直播时间');
            $table->date('birthday')->nullable()->comment('教会成立时间');
            $table->text('introduce')->nullable()->comment('教会简介');
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
        Schema::dropIfExists('churches');
    }
};
