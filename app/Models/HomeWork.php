<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeWork extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'date', 'code', 'filePath', 'imgUrl'];

}
