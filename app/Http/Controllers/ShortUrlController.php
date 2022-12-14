<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\{ShortUrls, User};

use Illuminate\Support\Facades\Auth;

class ShortUrlController extends Controller
{

    protected $short_url_domain ="http://localhost:8002/";

    public function __construct(ShortUrls $shortUrlModel, User $userModel){
        // parent::__construct();
        $this->shortUrlModel = $shortUrlModel;
        $this->userModel = $userModel;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    /**
     * generate token
     * @return \Illuminate\Http\Response
     */
    public function generate(Request $request){
        
        $inputs     = $request->input();

        $og_url   = $inputs["og_url"] ?? "";

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

            $resp = ['short_url' => $shortened_url];

            return $this->respondOk($resp, 1);

        } catch (\Exception $e) {
            $msg = "Something went wrong while generating short url.. please retry.";
            $this->code = 12;
            return $this->respondWithError($msg);
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
        $short_urls = $this->shortUrlModel::where('user_id', Auth::id())->get()->toArray(); //find(2);

        return view('list', ['short_urls' => $short_urls, 'short_url_domain' => $this->short_url_domain]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShortUrls  $shortUrls
     * @return \Illuminate\Http\Response
     */
    public function edit(ShortUrls $shortUrls)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShortUrls  $shortUrls
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShortUrls $shortUrls)
    {
        //
    }


    private function generateShortUrlID(){

        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-';
        $len = 12;
        
        // Shuffle the $str_result and returns substring of specified length
        $shuffled_str = substr(str_shuffle($str_result), 0, $len);

        /**
         *  @todo - check if above $shuffled_str is already present in DB is it is then call resursively this func to generate new one
         */

        return $shuffled_str;
    }
}
