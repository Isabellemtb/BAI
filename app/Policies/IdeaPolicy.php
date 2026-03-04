<?php

namespace App\Policies;

use App\Models\Idea;
use App\Models\User;

class IdeaPolicy
{
    /**
     * Seul l'auteur de l'idée peut la modifier.
     */
    public function update(User $user, Idea $idea): bool
    {
        return $user->id === (int) $idea->user_id;
    }

    /**
     * Seul l'auteur de l'idée ou un admin peut la supprimer.
     */
    public function delete(User $user, Idea $idea): bool
    {
        return $user->id === (int) $idea->user_id || $user->isAdmin();
    }
}
