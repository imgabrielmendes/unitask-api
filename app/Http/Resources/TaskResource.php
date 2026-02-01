<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            // 'id' => $this->id,
            
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,

            // 'due_date' => $this->due_date,
            // 'team_id' => $this->team_id,
            // 'assigned_user_id' => $this->assigned_user_id,
            // 'data_criacao' => $this->created_at->locale('pt_BR')->translatedFormat('d \d\e F \d\e Y \à\s H:i'),
            // 'updated_at' => $this->updated_at,
            
            // 'team' => $this->whenLoaded('team', fn() => [
            //     // 'id' => $this->team->id,
            //     'name' => $this->team->name,
            // ]),

            // 'assigned_user' => $this->whenLoaded('assignedUser', fn() => [
            //     'id' => $this->assignedUser->id,
            //     'name' => $this->assignedUser->name,
            // ]),
        ];
    }
}