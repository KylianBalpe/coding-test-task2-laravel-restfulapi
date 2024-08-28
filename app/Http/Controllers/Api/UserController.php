<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
}
