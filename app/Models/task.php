<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    protected $fillable = [
        'title',
        'description',
        'team_id',
        'assigned_user_id',
        'status',
        'due_date',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function scopeAssignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

}