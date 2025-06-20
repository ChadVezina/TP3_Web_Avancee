<?php

namespace App\Controllers;

use App\Providers\View;
use App\Providers\Validator;
use App\Providers\Auth;
use App\Models\Comment;

class CommentController
{
    public function store($data)
    {
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

            if ($selectedComment && $selectedComment['user_id'] == Auth::id()) {
                $deleteComment = $comment->delete($data['id']);

                if ($deleteComment) {
                    return View::redirect('post/show?id=' . $data['post_id']);
                } else {
                    return View::render('error', ['message' => 'Could not delete comment!']);
                }
            } else {
                return View::render('error', ['message' => 'Comment not found or unauthorized!']);
            }
        } else {
            return View::render('error', ['message' => 'Invalid request!']);
        }
    }
}
