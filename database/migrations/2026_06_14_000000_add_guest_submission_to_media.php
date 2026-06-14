<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->boolean('is_guest_submission')->default(false)->after('statut');
            $table->string('submitter_name')->nullable()->after('is_guest_submission');
            $table->string('submitter_email')->nullable()->after('submitter_name');
        });
    }

    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn(['is_guest_submission', 'submitter_name', 'submitter_email']);
        });
    }
};
