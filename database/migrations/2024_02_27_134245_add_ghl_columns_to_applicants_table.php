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
        Schema::table('applicants', function (Blueprint $table) {
            //
            $table->string('ghl_id')->nullable()->after('id');
            $table->text('ghl_post_data')->nullable()->after('ghl_id');
            $table->text('ghl_response')->nullable()->after('ghl_post_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            //
            $table->dropColumn('ghl_id');
            $table->dropColumn('ghl_post_data');
            $table->dropColumn('ghl_response');
        });
    }
};
