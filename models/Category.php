<?php

namespace App\Models;

use App\Models\CRUD;

class Category extends CRUD
{
    protected $table = "categories";
    protected $primaryKey = "id";
    protected $fillable = ['name'];
}
