<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TokenResource extends JsonResource
{
    /**
     * @return array{
     *     access_token: string,
     *     token_type: string,
     *     expires_in: int
     * }
     */
    public function toArray(Request $request): array
    {
        return [
            'access_token' => $this->resource->accessToken,
            'token_type' => $this->resource->tokenType,
            'expires_in' => $this->resource->expiresIn,
        ];
    }
}
