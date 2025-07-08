<?php

namespace App\Models;

use App\Models\CRUD;

class Post extends CRUD
{
    protected $table = "posts";
    protected $primaryKey = "id";
    protected $fillable = ['title', 'content', 'category_id', 'user_id', 'created_at'];

    public function selectWithRelations($id)
    {
        $sql = "SELECT p.*, c.name as category_name, u.username as author_name 
                FROM posts p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN users u ON p.user_id = u.id 
                WHERE p.id = :id";

        $stmt = $this->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $count = $stmt->rowCount();

        if ($count == 1) {
            return $stmt->fetch();
        } else {
            return false;
        }
    }

    public function selectAllWithRelations($field = 'id', $order = 'desc')
    {
        $sql = "SELECT p.*, c.name as category_name, u.username as author_name 
                FROM posts p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN users u ON p.user_id = u.id 
                ORDER BY p.$field $order";

        if ($stmt = $this->query($sql)) {
            return $stmt->fetchAll();
        } else {
            return false;
        }
    }
}