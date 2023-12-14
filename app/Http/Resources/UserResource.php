<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'external_id' => $this->resource->external_id,
            'email' => $this->resource->email,
            'title' => $this->resource->title,
            'first_name' => $this->resource->first_name,
            'last_name' => $this->resource->last_name,
            'picture' => $this->resource->picture,
        ];
    }
}
