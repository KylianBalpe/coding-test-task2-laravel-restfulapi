<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public $ok;
    public $status;
    public $message;

    public function __construct($resource, $ok, $status, $message)
    {
        parent::__construct($resource);
        $this->ok = $ok;
        $this->status = $status;
        $this->message = $message;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "ok" => $this->ok,
            "status" => $this->status,
            "message" => $this->message,
            "data" => [
                "name" => $this->name,
                "email" => $this->email,
                "token" => $this->whenNotNull($this->token),
            ],
        ];
    }
}
