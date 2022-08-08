<?php

use Carbon\Carbon;

return[

    'calender' => [
        'date'          => Carbon::now()->toDateString(),
        'date_format'   => Carbon::now()->format('Y-m-d'),
        'time'          => Carbon::now()->toTimeString(),
        'date_time'     => Carbon::now()->toDateTimeString(),
    ],

    'messages' => [
        'user' => [
            'invalid' => 'Invalid credentials',
        ],

        'success'                   => 'Success',
        'delete_success'            => 'Delete Successfully',
        'registration_success'      =>'Please check your inbox to activate your account',
        'forgotpassword_success'    =>'Password reset instructions has been sent to your email. Please check your inbox/spam',
        'forgotpassword_error'      =>'Invalid Email',
        'something_wrong'           => 'Something went wrong.',
        'login' => [
            'success'               => 'Login is successful.',
            'unverified_account'    => 'Your account is not verified yet.',
            'wrong_credentials'     => 'Invalid combination of email and password.',
            'login_token_failed'    => 'Could not create login token.',
        ],
        'password_changed'                  => "Password has been changed.",
        'something_went_wrong'              => 'Something went wrong.',
        'invalid_old_password'              => "Invalid old password.",
        'similar_password'                  => "Please enter a password which is not similar then current password.",
        'not_match_confirm_password'        => "New password is not match to confirm password.",
        'delete_sucess'                     => 'Delete Successful.',
        'delete_multiple_error'             => 'Please select records',
        'user_email_verified'               => 'Email Verified Successfully!',
        'create_success'                    => 'Created successfully.',
        'update_success'                    => 'Updated successfully.',
        'change_success'                    => 'Changed successfully.',
        'import_success'                    => 'Imported successfully.',
        'admin_role_delete_error'           => 'Administrator role can not be delete. ',
        'admin_user_delete_error'           => 'Super-admin user can not be delete. ',
    ],

    'validation_codes' => [
        'unauthorized'         => 401,
        'forbidden'            => 403,
        'unprocessable_entity' => 422,
        'ok'                   => 200,
    ],

    'user'=>[
        'status' => [
            '0' => 'Inactive',
            '1' => 'Active',
        ],
        'status_code' => [
            'inactive'  => '0',
            'active'    => '1',
        ],

        'gender' => [
            '0' => 'Female',
            '1' => 'Male',
        ],
        'gender_id' => [
            'female' => '0',
            'male'   => '1',
        ],
    ],

    'permission' => [
        'has_permission'                => '1',
        'has_not_permission'            => '0',
        'role_guard_name'               => 'web',
        'user_has_not_permission'       => "You don\'t have permission to this functionality",
        'user_already_has_permission'   => "Given permission already exists",
        'user_clinic_mapping_error'     => "User clinic is not mapped yet",
        'module_error'                  => "Module not in request",
        'invalid_module_error'          => "Invalid Module",
        'validation_error_status_code'  => 422,
        'permission_assign_success'     => 'Permission assign successfully',
        'permission_revert_success'     => 'Permission reverted successfully',
        'permission_revert_failure'     => 'Permission revert failed',
        'permission_not_found'          => 'Permission not found',
        'role_not_found'                => 'Role not found',
    ],

    'allowed_ip_addresses' => [
        'telescope'     => env('TELESCOPE_ALLOWED_IP_ADDRESSES'),
    ],

    'role'=>[
        'apply_role' => '1',
    ],

    'image' => [
        'dir_path'          => '/storage/',
        'default_types'     => 'gif|jpg|png|jpeg',
        'user_default_img'  => 'images/default.jpg',
    ],

    'models'=>[
        'user_model'            => 'user',
        'city_model'            => 'city',
        'hobby_model'           => 'hobby',
        'country_model'         => 'country',
        'state_model'           => 'state',
        'role_model'            => 'role',
        'permission_model'      => 'permission',
    ],

    'import_dir_path'=>[
        'user_dir_path'            => 'import/user/',
        'city_dir_path'            => 'import/city/',
        'hobby_model'              => 'hobby',
        'country_dir_path'         => 'import/country/',
        'state_dir_path'           => 'import/state/',
        'role_dir_path'            => 'import/role/',
        'permission_dir_path'      => 'import/permission/',
    ],

    'sample_dir_path'=>[
        'sample_users'      => '/storage/public/samples/users.csv',
        'sample_cities'     => '/storage/public/samples/city.csv',
        'sample_hobbies'    => '/storage/public/samples/hobby.csv',
        'sample_countries'  => '/storage/public/samples/country.csv',
        'sample_states'     => '/storage/public/samples/state.csv',
        'sample_role'       => '/storage/public/samples/role.csv',
        'sample_permission' => '/storage/public/samples/permission.csv',
    ],
];
