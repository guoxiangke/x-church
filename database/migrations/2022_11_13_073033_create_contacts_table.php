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
        // == profiles
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->comment('所属组织');
            // '可以为空，即没有登记为系统用户'
            //TODO 如何确定 contact 和 user 的关系？用手机号sms验证？
            $table->foreignId('user_id')->nullable()->comment();
            $table->string('name_last')->nullable()->comment('姓');
            $table->string('name_first')->nullable()->comment('名');// 'name' = name_last + name_first
            $table->string('name_en')->nullable()->comment('英文名');
            $table->boolean('sex')->default(0);
            $table->date('birthday')->nullable();
            //TODO 这里的手机号，用来确定 和 user 的关系，但需要sms验证
            $table->string('telephone', 22)->index()->comment('with(+1)');
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->date('date_join')->nullable();
            
            $table->foreignId('reference_id')->nullable()->comment('引荐人：已登记的本组织成员');//reference_church_contact_id
            $table->string('remark')->nullable()->comment('备注');
            $table->boolean('status')->default(1)->comment('会员状态：active，inactive');
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
        Schema::dropIfExists('contacts');
    }
};
