<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Helpers\ResponseFormatter;
use App\Models\Mobile\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function authenticate(Request $request)
    {
        $rules = [
            'phone_number' => ['required', 'String', 'max:20', 'min:12'],
            'photo_url' => 'mimes:jpeg,jpg,png,webp',
            'device_id' => 'required',
        ];

        $messages = [
            'phone_number.required' => 'You must enter phone number',
            'phone_number.max' => 'Phone number max : 20 digits',
            'phone_number.min' => 'Phone number min : 12 digits',
            'photo_url.mimes' => 'Photo format must be jpeg, jpg, png, or webp',
            'device_id.required' => 'The device id is required!'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return ResponseFormatter::error([
                'message' => $validator->errors()->first(),
            ], 'Authentication Failed', 500);
        } else {
            if ($request->hasFile('photo_url')) {
                $image = $request->file('photo_url');
                $imageName = 'user-' . time() . '.' . $image->extension();
                $image->move(public_path('images/users'), $imageName);
            } else {
                $imageName = null;
            }

            $isUserExist = User::where('phone_number', $request->phone_number)->first();

            // if user exist
            if (!$isUserExist) {
                //create a new data
                $createUser = User::create([
                    'name' => $request->name,
                    'photo_url' => $imageName,
                    'phone_number' => $request->phone_number,
                    'email' => $request->email,
                    'device_id' => $request->device_id,
                ]);
                $user = User::where('phone_number', $request->phone_number)->first();
            } else {
                //update user data
                $user = User::where('phone_number', $request->phone_number)->first();
                $user->name = $request->name ?? $user->name;
                $user->photo_url = $imageName ?? $user->photo_url;
                $user->email = $request->email ?? $user->email;
                $user->device_id = $request->device_id; //get a new device id
                $user->touch();
            }

            //create a token to request any api(s)
            $tokenResult = $user->createToken('MobileApp')->accessToken;

            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user,
                'base_photo_url' => url('images/users'),
            ], 'User');
        }
    }

    public function details()
    {
        $user = auth()->guard('mobile')->user();

        return ResponseFormatter::success([
            'user' => $user,
        ], 'User Data Retrieved');
    }

    public function deviceCheck(Request $request)
    {
        // dd($request->header('authorization'));
        $rules = [
            'device_id' => 'required',
        ];

        $messages = [
            'device_id.required' => 'The device id is required!'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return ResponseFormatter::error([
                'message' => $validator->errors()->first(),
            ], 'Device Check Failed', 500);
        } else {

            $user = auth()->guard('mobile')->user();

            return ResponseFormatter::success([
                'is_valid' => $user->device_id == $request->device_id,
            ], 'User Device Checked');
        }
    }

    public function update(Request $request)
    {
        $rules = [
            'photo_url' => 'mimes:jpeg,jpg,png,webp',
        ];

        $messages = [
            'photo_url.mimes' => 'Photo format must be jpeg, jpg, png, or webp',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        // get auth user
        $auth = auth()->guard('mobile')->user();

        if ($validator->fails()) {
            return ResponseFormatter::error([
                'message' => $validator->errors()->first(),
            ], 'Profile Update Failed', 500);
        } else {
            // if request has photo
            if ($request->hasFile('photo_url')) {
                $image_path = public_path("/images/users/" . $auth->photo_url);  // Value is not URL but directory file path
                // dd($image_path);
                if (File::exists($image_path)) {
                    File::delete($image_path);
                }
                $image = $request->file('photo_url');
                $imageName = 'user-' . time() . '.' . $image->extension();
                $image->move(public_path('images/users'), $imageName);
            } else {
                // if request has no photo
                $imageName = null;
            }

            //update user data
            $user = User::findOrFail($auth->id);
            $user->name = $request->name ?? $user->name;
            $user->photo_url = $imageName ?? $user->photo_url;
            $user->touch(); //trigger updatedAt

            return ResponseFormatter::success([
                'user' => $user,
                'base_photo_url' => url('images/users'),
            ], 'User profile updated');
        }
    }
}
