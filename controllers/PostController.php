<?php

namespace App\Controllers;

use App\Providers\View;
use App\Providers\Validator;
use App\Providers\Auth;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;

class PostController
{

    public function construct(){
        Auth::requireAuth();
        Auth::privilege(3);
    }

    public function index()
    {
        $post = new Post();
        $posts = $post->selectAllWithRelations('id', 'desc');
        return View::render('post/index', ['posts' => $posts]);
    }

    public function show($data)
    {
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
        self::construct();
        $category = new Category();
        $categories = $category->select();
        return View::render('post/create', ['categories' => $categories]);
    }

    public function store($data)
    {
        self::construct();
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
        self::construct();
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
        self::construct();
        if (isset($params['id']) && $params['id'] != null) {
            $validator = new Validator();
            $validator->field('title', $data['title'])->min(2)->max(255)->required();
            $validator->field('content', $data['content'])->min(10)->required();
            $validator->field('category_id', $data['category_id'], 'Category')->required();

            if ($validator->isSuccess()) {
                $post = new Post();
                $updatePost = $post->update($data, $params['id']);
                if ($updatePost) {
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
        if (Auth::has_privilege(2)) {
            if (isset($data['id']) && $data['id'] != null) {
                $comment = new Comment();
                $this->deleteCommentsByPostId($comment, $data['id']);

                $post = new Post();
                $deletePost = $post->delete($data['id']);
                if ($deletePost) {
                    return View::redirect('posts');
                } else {
                    return View::render('error', ['message' => 'Could not delete post!']);
                }
            } else {
                return View::render('error', ['message' => '404 not found!']);
            }
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