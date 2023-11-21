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
        Schema::create('money_resources', function (Blueprint $table) {
            $table->id();
            $table->double('value',8,3);
            $table->enum('type',['balance','bank_withdraw','outgoing_resource','sales','advances'])->default('balance');
            $table->date('resource_date');
            $table->integer('reference_id')->nullable();
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
        Schema::dropIfExists('money_resources');
    }
};
