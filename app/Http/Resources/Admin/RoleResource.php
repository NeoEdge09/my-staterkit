<?php

namespace App\Http\Resources\Admin;

use App\Traits\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    use User;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'guard_name' => $this->guard_name,
            'permissions' => $this->whenLoaded('permissions', function () {
                return $this->permissions->pluck('name')->toArray();
            }),
            'created_at' => $this->created_at->format('d M Y')
        ];
    }
}
