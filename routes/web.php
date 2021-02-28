<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Imports\AdministrativeUnitImport as AUI;
use App\Models\AdministrativeUnit as AU;
use App\Models\Unit;
// use Illuminate\Support\Facades\Str;
Route::get('/up', function() {
    AU::where('l2_code', '318')->get()->dd();
    Unit::create([
        'name' => 'xx',
        'name_with_type'=> 'aa',
        'code' => 'aa',
        'parent_code' => NULL,
        'slug' => 'xxa',
        'type' => 'tinh',
        'path' => NULL,
        'path_with_type' => NULL,
    ]);
    dd('sucess');
});
Route::get('/', function () {
    return view('welcome');
});

Route::get('/db', function(){
    $abc = Excel::import(new AUI, public_path('danh_sach_cap_tinh_kem_theo_quan_huyen_phuong_xa_27_02_2021.xls'));
    dd('import sucessfully!!');

});

Route::get('/xx', function(){
    $a = [];
    $b = array_shift($a);
    dump($a);
    dd($b);
});
Route::get('/getdb', function() {

    try{
        DB::beginTransaction();
        //import province
        $provinces = AU::select('l1_name', 'l1_code')->groupBy('l1_name','l1_code')->get();

        //import district
        foreach($provinces as $province) {

            if (strpos($province->l1_name, 'Tỉnh') !== false) {
                $type1 = 'tinh';
                $name1 = str_replace('Tỉnh ','', $province->l1_name);
                $name_with_type1 = "Tỉnh " . $name1;
            }
            if (strpos($province->l1_name, 'Thành phố') !== false) {
                $type1 = 'thanh-pho';
                $name1 = str_replace('Thành phố ','', $province->l1_name);
                $name_with_type1 = "Thành phố " . $name1;
            }
            $slug1 = Str::slug($name1);

            $unit = new Unit;
            $unit->name = $name1;
            $unit->slug = $slug1;
            $unit->type = $type1;
            $unit->name_with_type = $name_with_type1;
            $unit->code = $province->l1_code;
            $unit->save();
            //dump($unit);
            //continue;

            $districts = AU::cursor()->filter(function($district) use ($province){
                return $district->l1_name == $province->l1_name;
            });
            //dd($districts);
            //$districts = AU::select('l2_name', 'l2_code')->where('l1_name', $province->l1_name)->get();
            //import ward
            $districts->chunk(200)->each(function($districts) use ($name1, $province){

                $districts->each(function($district) use ($name1, $province){
                    $name2 = '';
                    $type2 = '';
                    $name_with_type2 = '';
                    if (strpos($district->l2_name, 'Huyện') !== false) {
                        $type2 = 'huyen';
                        $name2 = str_replace('Huyện ','', $district->l2_name);
                        $name_with_type2 = "Huyện " . $name2;
                    }
                    if (strpos($district->l2_name, 'Quận') !== false) {
                        $type2 = 'quan';
                        $name2 = str_replace('Quận ','', $district->l2_name);
                        $name_with_type2 = "Quận " . $name2;
                    }
                    $path2 = $name2 . ', ' . $name1;
                    $slug2 = Str::slug($name2);
                    $path_with_type2 = $district->l2_name . ', ' . $province->l1_name;

                    $dist = new Unit;
                    $dist->name = $name2;
                    $dist->slug = $slug2;
                    $dist->type = $type2;
                    $dist->name_with_type = $name_with_type2;
                    $dist->path = $path2;
                    $dist->path_with_type = $path_with_type2;
                    $dist->code = $district->l2_code;
                    $dist->parent_code = $province->l1_code;
                    $dist->save();
                    //return 0;

                    $wards = AU::cursor()->filter(function($ward) use ($district){
                        return $ward->l2_name == $district->l2_name;
                    });
                    //->select('l3_name', 'l3_code')->where('l2_name', $district->l2_name)->get();
                    $wards->chunk(200)->each(function($wards) use ($name1, $name2, $province, $district){
                        $wards->each(function($ward) use ($name1, $name2, $province, $district){
                            $name3 = '';
                            $type3 = '';
                            $name_with_type3 = '';
                            if (strpos($ward->l3_name, 'Xã') !== false) {
                                $type3 = 'xa';
                                $name3 = str_replace('Xã ','', $ward->l3_name);
                                $name_with_type3 = "Xã " . $name3;
                            }
                            if (strpos($ward->l3_name, 'Phường') !== false) {
                                $type3 = 'phuong';
                                $name3 = str_replace('Phường ','', $ward->l3_name);
                                $name_with_type3 = "Phường " . $name3;
                            }
                            //dd($name3);
                            $path3 = $name3 . ', ' . $name2 . ', ' . $name1;
                            $slug3 = Str::slug($name3);
                            //dd($path3);
                            $path_with_type3 = $ward->l3_name . ', ' . $district->l2_name . ', ' . $province->l1_name;
                            //dd($path_with_type3);
                            $w = new Unit;
                            $w->name = $name3;
                            $w->slug = $slug3;
                            $w->type = $type3;
                            $w->name_with_type = $name_with_type3;
                            $w->path = $path3;
                            $w->path_with_type = $path_with_type3;
                            $w->code = $ward->l3_code;
                            $w->parent_code = $district->l2_code;
                            //$w->save();
                        });
                    });
                    //dd('end');
                });
            });
        }
        DB::commit();
        dd('dump data sucessfully!!');
    }catch(Exepction $e) {
        DB::rollback();
        dd($e);
    }
});
