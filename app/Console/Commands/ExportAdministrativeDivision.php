<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\UnitJob;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;
use App\Models\AdministrativeUnit as AU;
use Illuminate\Support\Str;

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
     * Check string in a string
     */
    private function str_contains($string, $search) {
        if (strpos(mb_strtolower($string, 'UTF-8'), $search) !== false) {
            return true;
        }
        return false;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('start dump!');


        //import province
        $provinces = AU::select('l1_name', 'l1_code')->groupBy('l1_name','l1_code')->get();

        //import district
        foreach($provinces as $province) {
            //if($province->l1_name != 'Thành phố Hải Phòng') continue;
            dump($province->l1_name);
            if ($this->str_contains($province->l1_name, 'tỉnh')) {
                $tinh['type'] = 'tinh';
                $tinh['name'] = str_ireplace('Tỉnh ','', $province->l1_name);
                $tinh['name_with_type'] = "Tỉnh " . $tinh['name'];
            }
            if ($this->str_contains($province->l1_name, 'thành phố')) {
                $tinh['type'] = 'thanh-pho';
                $tinh['name'] = str_ireplace('Thành phố ','', $province->l1_name);
                $tinh['name_with_type'] = "Thành phố " . $tinh['name'];
            }
            $tinh['level'] = 1;
            $tinh['slug'] = Str::slug($tinh['name']);
            $tinh['code'] = $province->l1_code;
            UnitJob::dispatch($tinh); //Dispatch job here

            $districts = AU::cursor()->filter(function($district) use ($province){
                return $district->l1_name == $province->l1_name;
            });

            if ($districts->count() > 1) {
                //$districts->chunk(200)->each(function($districts) use ($tinh, $province){
                    // In a chunk
                    $districts->each(function($district) use ($tinh, $province){
                        dump($district->l2_name);
                        if ($this->str_contains($district->l2_name, 'huyện')) {
                            $huyen['type'] = 'huyen';
                            $huyen['name'] = str_ireplace('Huyện ','', $district->l2_name);
                            $huyen['name_with_type'] = "Huyện " . $huyen['name'];
                        }
                        if ($this->str_contains($district->l2_name, 'quận')) {
                            $huyen['type'] = 'quan';
                            $huyen['name'] = str_ireplace('Quận ','', $district->l2_name);
                            $huyen['name_with_type'] = "Quận " . $huyen['name'];
                        }
                        if ($this->str_contains($district->l2_name, 'thị xã')) {
                            $huyen['type'] = 'thi-xa';
                            $huyen['name'] = str_ireplace('Thị xã ','', $district->l2_name);
                            $huyen['name_with_type'] = "Thị xã " . $huyen['name'];
                        }
                        if ($this->str_contains($district->l2_name, 'thành phố')) {
                            $huyen['type'] = 'thanh-pho';
                            $huyen['name'] = str_ireplace('Thành phố ','', $district->l2_name);
                            $huyen['name_with_type'] = "Thành phố " . $huyen['name'];
                        }
                        $huyen['path'] = $huyen['name'] . ', ' . $tinh['name'];
                        $huyen['slug'] = Str::slug($huyen['name']);
                        $huyen['path_with_type'] = $district->l2_name . ', ' . $province->l1_name;
                        $huyen['code'] = $district->l2_code;
                        $huyen['parent_code'] = $province->l1_code;
                        $huyen['level'] = 2;
                        UnitJob::dispatch($huyen); //Dispatch job here

                        $wards = AU::cursor()->filter(function($ward) use ($district){
                            return $ward->l2_name == $district->l2_name;
                        });
                        // Some districts have no wards
                        if ($wards->count() > 1) {
                            //$wards->chunk(200)->each(function($wards) use ($tinh, $huyen, $province, $district){
                                // in a chunk
                                $wards->each(function($ward) use ($tinh, $huyen, $province, $district){
                                    dump($ward->l3_name);
                                    if ($this->str_contains($ward->l3_name, 'xã')) {
                                        $xa['type'] = 'xa';
                                        $xa['name'] = str_ireplace('Xã ','', $ward->l3_name);
                                        $xa['name_with_type'] = "Xã " . $xa['name'];
                                    }
                                    if ($this->str_contains($ward->l3_name, 'phường')) {
                                        $xa['type'] = 'phuong';
                                        $xa['name'] = str_ireplace('Phường ','', $ward->l3_name);
                                        $xa['name_with_type'] = "Phường " . $xa['name'];
                                    }
                                    if ($this->str_contains($ward->l3_name, 'thị trấn')) {
                                        $xa['type'] = 'thi-tran';
                                        $xa['name'] = str_ireplace('Thị trấn ','', $ward->l3_name);
                                        $xa['name_with_type'] = "Thị trấn " . $xa['name'];
                                    }

                                    $xa['path'] = $xa['name'] . ', ' . $huyen['name'] . ', ' . $tinh['name'];
                                    $xa['slug'] = Str::slug($xa['name']);
                                    $xa['path_with_type'] = $ward->l3_name . ', ' . $district->l2_name . ', ' . $province->l1_name;
                                    $xa['code'] = $ward->l3_code;
                                    $xa['parent_code'] = $district->l2_code;
                                    $xa['level'] = 3;
                                    UnitJob::dispatch($xa); //Dispatch job here
                                });
                            //});
                        } //end ward
                    });
                //});
            } // end district
        }

        $this->info('end dump!');
    }
}
