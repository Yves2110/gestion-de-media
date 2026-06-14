<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('id');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('id');
        });

        foreach (DB::table('media')->whereNull('uuid')->cursor() as $row) {
            DB::table('media')->where('id', $row->id)->update(['uuid' => (string) Str::uuid()]);
        }

        foreach (DB::table('documents')->whereNull('uuid')->cursor() as $row) {
            DB::table('documents')->where('id', $row->id)->update(['uuid' => (string) Str::uuid()]);
        }
    }

    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
