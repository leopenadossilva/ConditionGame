<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_tests_run_on_postgres(): void
    {
        $this->assertSame('pgsql', DB::connection()->getDriverName());
    }

    public function test_pgvector_extension_is_available(): void
    {
        $extension = DB::selectOne("SELECT extversion FROM pg_extension WHERE extname = 'vector'");

        $this->assertNotNull($extension, 'pgvector must be enabled; tests should not pass on plain Postgres.');
    }
}
