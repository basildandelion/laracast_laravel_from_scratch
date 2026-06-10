<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('steps', function (Blueprint $table) {
            $table->foreignId('idea_id')->nullable()->constrained('ideas')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('steps', function (Blueprint $table) {
            $table->dropForeign(['idea_id']);
            $table->dropColumn('idea_id');
        });
    }
};
