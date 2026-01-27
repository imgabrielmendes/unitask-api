<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    public function authorize()
    {
        // Verifica se o usuário pertence ao time informado no request
        // Isso substitui aquele if do "Forbidden" no seu controller original
        return $this->user()->teams()->whereKey($this->input('team_id'))->exists();
    }

    public function rules()
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'team_id' => ['required', 'integer', 'exists:teams,id'],
            'assigned_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'status' => ['nullable', 'in:pending,in_progress,completed'],
            'due_date' => ['nullable', 'date'],
        ];
    }
}