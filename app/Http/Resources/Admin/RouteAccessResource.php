<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RouteAccessResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'route_name' => $this->route_name,
            'role_id' => $this->role_id,
            'permission_id' => $this->permission_id,
            'created_at' => $this->created_at,
            'role' => $this->whenLoaded('role', [
                'id' => $this->role?->id,
                'name' => $this->role?->name,
            ]),
            'permission' => $this->whenLoaded('permission', [
                'id' => $this->permission?->id,
                'name' => $this->permission?->name,
            ]),
        ];
    }
}
