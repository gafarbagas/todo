<?php

namespace App\Models;

use App\Traits\shortIdTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory, shortIdTrait;

    public $incrementing = false; 

    protected $table="tasks";

    protected $fillable = ["title", "description", "priority", "completed", "user_id"];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($task) {
            $task->id = self::generateShortId();
            $task->user_id = auth()->user()->id;
            $task->priority = self::getLatestPriority() + 1;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    private static function getLatestPriority()
    {
        $latest = Task::where('user_id', auth()->user()->id)
            ->orderBy('priority', 'desc')
            ->first();

        if ($latest)
            return $latest->priority;

        return 0;
    }

    public function reorderTasksByIds($ids)
    {
        $tasks = Task::where('user_id', auth()->user()->id)
            ->get();
        
        if (count($tasks) != count($ids))
            return false;

        foreach ($tasks as $task) {
            $task->priority = array_search($task->id, $ids);
            $task->save();
        }

        return true;
    }
}
