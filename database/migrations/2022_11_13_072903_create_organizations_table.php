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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->comment('管理员、负责人、owner、director');
            $table->string('system_name')->comment('系统名称to show bottom')->index();
            $table->string('name')->comment('组织名称')->index();
            $table->string('name_abbr')->nullable()->comment('组织名称缩写cn');
            $table->string('name_en')->nullable()->comment('组织名称en')->index();
            $table->string('name_en_abbr')->nullable()->comment('组织名称缩写en');
            $table->string('telephone', 22)->unique()->index();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('website_url')->nullable()->comment('组织网址');
            $table->string('logo_url')->nullable()->comment('组织logo网址');
            $table->string('wechat_qr_url')->nullable()->comment('组织的AI机器人，扫码添加,如果为空，则使用AI机器人的');
            $table->string('wechat_ai_title')->nullable()->comment('管理员Title：牧师、教务长..');
            $table->string('wechat_ai_token')->nullable()->comment('token，用来发微信消息的');
            $table->timestamp('birthday')->nullable()->comment('组织成立时间');
            $table->text('introduce')->nullable()->comment('组织简介');
            $table->string('contact_fields')->nullable()->comment('额外属性字段，需要收集的，英文;分割');
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
        Schema::dropIfExists('organizations');
    }
};
