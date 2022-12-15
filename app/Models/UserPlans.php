<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPlans extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function upgradeUserPlan($data = [], $data_check_record = []){
        
        $upgraded_plan = self::updateOrCreate(
                            $data_check_record,
                            $data
                        );

        return $upgraded_plan;
        
    }

}
