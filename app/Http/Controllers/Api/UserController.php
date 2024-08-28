<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        Log::info("Registering user " . json_encode($data, JSON_PRETTY_PRINT));

        if (User::where("email", $data["email"])->exists()) {
            Log::info("Email already exists " . json_encode($data, JSON_PRETTY_PRINT));
            throw new HttpResponseException(response([
                "ok" => false,
                "status" => 400,
                "message" => "Username already exists"
            ], 400));
        }

        $data["password"] = bcrypt($data["password"]);
        $user = User::create($data);

        Log::info("User created successfully " . json_encode($user, JSON_PRETTY_PRINT));
        return (new UserResource($user, true, 201, "User created successfully"))->response();
    }

    public function login(UserLoginRequest $request)
    {
        $data = $request->validated();
        Log::info("Logging in user " . json_encode($data, JSON_PRETTY_PRINT));

        $user = User::where("email", $data["email"])->first();

        if (!$user || !password_verify($data["password"], $user->password)) {
            Log::info("Invalid credentials " . json_encode($data, JSON_PRETTY_PRINT));
            throw new HttpResponseException(response([
                "ok" => false,
                "status" => 401,
                "message" => "Email or password is invalid"
            ], 401));
        }

        $user["token"] = $user->createToken("auth_token")->plainTextToken;

        Log::info("User logged in successfully " . json_encode($user, JSON_PRETTY_PRINT));
        return (new UserResource($user, true, 200, "User logged in successfully"))->response();
    }
}
