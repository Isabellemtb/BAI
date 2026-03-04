<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    /**
     * L'auteur du commentaire ou un admin peut le modifier.
     */
    public function update(User $user, Comment $comment): bool
    {
        return $user->id === (int) $comment->user_id || $user->isAdmin();
    }

    /**
     * Seul l'auteur du commentaire ou un admin peut le supprimer.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === (int) $comment->user_id || $user->isAdmin();
    }
}
