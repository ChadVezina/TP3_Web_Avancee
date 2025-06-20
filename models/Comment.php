<?php

namespace App\Models;

use App\Models\CRUD;

class Comment extends CRUD
{
    protected $table = "comments";
    protected $primaryKey = "id";
    protected $fillable = ['content', 'post_id', 'user_id', 'created_at'];

    public function selectByPostWithUser($postId)
    {
        $sql = "SELECT c.*, u.username as author_name 
                FROM comments c 
                LEFT JOIN users u ON c.user_id = u.id 
                WHERE c.post_id = :post_id 
                ORDER BY c.created_at ASC";

        $stmt = $this->prepare($sql);
        $stmt->bindValue(":post_id", $postId);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
