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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->string('title');
            $table->text('description');
            $table->text('address');
            $table->text('email');
            $table->text('website');
            $table->text('phone');
            $table->string('company');
            $table->string('category');
            $table->string('country');
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->decimal('salary', 10, 2);
            $table->string('type');
            $table->date('deadline');
            $table->enum('job_status', ['published', 'draft'])->default('draft');
            $table->dateTime('published_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
