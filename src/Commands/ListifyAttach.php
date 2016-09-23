<?php

namespace Trungtnm\Backend\Commands;

use Lookitsatravis\Listify\Commands\AttachCommand;

class ListifyAttach extends AttachCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "trungtnm:listifyAttach {table} {column=position}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a migration and run it immediately for adding position column used by Listify to a database table.';

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
     * @return mixed
     */
    public function handle()
    {
        parent::fire();
        //do the migration
        \Artisan::call('migrate');
        // Dump the migration message to the console.
        $this->info("Successfully run artisan:migrate to add new column into specific table");
    }
}
