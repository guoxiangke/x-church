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
            $table->string('name')->nullable()->index()->comment('姓名');
            $table->string('name_en')->nullable()->index()->comment('英文名');
            $table->boolean('sex')->default(0);
            $table->timestamp('birthday')->nullable();
            // 用户/管理员 输入的手机号和邮箱，信息不可靠！
            $table->string('telephone', 22)->nullable()->index()->comment('with(+1)');
            $table->string('email')->nullable()->index();

            $table->string('address')->nullable();
            $table->timestamp('date_join')->nullable();
            
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
