<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            // 'id' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'team_id' => 'nullable|integer|exists:teams,id',
            'assigned_user_id' => 'nullable|integer|exists:users,id',
            'status' => 'required|string|in:pending,in_progress,completed',
            'due_date' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'O campo id é obrigatório.',
            'id.integer' => 'O campo id deve ser um número inteiro.',
            'title.required' => 'O campo título é obrigatório.',
            'title.string' => 'O campo título deve ser uma string.',
            'title.max' => 'O campo título não pode exceder 255 caracteres.',
            'description.string' => 'O campo descrição deve ser uma string.',
            'team_id.integer' => 'O campo team_id deve ser um número inteiro.',
            'team_id.exists' => 'O time especificado não existe.',
            'assigned_user_id.integer' => 'O campo assigned_user_id deve ser um número inteiro.',
            'assigned_user_id.exists' => 'O usuário atribuído especificado não existe.',
            'status.required' => 'O campo status é obrigatório.',
            'status.string' => 'O campo status deve ser uma string.',
            'status.in' => 'O campo status deve ser um dos seguintes valores: pending, in_progress, completed.',
            'due_date.date' => 'O campo due_date deve ser uma data válida.',
        ];
    }
}