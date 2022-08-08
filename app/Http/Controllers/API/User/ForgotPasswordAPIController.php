<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;

class ForgotPasswordAPIController extends Controller
{

    use SendsPasswordResetEmails;

    /**
     * Forgot password reset link success response
     * @param Request $request
     * @param $response
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse(Request $request)
    {
        $input = $request->only('email');
        $validator = Validator::make($input, [
            'email' => "required|email|max:255|exists:users,email"
        ]);
        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $response =  Password::sendResetLink($input);
        if($response == Password::RESET_LINK_SENT){
            $message = "Mail send successfully";
        } else{
            $message = "This Email is not exists,Please Check your email address";
        }
        return response()->json(['message' => $message],200);
    }

    /**
     * Forgot password reset link fail response
     * @param Request $request
     * @param $response
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    protected function sendResetLinkFailedResponse(Request $request,$response)
    {
        if($request->wantsJson()){
            throw ValidationException::withMessages([
               'email' => [trans($response)]
            ]);
        }

        return response()->json(['error' => trans($response)], config('constants.validation_codes.unprocessable_entity'));
    }
}
