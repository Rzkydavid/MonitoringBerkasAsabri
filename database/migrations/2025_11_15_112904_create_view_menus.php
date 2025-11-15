<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
        CREATE VIEW view_menus AS
        SELECT 
            m.id,
            m.name,
            m.route,
            m.parent_id,
            p.name AS parent_name,
            m.`order`,
            m.created_at,
            m.updated_at
        FROM menus m
        LEFT JOIN menus p ON m.parent_id = p.id
    ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS view_menus");
    }
};
