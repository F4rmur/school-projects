<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nom')->nullable();
            $table->string('prenom')->nullable();
        });

        DB::table('users')->whereNull('nom')->update([
            'nom' => DB::raw('name'),
        ]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable();
        });

        DB::table('users')->orderBy('id')->eachById(function (object $user): void {
            $fullName = trim($user->prenom.' '.$user->nom);

            DB::table('users')
                ->where('id', $user->id)
                ->update(['name' => $fullName]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nom', 'prenom']);
        });
    }
};
