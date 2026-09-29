<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Silber\Bouncer\Database\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Role::query()->firstOrCreate(
            ['name' => 'utilisateur'],
            ['title' => 'Utilisateur'],
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $role = Role::query()->where('name', 'utilisateur')->first();

        if (! $role) {
            return;
        }

        if (DB::table('assigned_roles')->where('role_id', $role->getKey())->exists()) {
            throw new RuntimeException('Le rôle utilisateur est encore attribué et ne peut pas être supprimé.');
        }

        $role->delete();
    }
};
