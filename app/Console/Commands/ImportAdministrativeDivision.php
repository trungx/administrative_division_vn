<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AdministrativeUnitImport as AUI;
use App\Models\AdministrativeUnit as AU;
use App\Models\Unit;

class ImportAdministrativeDivision extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:administrative';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import administrative';

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
        //handle here
        try {
            Excel::import(new AUI, public_path('danh_sach_cap_tinh_kem_theo_quan_huyen_phuong_xa_27_02_2021.xls'));
        } catch(Exception $e) {
            $this->error($e);
        }
        $this->info('Import Administrative Division Successfully..!');
    }
}
