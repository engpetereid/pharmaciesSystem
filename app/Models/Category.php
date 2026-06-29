<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Drug;

class Category extends Model
{
    //

    use HasFactory;
    protected $table = 'categories';
    protected $fillable =[
        'id',
        'name'
    ];

    public function drugs()
    {
        return $this->hasMany(Drug::class);
    }

    public $timestamps = false;
}
