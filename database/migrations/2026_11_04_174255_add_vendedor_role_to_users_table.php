<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check');
            DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role::text = ANY (ARRAY['admin'::text, 'socia'::text, 'operador'::text, 'vendedor'::text]))");
        } else {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'socia', 'operador', 'vendedor') DEFAULT 'operador'");
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check');
            DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role::text = ANY (ARRAY['admin'::text, 'socia'::text, 'operador'::text]))");
        } else {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'socia', 'operador') DEFAULT 'operador'");
        }
    }
};
