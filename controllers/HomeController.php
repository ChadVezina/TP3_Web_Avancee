<?php

namespace App\Controllers;

use App\Providers\View;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;

class HomeController
{

    public function index()
    {
        $postModel = new Post();
        $categoryModel = new Category();
        $userModel = new User();

        // Récupérer les 6 derniers posts avec leurs relations
        $recentPosts = $postModel->selectAllWithRelations('created_at', 'desc');
        $recentPosts = array_slice($recentPosts, 0, 6);

        // Statistiques du site
        $totalPosts = count($postModel->select());
        $totalCategories = count($categoryModel->select());
        $totalUsers = count($userModel->select());

        // Récupérer toutes les catégories pour la section navigation
        $categories = $categoryModel->select();

        $data = [
            'recentPosts' => $recentPosts,
            'totalPosts' => $totalPosts,
            'totalCategories' => $totalCategories,
            'totalUsers' => $totalUsers,
            'categories' => $categories
        ];

        return View::render('home', $data);
    }
}
