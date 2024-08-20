<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'national_code' => 'required|string|max:10|min:10|unique:users',
            'id_number' => 'required|string|max:15|unique:users',
            'phone' => 'required|string|max:15',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ],[
            'name.required' => 'فیلد نام الزامی است.',
            'national_code.required' => 'فیلد کد ملی الزامی است.',
            'id_number.required' => 'فیلد شماره شناسایی الزامی است.',
            'phone.required' => 'فیلد تلفن الزامی است.',
            'email.required' => 'فیلد ایمیل الزامی است.',
            'email.email' => 'فرمت ایمیل صحیح نیست.',
            'password.required' => 'فیلد رمز عبور الزامی است.',
            'national_code.max' => 'کد ملی نمی تواند بیشتر از 10 کاراکتر باشد.',
            'national_code.min' => 'کد ملی نمی تواند کمتر از 10 کاراکتر باشد.',
            'phone.max' => 'تلفن نمی تواند بیشتر از 15 کاراکتر باشد.',
            'id_number.max' => 'شماره شناسایی نمی تواند بیشتر از 15 کاراکتر باشد.',
            'password.min' => 'رمز عبور باید حداقل 8 کاراکتز باشد.',
            'national_code.unique' => 'این کد ملی قبلا ثبت شده است.',
            'id_number.unique' => 'این شماره شناسایی قبلا ثبت شده است.',
            'email.unique' => 'این ایمیل قبلا ثبت شده است.',
        ]);

        $user = User::query()->create([
            'name' => $request->name,
            'national_code' => $request->national_code,
            'id_number' => $request->id_number,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student', // نقش پیش‌فرض
        ]);

        return response()->json([$user], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'id_number' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('id_number', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    }

    public function teacherRegister(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'national_code' => 'required|string|max:10|min:10|unique:users',
            'id_number' => 'required|string|max:15|unique:users',
            'phone' => 'required|string|max:15',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ],[
            'name.required' => 'فیلد نام الزامی است.',
            'national_code.required' => 'فیلد کد ملی الزامی است.',
            'id_number.required' => 'فیلد شماره شناسایی الزامی است.',
            'phone.required' => 'فیلد تلفن الزامی است.',
            'email.required' => 'فیلد ایمیل الزامی است.',
            'email.email' => 'فرمت ایمیل صحیح نیست.',
            'password.required' => 'فیلد رمز عبور الزامی است.',
            'national_code.max' => 'کد ملی نمی تواند بیشتر از 10 کاراکتر باشد.',
            'national_code.min' => 'کد ملی نمی تواند کمتر از 10 کاراکتر باشد.',
            'phone.max' => 'تلفن نمی تواند بیشتر از 15 کاراکتر باشد.',
            'id_number.max' => 'شماره شناسایی نمی تواند بیشتر از 15 کاراکتر باشد.',
            'password.min' => 'رمز عبور باید حداقل 8 کاراکتز باشد.',
            'national_code.unique' => 'این کد ملی قبلا ثبت شده است.',
            'id_number.unique' => 'این شماره شناسایی قبلا ثبت شده است.',
            'email.unique' => 'این ایمیل قبلا ثبت شده است.',
        ]);

        $teacher = User::query()->create([
            'name' => $request->name,
            'national_code' => $request->national_code,
            'id_number' => $request->id_number,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher', // نقش پیش‌فرض
        ]);

        return response()->json([$teacher], 201);
    }

    public function teacherLogin(Request $request) {
        $request->validate([
            'id_number' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('id_number', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'نام کاربری یا رمز عبور اشتباه است.'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
