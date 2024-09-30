<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees_vaccines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employees_id');
            $table->unsignedBigInteger('vaccines_id');
            $table->string('batch')->nullable();
            $table->date('validate_date')->nullable();
            $table->date('first_dose_vaccine')->nullable();
            $table->date('second_dose_vaccine')->nullable();
            $table->date('third_dose_vaccine')->nullable();
            $table->timestamps();

            $table->foreign('employees_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('vaccines_id')->references('id')->on('vaccines')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_vaccine');
    }
};
