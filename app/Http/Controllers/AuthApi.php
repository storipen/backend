<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\User;
use Illuminate\Routing\Controller as BaseController;
use Tymon\JWTAuth\Facades\JWTFactory;
class AuthApi extends BaseController
{
   
    //
    public function __construct()
    {
        $this->middleware('guest:api')->except('postLogout');
    }
    public function handleCallback(Request $request)
    {
        try {
            
            $user = User::where('social_id','=',$request->only('social_id'))->first();
            if ($user!=null){
                if($request->get('email')!= $user->email){
                    return response()->json(['error' => 'authentication failed'], 404);
                }  
                $payloadable = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'social_id'=>$user->social_id,
                
                ];
                error_log($user->id);
                $payload = JWTFactory::sub($user->id)
                            ->user($user)
                            ->make();
    
                if (! $token = JWTAuth::fromUser($user,$payload)) {
                    return response()->json(['error' => 'invalid_credentials'], 400);
                }
                return response()->json(compact('user','token'));
            }
            if($user==null){
                try{
                    $user = User::create([
                    'name' => $request->get('name'),
                    'email' => $request->get('email'),
                    'password' => Hash::make($request->get('password')),
                    'social_id'=> $request->get('social_id'),
                    'picture'=>$request->get('picture'),
                ]);
                error_log($user->id);
                }catch(\Illuminate\Database\QueryException $ex){
                    if($ex->errorInfo[1]=='1062'){
                        return response()->json($ex->errorInfo[2]);
                    }
                }
                $token = JWTAuth::fromUser($user);
                return response()->json(compact('user','token'),201);
            }

            // return response()->json(compact('user','token'),201);
            
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

       
    }
    public function register(Request $request)
    {
        error_log($request);
        // $validator = Validator::make($request->all(), [
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|string|email|max:255|unique:users',
        //     'password' => 'required|string|min:6|confirmed',

        // ]);

        // if($validator->fails()){
        //     return response()->json($validator->errors()->toJson(), 400);
        // }
        try{
            $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'social_id'=> $request->get('social_id'),
            'picture'=>$request->get('picture'),
        ]);
        
        }catch(\Illuminate\Database\QueryException $ex){
            if($ex->errorInfo[1]=='1062'){
                return response()->json($ex->errorInfo[2]);
            }
        }
        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
    }
    public function getAuthenticatedUser()
    {
        error_log("masuk");
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

        return response()->json(compact('user'));
    }
    public function refresh(){
        error_log("Masuk auth refresh");
        $tokenold = JWTAuth::getToken();
        error_log($tokenold);
        $token = JWTAuth::refresh($tokenold);
        return response()->json(compact('token'),201);
    }
}
