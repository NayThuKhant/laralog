<?php

namespace App\Console\Commands;

use App\Enums\LogStatusEnum;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:create-table {table : Name of the creating log table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will create a log table using the given table name';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $table = $this->argument('table');

        Schema::create($table, function (Blueprint $table) {
            $table->id();
            $table->foreignId('log_level_id')->constrained()->onDelete('cascade');
            $table->string('status')->default(LogStatusEnum::UNRESOLVED->value);
            $table->longText('message');
            $table->json('context')->nullable();
            $table->timestamps();
        });
    }
}
