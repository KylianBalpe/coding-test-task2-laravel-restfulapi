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
                "message" => "Username already exists",
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
}
