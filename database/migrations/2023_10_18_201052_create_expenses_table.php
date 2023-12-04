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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('section')->nullable();
            $table->foreign('section')->references('id')->on('department_expenses')->onDelete('set null');
            $table->unsignedBigInteger('sub_service')->nullable();
            $table->foreign('sub_service')->references('id')->on('department_expenses')->onDelete('set null');
            $table->text('supplier')->nullable();
            $table->text('bill_no');
            $table->date('expense_date');
            $table->text('expense_description')->nullable();
            $table->double('taxs_amount',8,3)->default(0);
            $table->double('amount',8, 3)->default(0);
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
        Schema::dropIfExists('expenses');
    }
};
