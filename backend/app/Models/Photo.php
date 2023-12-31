<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;
    public $table = "photos";
    public $primaryKey = "id";

    protected $guarded = ["id"];
}
