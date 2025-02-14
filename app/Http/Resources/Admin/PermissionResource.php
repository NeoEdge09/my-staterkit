<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
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
            'name' => $this->name,
            'module' => $this->module,
            'guard_name' => $this->guard_name,
            'roles' => $this->whenLoaded('roles', function () {
                return $this->permissions->pluck('name')->toArray();
            }),
            'created_at' => $this->created_at->format('d M Y'),
        ];
    }
}
