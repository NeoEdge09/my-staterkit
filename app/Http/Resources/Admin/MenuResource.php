<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
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
            'icon' => $this->icon,
            'route' => $this->route,
            'permission' => $this->permission,
            'order' => $this->order,
            'parent_id' => $this->parent_id,
            'parent' => $this->whenLoaded('parent', [
                'id' => $this->parent?->id,
                'name' => $this->parent?->name,
            ]),
            'children' => $this->whenLoaded('children', MenuResource::collection($this->children)),
        ];
    }
}
