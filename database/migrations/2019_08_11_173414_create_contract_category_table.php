<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->default('')->comment('分类名称');
            $table->unsignedTinyInteger('players')->default(0)->comment('参与人类型');
            $table->string('introduce')->default('')->comment('简介');
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
        Schema::dropIfExists('contract_category');
    }
}