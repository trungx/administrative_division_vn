<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\AdministrativeUnit as Unit;

class AdministrativeUnit extends Model
{
    public $timestamps = false;

    protected $fillable = ['l1_name', 'l2_name', 'l3_name' ,'l1_code', 'l2_code', 'l3_code', 'en_name', 'level'];
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
    }

    // Get all districts of provinces/cities
    public function districts()
    {
        $this->hasMany(Unit::class, 'parent_code', 'code');
    }

    // Get all wards of districts
    public function wards()
    {
        $this->hasMany(Unit::class, 'parent_code', 'code');
    }


}
