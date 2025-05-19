<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearTables extends Command
{
    protected $signature = 'tables:clear';
    protected $description = 'Clear specific tables in the database';

    public function handle()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Clear tables
        DB::table('products')->truncate();
        DB::table('categories')->truncate();
        DB::table('brands')->truncate();

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->info('Tables cleared successfully!');
    }
} 