<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DropLogTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:drop-table {table : Name of the creating log table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will drop the given table';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $table = $this->argument('table');
        if (! Str::startsWith($table, 'logs')) {
            $this->info("$table is not one of the log tables and can't be deleted via this command");

            return;
        }

        Schema::dropIfExists($table);
    }
}
