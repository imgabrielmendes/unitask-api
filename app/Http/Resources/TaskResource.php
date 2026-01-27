<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            // 'team_id' => $this->team_id,
            // 'assigned_user_id' => $this->assigned_user_id,
            // 'status' => $this->status,
            // 'due_date' => $this->due_date,
            // 'created_at' => $this->created_at,
            'data_criacao' => $this->created_at->locale('pt_BR')->translatedFormat('d \d\e F \d\e Y \à\s H:i'),
            // 'updated_at' => $this->updated_at,
        ];
    }
}
