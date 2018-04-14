<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;


class AuthApiController extends Controller
{
    
    
    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
    }

    /*
    public function refreshToken(Request $request){
        
        $token = $request->get('token'); 
        
        if( !$token ){
            
            return response()->json(['error' => 'token_not_send'], 401);
        }
        
        try{
            $token = JWTAuth::refresh($token);
        }catch (TokenInvalidException $e){
            return response()->json(['error' => 'token_invalid'], 401);
        }
        
        return response()->json(compact('token'));
        
    }*/
    
    
  
public function refreshToken()
{
    $token = JWTAuth::getToken();
    
    if (!$token = JWTAuth::getToken())
        return response()->json(['error' => 'token_not_send'], 401);

    try {
        $token = JWTAuth::refresh();
    } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
        return response()->json(['token_invalid'], $e->getStatusCode());
    }

    return response()->json(compact('token'));
}
    
}
