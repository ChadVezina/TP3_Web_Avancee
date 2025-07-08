<?php

namespace App\Controllers;

use App\Providers\View;
use App\Providers\Validator;
use App\Providers\Auth;
use App\Providers\SecurityMiddleware;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;

class PostController
{
    /**
     * Vérification des privilèges pour les opérations CRUD
     */
    private function requireCrudPrivileges()
    {
        Auth::requireAuth();
        Auth::privilege(3); // Utilisateur, Modérateur ou Admin
    }

    /**
     * Vérification des privilèges pour les opérations de modération (edit/delete)
     */
    private function requireModerationPrivileges()
    {
        Auth::requireAuth();
        Auth::privilege(2); // Modérateur ou Admin seulement
    }

    public function index()
    {
        // Vue publique - aucune connexion requise
        $post = new Post();
        $posts = $post->selectAllWithRelations('id', 'desc');
        return View::render('post/index', ['posts' => $posts]);
    }

    public function show($data)
    {
        // Vue publique - aucune connexion requise
        if (isset($data['id']) && $data['id'] != null) {
            $post = new Post();
            $selectedPost = $post->selectWithRelations($data['id']);
            if ($selectedPost) {
                $comment = new Comment();
                $comments = $comment->selectByPostWithUser($data['id']);

                return View::render('post/show', [
                    'post' => $selectedPost,
                    'comments' => $comments
                ]);
            } else {
                return View::render('error', ['message' => 'Post not found!']);
            }
        } else {
            return View::render('error', ['message' => '404 not found!']);
        }
    }

    public function create()
    {
        // Création - Tous les utilisateurs connectés
        $this->requireCrudPrivileges();

        $category = new Category();
        $categories = $category->select();
        return View::render('post/create', ['categories' => $categories]);
    }

    public function store($data)
    {
        // Création - Tous les utilisateurs connectés
        $this->requireCrudPrivileges();

        $validator = new Validator();
        $validator->field('title', $data['title'])->min(2)->max(255)->required();
        $validator->field('content', $data['content'])->min(10)->required();
        $validator->field('category_id', $data['category_id'], 'Category')->required();

        if ($validator->isSuccess()) {
            $post = new Post();
            $data['user_id'] = Auth::id();
            date_default_timezone_set('America/New_York');
            $data['created_at'] = date('Y-m-d H:i:s');
            $insertPost = $post->insert($data);
            if ($insertPost) {
                SecurityMiddleware::logCrudOperation('création', 'post', $insertPost);
                return View::redirect('post/show?id=' . $insertPost);
            } else {
                return View::render('error', ['message' => 'Could not create post!']);
            }
        } else {
            $errors = $validator->getErrors();
            $category = new Category();
            $categories = $category->select();
            return View::render('post/create', ['errors' => $errors, 'post' => $data, 'categories' => $categories]);
        }
    }

    public function edit($data)
    {
        // Modification - Modérateur et Admin seulement
        $this->requireModerationPrivileges();

        if (isset($data['id']) && $data['id'] != null) {
            $post = new Post();
            $selectedPost = $post->selectId($data['id']);
            if ($selectedPost) {
                $category = new Category();
                $categories = $category->select();
                return View::render('post/edit', ['post' => $selectedPost, 'categories' => $categories]);
            } else {
                return View::render('error', ['message' => 'Post not found!']);
            }
        } else {
            return View::render('error', ['message' => '404 not found!']);
        }
    }

    public function update($data, $params)
    {
        // Modification - Modérateur et Admin seulement
        $this->requireModerationPrivileges();

        if (isset($params['id']) && $params['id'] != null) {
            $validator = new Validator();
            $validator->field('title', $data['title'])->min(2)->max(255)->required();
            $validator->field('content', $data['content'])->min(10)->required();
            $validator->field('category_id', $data['category_id'], 'Category')->required();

            if ($validator->isSuccess()) {
                $post = new Post();
                $updatePost = $post->update($data, $params['id']);
                if ($updatePost) {
                    SecurityMiddleware::logCrudOperation('modification', 'post', $params['id']);
                    return View::redirect('post/show?id=' . $params['id']);
                } else {
                    return View::render('error', ['message' => 'Could not update post!']);
                }
            } else {
                $errors = $validator->getErrors();
                $post = new Post();
                $selectedPost = $post->selectId($params['id']);
                $category = new Category();
                $categories = $category->select();
                return View::render('post/edit', ['errors' => $errors, 'post' => array_merge($selectedPost, $data), 'categories' => $categories]);
            }
        } else {
            return View::render('error', ['message' => '404 not found!']);
        }
    }

    public function delete($data)
    {
        // Suppression - Modérateur et Admin seulement
        $this->requireModerationPrivileges();

        if (isset($data['id']) && $data['id'] != null) {
            $post = new Post();
            $comment = new Comment();

            // Delete associated comments first
            $this->deleteCommentsByPostId($comment, $data['id']);

            // Delete the post
            $deletePost = $post->delete($data['id']);
            if ($deletePost) {
                SecurityMiddleware::logCrudOperation('suppression', 'post', $data['id']);
                return View::redirect('posts');
            } else {
                return View::render('error', ['message' => 'Could not delete post!']);
            }
        } else {
            return View::render('error', ['message' => '404 not found!']);
        }
    }

    private function deleteCommentsByPostId($comment, $postId)
    {
        $sql = "DELETE FROM comments WHERE post_id = :post_id";
        $stmt = $comment->prepare($sql);
        $stmt->bindValue(":post_id", $postId);
        $stmt->execute();
    }
}
