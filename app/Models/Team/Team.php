<?php

namespace App\Models\Team;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use App\Models\User\User;
use App\Models\Task\Task;
use App\Models\Project\Project;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Boot do modelo para gerar slug automaticamente.
     */
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($team) {
    //         if (empty($team->slug)) {
    //             $team->slug = Str::slug($team->name);
                
    //             $originalSlug = $team->slug;
    //             $count = 1;
    //             while (static::where('slug', $team->slug)->exists()) {
    //                 $team->slug = $originalSlug . '-' . $count;
    //                 $count++;
    //             }
    //         }
    //     });

    //     static::updating(function ($team) {
    //         if ($team->isDirty('name') && empty($team->slug)) {
    //             $team->slug = Str::slug($team->name);
    //             $originalSlug = $team->slug;
    //             $count = 1;
    //             while (static::where('slug', $team->slug)->where('id', '!=', $team->id)->exists()) {
    //                 $team->slug = $originalSlug . '-' . $count;
    //                 $count++;
    //             }
    //         }
    //     });
    // }

    /**
     * Configura o Laravel para usar slug nas rotas em vez de id.
     */
    // public function getRouteKeyName()
    // {
    //     return 'slug';
    // }

    public function users()
    {
        return $this->belongsToMany(User::class, 'team_user')->withTimestamps();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
