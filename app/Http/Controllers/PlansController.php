<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\{Plans, UserPlans};

class PlansController extends Controller
{
    
    protected $plansModel;
    protected $userPlanModel;

    public function __construct(Plans $plansModel, UserPlans $userPlanModel){
        parent::__construct();
        $this->plansModel = $plansModel;
        $this->userPlanModel = $userPlanModel;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShortUrls  $shortUrls
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $all_plans = $this->plansModel::all()->toArray();

        return view('plan-list', ['all_plans' => $all_plans]);
    }

    public function upgrade(Request $request){

        $inputs = $request->input();
        $plan_id = $inputs['plan_id'] ?? "";

        try {
            // validatate url
            $validator = Validator::make(
                
                $request->all(),
                [
                    'plan_id' => 'required',
                ]
            );
            
            if ($validator->fails()) {
                $this->code = 21; // validation failed for plan id
                return $this->respondWithValidationError($validator->errors());
            }

            $tobe_checked  = ['user_id' => Auth::id()];

            $plan_details = $this->plansModel::find($plan_id)->toArray();
            $plan_expiry_term = $plan_details['plan_expiry_term'];

            $tobe_inserted['start_date'] = date("Y-m-d");
            $tobe_inserted['plan_id']    = $inputs['plan_id'];

            switch ($plan_expiry_term) {
                case 'M':
                    $tobe_inserted['end_date'] = date("Y-m-d", strtotime ( '+1 month' , strtotime ( $tobe_inserted['start_date'] ) )) ;

                    break;

                case 'Y':
                    $tobe_inserted['end_date'] = date("Y-m-d", strtotime ( '+1 year' , strtotime ( $tobe_inserted['start_date'] ) )) ;
                    break;

                default:
            }

            // create or update the plan against the user
            $upgraded_plan = $this->userPlanModel->upgradeUserPlan($tobe_inserted, $tobe_checked);

            if($upgraded_plan){
                $resp = ['msg' => "Congo!! Your plan has been upgraded to ". $plan_details['plan_name'] ];
                return $this->respondOk($resp, 1);
            }else{
                throw new \Exception("Could not upgrade the plan");
            }
            

            return $this->respondOk($resp, 1);

        } catch (\Exception $e) {
            $msg = "Something went wrong while upgrading your plan.. please retry.";
            $this->code = 22;
            return $this->respondWithError($msg);
        }

    }

}
