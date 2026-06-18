<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Memastikan user hanya bisa berinteraksi dengan task jika task tersebut 
     * berada di dalam project milik user yang bersangkutan (Anti-IDOR).
     */
    public function interact(User $user, Task $task): bool
    {
        return $user->id === $task->project->user_id;
    }
}