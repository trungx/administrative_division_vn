<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExportAdministrativeDivision extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:administative';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export administative';

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
     * @return int
     */
    public function handle()
    {
        $this->info('Export Administrative Division Successfully..!');
    }
}
