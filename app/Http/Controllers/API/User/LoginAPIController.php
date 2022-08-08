<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Resources\DataTrueResource;
use App\Http\Resources\User\LoginResource;
use App\Models\User\Permission;
use App\Models\User\Role;
use App\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Laravel\Passport\Client;
use Laravel\Passport\RefreshToken;

class LoginAPIController extends Controller
{
    /**
     * Login user and create token
     *
     * @param LoginRequest $request
     * @return LoginResource|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */

    public function login(LoginRequest $request)
    {
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials)){
            return response()->json(['error' => config('constants.messages.user.invalid')], config('constants.validation_codes.unprocessable_entity'));
        }

        $user = $request->user();

        $oauthClient = Client::where('password_client',1)->latest()->first();
        if(is_null($oauthClient)){
            return User::GetError('Oauth Password Client not Found');
        }

        if ((isset($user) && $user->status != config('constants.user.status_code.active'))) {
            return response()->json(['error' => config('constants.messages.login.unverified_account')],config('constants.validation_codes.unprocessable_entity'));
        }

        $userVerified = User::whereNotNull('email_verified_at')->where('id', $user->id)->first();
        if(is_null($userVerified)){
            return response()->json(["error" => config('constants.messages.login.unverified_account')], config('constants.validation_codes.unprocessable_entity'));
        }


        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if($user != null){
            foreach ($user->tokens->where('revoked','0') as $token){
                RefreshToken::where('access_token_id',$token->id)->update(['revoked' => '1']);
                if(!$token->revoked){
                    User::revoke_token($token); //Revoking token
                }
            }
            //get User Permission and save permission in token
            $role = Role::findorfail($user->role_id);//get role details
            $data = [
                'username'      => $request->email,
                'password'      => $request->password,
                'client_id'     => $oauthClient->id,
                'client_secret' => $oauthClient->secret,
                'grant_type'    => 'password',
            ];
            $request = app('request')->create('/oauth/token', 'POST', $data);
            $tokenResult = json_decode(app()->handle($request)->getContent());
            $getToken = User::getUserActiveToken($user->id, $oauthClient->id);
            $getToken->scopes = $user->role->permissions->pluck('name')->toArray();
            $getToken->save();

            $user->permission = Permission::getPermissions($role);
            $user->authorization = $tokenResult->access_token;
            $user->refresh_token = $tokenResult->refresh_token;
            return new LoginResource($user);
        }else{
            return response("No User found.", config('constants.validation_codes.unprocessable_entity') );
        }

    }

    /**
     * change password functionality.
     *
     * @param ChangePasswordRequest $request
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        //get all updated data.
        $data = $request->all();
        $masterUser = User::where('email', $request->user()->email)->first();
        if (Hash::check($data['old_password'], $masterUser->password)) {
            $masterData['password'] = bcrypt($data['new_password']);
            //update user password in master user table
            if ($masterUser->update($masterData)){
                return response()->json(['success'=>config("constants.messages.password_changed")]);
            }else{
                return response()->json(['error' => config("constants.messages.something_wrong")],config('constants.validation_codes.unprocessable_entity'));
            }
        }
        else{
            return response()->json(['error' => config("constants.messages.invalid_old_password")],config('constants.validation_codes.unprocessable_entity'));
        }

    }

    /**
     * Logout User
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        return response()->json('You have been Successfully logged out!');
    }

    public function refreshingTokens(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required'
        ]);

        $oauthClient = Client::where('password_client', 1)->latest()->first();
        if (is_null($oauthClient)){
            return User::GetError('Oauth password client not found.');
        }
        $data = [
            'client_id'      => $oauthClient->id,
            'client_secret'  => $oauthClient->secret,
            'grant_type'     => 'refresh_token',
            'refresh_token'  => $request->refresh_token,
        ];
        $request     = app('request')->create('/oauth/token', 'POST', $data);
        $tokenResult = json_decode(app()->handle($request)->getContent());

        if (!isset($tokenResult->access_token)) {
            return User::GetError('The refresh token is invalid.');
        }
        $tokenId = (new Parser(new JoseEncoder()))->parse($tokenResult->access_token)->claims()->all()['jti'];
        $accessToken = Token::where('id', $tokenId)->first();

        if ($accessToken) {
            $lytLoginUsr = User::where('loginUId', $accessToken->user_id)->with(['role'])->firstorfail();

            // Update scopes for each user access tokens
            $getToken = User::getUserActiveToken($lytLoginUsr->loginUId, $oauthClient->id);
            if (is_null($getToken)) {
                return User::GetError('The refresh token is invalid.');
            }

            $getToken->scopes = $lytLoginUsr->role->permissions->pluck('name')->toArray();
            $getToken->save();
        }

        if (isset($tokenResult->error)) {
            return User::GetError($tokenResult->message);
        }

        return response()->json($tokenResult);
    }
}
