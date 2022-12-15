<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPlans extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function upgradeUserPlan($data = [], $data_check_record = []){
        
        $upgraded_plan = self::updateOrCreate(
                            $data_check_record,
                            $data
                        );

        return $upgraded_plan;
        
    }

    public function getUserPlanDetails($user_id){
        
        $data = self::leftJoin('plans', function($join) {
                            $join->on('plans.id', '=', 'user_plans.plan_id');
                        })->where(
                            [
                                ['user_id', '=', $user_id ],
                                ['user_plans.is_active', '=', '1' ],
                                ['user_plans.is_deleted', '=', 0 ]
                            ]
                        )->first(['user_plans.*', 'plans.plan_limit', 'plans.plan_name']); //->toArray();  //->get()->toArray();

        if(!empty($data)){
            $data = $data->toArray();
        }

        return $data;
    }

}
