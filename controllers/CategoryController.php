<?php

namespace App\Controllers;

use App\Providers\View;
use App\Providers\Validator;
use App\Providers\Auth;
use App\Providers\SecurityMiddleware;
use App\Models\Category;
use App\Models\Post;

class CategoryController
{
    /**
     * Vérification des privilèges pour les opérations CRUD
     */
    private function requireCrudPrivileges()
    {
        Auth::requireAuth();
        Auth::privilege(2); // Modérateur ou Admin
    }

    public function index()
    {
        // Vue des catégories: Connexion Requise
        Auth::requireAuth();

        $category = new Category();
        $categories = $category->select();
        return View::render('category/index', ['categories' => $categories]);
    }

    public function show($data)
    {
        // Vue des catégories: Connexion Requise
        Auth::requireAuth();

        if (isset($data['id']) && $data['id'] != null) {
            $category = new Category();
            $selectedCategory = $category->selectId($data['id']);

            if ($selectedCategory) {
                $post = new Post();
                $posts = $this->getPostsByCategory($post, $data['id']);
                return View::render('category/show', [
                    'category' => $selectedCategory,
                    'posts' => $posts
                ]);
            } else {
                return View::render('error', ['message' => 'Category not found!']);
            }
        } else {
            return View::render('error', ['message' => '404 not found!']);
        }
    }

    public function create()
    {
        // CRUD des catégories: Modérateur et Admin
        $this->requireCrudPrivileges();

        return View::render('category/create');
    }

    public function store($data)
    {
        // CRUD des catégories: Modérateur et Admin
        $this->requireCrudPrivileges();

        $validator = new Validator();
        $validator->field('name', $data['name'])->min(2)->max(100)->required();

        if ($validator->isSuccess()) {
            $category = new Category();
            $insertCategory = $category->insert($data);
            if ($insertCategory) {
                SecurityMiddleware::logCrudOperation('création', 'catégorie', $insertCategory);
                return View::redirect('categories');
            } else {
                return View::render('error', ['message' => 'Could not create category!']);
            }
        } else {
            $errors = $validator->getErrors();
            return View::render('category/create', ['errors' => $errors, 'category' => $data]);
        }
    }

    public function edit($data)
    {
        // CRUD des catégories: Modérateur et Admin
        $this->requireCrudPrivileges();

        if (isset($data['id']) && $data['id'] != null) {
            $category = new Category();
            $selectedCategory = $category->selectId($data['id']);
            if ($selectedCategory) {
                return View::render('category/edit', ['category' => $selectedCategory]);
            } else {
                return View::render('error', ['message' => 'Category not found!']);
            }
        } else {
            return View::render('error', ['message' => '404 not found!']);
        }
    }

    public function update($data, $params)
    {
        // CRUD des catégories: Modérateur et Admin
        $this->requireCrudPrivileges();

        if (isset($params['id']) && $params['id'] != null) {
            $validator = new Validator();
            $validator->field('name', $data['name'])->min(2)->max(100)->required();

            if ($validator->isSuccess()) {
                $category = new Category();
                $updateCategory = $category->update($data, $params['id']);
                if ($updateCategory) {
                    SecurityMiddleware::logCrudOperation('modification', 'catégorie', $params['id']);
                    return View::redirect('categories');
                } else {
                    return View::render('error', ['message' => 'Could not update category!']);
                }
            } else {
                $errors = $validator->getErrors();
                $category = new Category();
                $selectedCategory = $category->selectId($params['id']);
                return View::render('category/edit', ['errors' => $errors, 'category' => array_merge($selectedCategory, $data)]);
            }
        } else {
            return View::render('error', ['message' => '404 not found!']);
        }
    }

    public function delete($data)
    {
        // CRUD des catégories: Modérateur et Admin
        $this->requireCrudPrivileges();

        if (isset($data['id']) && $data['id'] != null) {
            $category = new Category();
            $deleteCategory = $category->delete($data['id']);
            if ($deleteCategory) {
                SecurityMiddleware::logCrudOperation('suppression', 'catégorie', $data['id']);
                return View::redirect('categories');
            } else {
                return View::render('error', ['message' => 'Could not delete category!']);
            }
        } else {
            return View::render('error', ['message' => '404 not found!']);
        }
    }

    private function getPostsByCategory($post, $categoryId)
    {
        $sql = "SELECT p.*, c.name as category_name, u.username as author_name 
                FROM posts p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN users u ON p.user_id = u.id 
                WHERE p.category_id = :category_id 
                ORDER BY p.created_at DESC";

        $stmt = $post->prepare($sql);
        $stmt->bindValue(":category_id", $categoryId);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
