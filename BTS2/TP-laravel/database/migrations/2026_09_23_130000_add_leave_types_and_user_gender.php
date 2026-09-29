<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('sexe')->nullable()->after('name');
        });

        Schema::table('absences', function (Blueprint $table) {
            $table->string('type_conge')->nullable()->after('conges_payes');
        });
    }

    public function down(): void
    {
        Schema::table('absences', function (Blueprint $table) {
            $table->dropColumn('type_conge');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('sexe');
        });
    }
};
