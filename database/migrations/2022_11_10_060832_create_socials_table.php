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
        // user HasMany socials 1:N
        // social HasOne User 1:1
        Schema::create('socials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('social_id')->index();//->comment('openid');
            $table->unsignedTinyInteger('type')->default(0); //0:wechat 1:github 2:facebook 3:psid
            $table->string('name')->nullable(); //公众号获取的微信昵称
            $table->string('avatar')->nullable();

            // add 唯一索引在 social_id + type //确保一个用户在一个平台唯一绑定
            $table->unique(['social_id', 'type']);
            // 个人weixin号，和gh绑定后，更新
            $table->string('wxid')->nullable()->index();
            $table->string('nickname')->nullable()->index()->comment('微信姓名');//微信备注里的姓名
            // 绑定手机后，可以用来登陆
            $table->string('telephone', 22)->nullable()->index()->comment('with(+1)');
            
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
        Schema::dropIfExists('socials');
    }
};
