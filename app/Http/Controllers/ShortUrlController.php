<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\{ShortUrls, User, UserPlans};

use Illuminate\Support\Facades\Auth;

class ShortUrlController extends Controller
{

    protected $short_url_domain ="http://localhost:8002/";

    protected $defaultFreePlanModelId;

    public function __construct(ShortUrls $shortUrlModel, User $userModel, UserPlans $userPlanModel){
        parent::__construct();
        
        $this->defaultFreePlanModelId = 1;

        $this->shortUrlModel = $shortUrlModel;
        $this->userModel     = $userModel;
        $this->userPlanModel = $userPlanModel;
        
        
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::id();
        return view('home');
    }

    /**
     * generate token
     * @return \Illuminate\Http\Response
     */
    public function generate(Request $request){
        
        $inputs  = $request->input();

        $og_url  = $inputs["og_url"] ?? "";
        $user_id = Auth::id();
        
        $user_plan_details        = $this->userPlanModel->getUserPlanDetails($user_id);
        $total_short_urls_created = $this->shortUrlModel::where('user_id', $user_id)->count();

        if(!empty($user_plan_details)){
            // check if curr date is more than end_date
            $curr_date = date('Y-m-d');
            $end_date  = $user_plan_details['end_date'];
            // 
            if(strtotime($curr_date) > strtotime($end_date)){
                $resp = ['msg' => 'Your plan is Expired.. pls consider upgrading again'];
                $code = 13;
                return $this->respondOk($resp, $code);
            }else{
                // check for limit short url limit exceed
                if( ($total_short_urls_created >= $user_plan_details['plan_limit']) &&  $user_plan_details['plan_limit'] != -1){
                    $resp = ['msg' => 'Your have exceeded the Plan limit.. pls consider upgrading'];
                    $code = 14;
                    return $this->respondOk($resp, $code);
                }
            }
        }

        try {
            // validatate url
            $validator = Validator::make(
                
                $request->all(),
                [
                    'og_url' => 'required',
                ]
            );

            /**
             * @todo check if url is a valid domain if not then thow the error
             */
            
            if ($validator->fails()) {
                $this->code = 11; // validation failed
                return $this->respondWithValidationError($validator->errors());
            }

            $short_url_id   = $this->generateShortUrlID();
            $shortened_url  = $this->short_url_domain. "" .$short_url_id;

            $tobe_inserted  = [
                                'short_url' => $short_url_id,
                                'user_id' => Auth::id(),
                                'og_url' => $og_url,
                            ];

            $id = $this->shortUrlModel->create($tobe_inserted);

            if( $total_short_urls_created == 0 && empty($user_plan_details) ){
                // insert free plan By default for user
                $tobe_checked                = ['user_id' => Auth::id()];
                $uplan_tobe_inserted['start_date'] = date("Y-m-d");
                $uplan_tobe_inserted['plan_id']    = $this->defaultFreePlanModelId; // default free plan ID
                $uplan_tobe_inserted['end_date']   = date("Y-m-d", strtotime ( '+1 month' , strtotime ( $uplan_tobe_inserted['start_date'] ) )) ;

                $free_plan = $this->userPlanModel->upgradeUserPlan($uplan_tobe_inserted, $tobe_checked);
            }

            $resp = ['short_url' => $shortened_url];

            return $this->respondOk($resp, 1);

        } catch (\Exception $e) {
            $msg = "Something went wrong while generating short url.. please retry.";
            $this->code = 12;
            return $this->respondWithError($e);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShortUrls  $shortUrls
     * @return \Illuminate\Http\Response
     */
    public function show(ShortUrls $shortUrls)
    {
        $short_urls = $this->shortUrlModel::where(
            [
                ['user_id', '=', Auth::id()],
                ['is_active', '=', '1'],
                ['is_deleted', '=', 0]
            ]
        )->get()->toArray();

        return view('list', ['short_urls' => $short_urls, 'short_url_domain' => $this->short_url_domain]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShortUrls  $shortUrls
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if($request->isMethod('get')){

            /**
             * @todo check for inactive and deleted flags
             */
            $url = $this->shortUrlModel::find($id)->toArray();
            return view('edit-short-url', ['data' => $url, 'short_url_domain' => $this->short_url_domain]);
        }

        $inputs = $request->input();

        try {
            // validatate url
            $validator = Validator::make(
                
                $request->all(),
                [
                    'short_url' => 'required',
                ]
            );

            if($validator->fails()) {
                $this->code = 16; // validation failed
                return $this->respondWithValidationError($validator->errors());
            }

            $tobe_updated['short_url'] = $inputs['short_url'];

            $updated = $this->shortUrlModel::where('id', $id)
                                ->update($tobe_updated);

            return redirect('/list');

        } catch (\Exception $e) {
            
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShortUrls  $shortUrls
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $inputs  = $request->input();

        try {
            // validatate url
            $validator = Validator::make(
                
                $request->all(),
                [
                    'short_url_id' => 'required',
                    'action' => 'required'
                ]
            );

            if ($validator->fails()) {
                $this->code = 15; // validation failed
                return $this->respondWithValidationError($validator->errors());
            }

            if($inputs['action'] == 'disable'){
                $tobe_updated['is_active'] = '0';
                $res_msg = "Url Disabled..";
            }elseif($inputs['action'] == 'delete'){
                $tobe_updated['is_deleted'] = 1;
                $res_msg = "Url Deleted..";
            }

            $updated = $this->shortUrlModel::where('id', $inputs['short_url_id'])
                                ->update($tobe_updated);

            $resp['msg'] = $res_msg;
            return $this->respondOk($resp, 1);

        } catch (\Exception $e) {
            $msg = "Something went wrong while generating short url.. please retry.";
            $this->code = 12;
            return $this->respondWithError($e);
        }
    }

    /**
     * function to generate short url id which will be stored in short_url col
    */
    private function generateShortUrlID(){

        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-';
        $len = 12;
        
        // Shuffle the $str_result and returns substring of specified length
        $short_url_id = substr(str_shuffle($str_result), 0, $len);

        $is_present   = $this->shortUrlModel->where('short_url', $short_url_id)->count();
        
        if($is_present > 0){
            $this->generateShortUrlID();
        }

        return $short_url_id;
    }
}
