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
            $table->string('name_cn')->comment('组织名称cn')->index();
            $table->string('name_en')->comment('组织名称en')->index();
            $table->string('name_cn_abbr')->nullable()->comment('组织名称缩写cn')->index();
            $table->string('name_en_abbr')->nullable()->comment('组织名称缩写en')->index();
            $table->string('telephone', 22)->unique()->index();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('website_url')->nullable()->comment('组织网址');
            $table->string('logo_url')->nullable()->comment('组织logo网址');
            $table->date('birthday')->nullable()->comment('组织成立时间');
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
