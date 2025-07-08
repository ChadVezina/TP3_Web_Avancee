<?php

namespace App\Controllers;

use App\Providers\View;
use App\Providers\Validator;
use App\Providers\Auth;
use App\Providers\SecurityMiddleware;
use App\Models\Comment;

class CommentController
{
    public function store($data)
    {
        // Commentaires: Connexion Requise
        Auth::requireAuth();

        $validator = new Validator();
        $validator->field('content', $data['content'])->min(3)->max(1000)->required();
        $validator->field('post_id', $data['post_id'])->required();

        if ($validator->isSuccess()) {
            $comment = new Comment();
            $data['user_id'] = Auth::id();
            date_default_timezone_set('America/New_York');
            $data['created_at'] = date('Y-m-d H:i:s');
            $insertComment = $comment->insert($data);
            if ($insertComment) {
                SecurityMiddleware::logCrudOperation('création', 'commentaire', $insertComment);
                return View::redirect('post/show?id=' . $data['post_id']);
            } else {
                return View::render('error', ['message' => 'Could not create comment!']);
            }
        } else {
            return View::redirect('post/show?id=' . $data['post_id']);
        }
    }

    public function delete($data)
    {
        Auth::requireAuth();

        if (isset($data['id']) && isset($data['post_id'])) {
            $comment = new Comment();
            $selectedComment = $comment->selectId($data['id']);

            // Allow deletion if user owns the comment OR has moderator/admin privileges
            if (
                $selectedComment &&
                ($selectedComment['user_id'] == Auth::id() || Auth::has_privilege(2))
            ) {
                $deleteComment = $comment->delete($data['id']);

                if ($deleteComment) {
                    SecurityMiddleware::logCrudOperation('suppression', 'commentaire', $data['id']);
                    return View::redirect('post/show?id=' . $data['post_id']);
                } else {
                    return View::render('error', ['message' => 'Could not delete comment!']);
                }
            } else {
                return View::render('error', ['message' => 'Unauthorized to delete this comment!']);
            }
        } else {
            return View::render('error', ['message' => '404 not found!']);
        }
    }
}
