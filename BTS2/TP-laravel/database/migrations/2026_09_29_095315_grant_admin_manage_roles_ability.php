<?php

use Illuminate\Database\Migrations\Migration;
use Silber\Bouncer\BouncerFacade as Bouncer;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Bouncer::allow('admin')->to('manage-roles');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Bouncer::disallow('admin')->to('manage-roles');
    }
};
