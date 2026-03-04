<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use App\Models\Comment;
use App\Services\Logging\ActionLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller responsible for comments on ideas.
 *
 * NOTE:
 * - No validation (TODO)
 * - No limit per user (add max 3 comments per idea) (TODO)
 * - No authorization on delete (TODO secure)
 */
class CommentController extends Controller
{
    public function __construct(
        private ActionLogService $logService
    ) {
    }

    /**
     * Store a new comment for an idea.
     */
    public function store(Request $request, Idea $idea)
    {
        $comment = Comment::create([
            'idea_id'     => $idea->id,
            'user_id'     => Auth::id(),
            'description' => $request->input('description'), // XSS vulnerable
        ]);

        $this->logService->log(
            userId: Auth::id(),
            action: 'comment_created',
            ideaId: $idea->id,
            commentId: $comment->id,
            dataAfter: json_encode($comment->toArray()),
            request: $request,
        );

        return redirect()
            ->route('ideas.show', $idea)
            ->with('status', 'Comment added.');
    }

    /**
     * Update a comment.
     */
    public function update(Request $request, Idea $idea, Comment $comment)
    {
        $this->authorize('update', $comment);

        $dataBefore = json_encode($comment->toArray());

        $comment->update([
            'description' => $request->input('description'),
        ]);

        $this->logService->log(
            userId: Auth::id(),
            action: 'comment_updated',
            ideaId: $idea->id,
            commentId: $comment->id,
            dataBefore: $dataBefore,
            dataAfter: json_encode($comment->fresh()->toArray()),
            request: $request,
        );

        return redirect()
            ->route('ideas.show', $idea)
            ->with('status', 'Comment updated.');
    }

    /**
     * Remove a comment.
     * Seul l'auteur du commentaire ou un admin peut supprimer (via CommentPolicy).
     */
    public function destroy(Idea $idea, Comment $comment)
    {
        $this->authorize('delete', $comment);

        $this->logService->log(
            userId: Auth::id(),
            action: 'comment_deleted',
            ideaId: $idea->id,
            commentId: $comment->id,
            dataBefore: json_encode($comment->toArray()),
            request: request(),
        );

        $comment->delete();

        return redirect()
            ->route('ideas.show', $idea)
            ->with('status', 'Comment deleted.');
    }
}
