<?php

namespace App\Console\Commands;

use Exception as ExceptionAlias;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearBucket extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Clear all documents in the bucket";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $bucket = env('DB_DATABASE');
        try {
            DB::delete("DELETE FROM $bucket");
            echo("$bucket has been cleared\n");
        } catch (ExceptionAlias $e) {
            echo("There was a problem clearing $bucket\n");
        }
    }
}
