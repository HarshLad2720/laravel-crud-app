<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class VerificationAPIController extends Controller
{
    /**
     * Email Verification
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request)
    {
        $userId                  = $request['id'];
        $user                    = User::find($userId);
        $user->email_verified_at = config('constants.calender.date_time');
        $user->status            = config('constants.user.status_code.active');
        $user->save();

        return response()->json(['data' => $user,config('constants.messages.user_email_verified')]);
    }

    /**
     * Resend the email verification notification.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend(Request $request)
    {
        if($request->user()->hasVerifiedEmail()){
            return response()->json('User already have verified email!',config('constants.validation_codes.unprocessable_entity'));
        }
        $request->user()->sendEmailVerificationNotification();
        return response()->json(config('constants.message.email_notification_resubmitted'));
    }
}
