<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Silber\Bouncer\BouncerFacade as Bouncer;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $adminIds = DB::table('users')->where('is_admin', true)->pluck('id');

        Bouncer::allow('admin')->to('manage-users');
        Bouncer::allow('admin')->to('manage-all-absences');

        User::query()->whereKey($adminIds)->get()->each(function (User $user): void {
            $user->assign('admin');
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('is_admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->boolean('is_admin')->default(false)->after('password');
        });

        $adminIds = User::query()->whereIs('admin')->pluck('id');

        DB::table('users')->whereIn('id', $adminIds)->update(['is_admin' => true]);
    }
};
