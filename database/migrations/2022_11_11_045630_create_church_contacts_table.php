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
        Schema::create('church_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('church_id')->nullable()->comment('所属教会');
            $table->foreignId('user_id')->nullable()->comment('教会成员，可以为空，即没有登记为系统用户');
            $table->string('name_en')->nullable()->comment('英文名');
            $table->string('name_last')->nullable()->comment('中文姓');
            $table->string('name_first')->nullable()->comment('中文名');// 'name' = name_last + name_first
            $table->boolean('sex')->default(0);
            $table->date('birthday')->nullable();
            $table->string('telephone', 22)->index()->comment('with(+1)');
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->date('date_join')->nullable();
            $table->date('date_baptized')->nullable();
            $table->boolean('is_married')->default(0)->comment('0单身');
            $table->foreignId('reference_id')->nullable()->comment('引荐人：已登记的本教会成员');//reference_church_contact_id
            $table->string('remark')->nullable()->comment('备注');
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
        Schema::dropIfExists('church_contacts');
    }
};
