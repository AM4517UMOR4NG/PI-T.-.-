<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixImageUrlsSeeder extends Seeder
{
    public function run(): void
    {
        // Fix escaped slashes in image URLs
        DB::statement("UPDATE unit_ps SET foto = REPLACE(foto, '\\\\/', '/')");
        DB::statement("UPDATE games SET gambar = REPLACE(gambar, '\\\\/', '/')");
        DB::statement("UPDATE accessories SET gambar = REPLACE(gambar, '\\\\/', '/')");
        
        echo "Fixed escaped slashes in image URLs\n";
    }
}
