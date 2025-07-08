<?php
namespace App\Models;
use App\Models\CRUD;

class Privilege extends CRUD {
    protected $table = "privileges";
    protected $primaryKey = "id";
    protected $fillable = ['privilege'];
}