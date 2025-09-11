<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Cho phép fill tất cả các cột (trừ id auto increment)
    protected $guarded = [];

    // Nếu muốn bảo mật hơn có thể khai báo cụ thể
    // protected $fillable = ['name', 'code', 'parent_id'];

    /**
     * Quan hệ: Category con của một Category cha
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Quan hệ: Category cha có nhiều Category con
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
