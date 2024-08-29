<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{

    public function testRegisterValidationError()
    {
        $this->post("/api/user/register", [
            "name" => "",
            "email" => "",
            "password" => "",
        ])->assertStatus(422)
            ->assertJson([
                "ok" => false,
                "status" => 422,
                "message" => "Validation error",
                "errors" => [
                    "name" => ["The name field is required."],
                    "email" => ["The email field is required."],
                    "password" => ["The password field is required."],
                ]
            ]);
    }

    public function testRegisterEmailExists()
    {
        $this->testRegisterSuccess();
        $this->post("/api/user/register", [
            "name" => "John Doe",
            "email" => "admin@example.com",
            "password" => "password",
        ])->assertStatus(400)
            ->assertJson([
                "ok" => false,
                "status" => 400,
                "message" => "Email already exists",
            ]);
    }

    public function testRegisterSuccess()
    {
        $this->post("/api/user/register", [
            "name" => "John Doe",
            "email" => "admin@example.com",
            "password" => "password",
        ])->assertStatus(201)
            ->assertJson([
                "ok" => true,
                "status" => 201,
                "message" => "User created successfully",
                "data" => [
                    "name" => "John Doe",
                    "email" => "admin@example.com",
                ]
            ]);
    }

    public function testLoginSuccess()
    {
        $this->testRegisterSuccess();
        $this->post("/api/user/login", [
            "email" => "admin@example.com",
            "password" => "password",
        ])->assertStatus(200)
            ->assertJson([
                "ok" => true,
                "status" => 200,
                "message" => "User logged in successfully",
                "data" => [
                    "name" => "John Doe",
                    "email" => "admin@example.com",
                ]
            ]);
    }

    public function testLoginValidationError()
    {
        $this->post("/api/user/login", [
            "email" => "",
            "password" => "",
        ])->assertStatus(422)
            ->assertJson([
                "ok" => false,
                "status" => 422,
                "message" => "Validation error",
                "errors" => [
                    "email" => ["The email field is required."],
                    "password" => ["The password field is required."],
                ]
            ]);
    }

    public function testLoginCredentialsError()
    {
        $this->testRegisterSuccess();
        $this->post("/api/user/login", [
            "email" => "user@example.com",
            "password" => "password",
        ])->assertStatus(401)
            ->assertJson([
                "ok" => false,
                "status" => 401,
                "message" => "Email or password is incorrect",
            ]);
    }
}
