<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            // Requires the pgvector extension to be installed on the server.
            // The compose file uses the pgvector/pgvector image for this reason.
            DB::statement('CREATE EXTENSION IF NOT EXISTS vector');
        }
    }

    public function down(): void
    {
        // Extensions may be shared by other schemas; never drop here.
    }
};
