<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\LoginAPIRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthAPIController extends BaseAPIController
{
    
    //Login attempt for the API and token generator
    public function gettoken(LoginAPIRequest $loginAPIRequest){
       
        try{

            Log::channel('api_auth_log')->info("[Authenticate] ==> authenticate request received - ".json_encode($loginAPIRequest->all()));

            if(Auth::attempt(['email' => $loginAPIRequest->email, 'password' => $loginAPIRequest->password])){

                $user = Auth::user();
                $userToken = $user->createToken('Token')->plainTextToken;

                Log::channel('api_auth_log')->info("[Authenticate] ==> authentication successful");

                return $this->sendResponse(true,200,'Authentication success',array('token' => $userToken));


            }else{

                Log::channel('api_auth_log')->info("[Authenticate] ==> authentication unsuccessful. Unauthorized");

                return $this->sendResponse(false,401,'Unauthorized access',null);
            }


        }catch(\Exception $exception){

            Log::channel('api_auth_log')->info("[Authenticate] ==> authentication error occured = ".$exception->getMessage());

            return $this->sendResponse(false,500,$exception->getMessage(),null);

        }
    }
}
