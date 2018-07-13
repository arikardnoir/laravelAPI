<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth as JWT;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use JWTAuth;

class AuthApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['authenticate']]);
    }
    
    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        //Def status to false
        $status = false;

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials', 'status' => $status], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token', 'status' => $status], 500);
        }

        // Get user authenticated
        $user = auth()->user();

        $status = true;

        // all good so return the token
        return response()->json(compact('token', 'user', 'status'));
    }


    public function getAuthenticatedUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        // the token is valid and we have found the user via the sub claim
        return response()->json(compact('user'));
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
    
    
  
/* public function refreshToken()
{
    // grab the last token from the request
    $token = JWTAuth::getToken();
    
    // attempt to verify the token
    if (!$token = JWTAuth::getToken())
        return response()->json(['error' => 'token_not_send'], 401);

    try {
        // refresh a token for the user
        $token = JWTAuth::refresh();
    } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

         // something went wrong, token send by user is invalid
        return response()->json(['token_invalid'], $e->getStatusCode());
    }
    //All ok, then send a Token refreshed
    return response()->json(compact('token'));
} */

public function refreshToken()
    {
        if (!$token = JWTAuth::getToken())
            return response()->json(['error' => 'token_not_send'], 401);

        try {
            $token = JWTAuth::refresh();
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        }

        return response()->json(compact('token', 'user'));
    }
    
}
