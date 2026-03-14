<?php

namespace App\Models\Member;

use Illuminate\Database\Eloquent\Model;
use App\Models\Team\Team;
use App\Models\User\User;

class Member extends Model
{
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
}
