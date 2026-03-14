<?php

namespace App\Http\Resources\Team;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
    /**
     * 
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
    
        return [
            // 'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => $this->description,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ];
    
    }
}
