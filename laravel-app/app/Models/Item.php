<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\SoldItem;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Condition;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand',
        'item_img',
        'price',
        'description',
        'user_id',
        'category_id',
        'condition_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes', 'item_id', 'user_id');
    }

    public function soldItem()
    {
        return $this->hasOne(SoldItem::class);
    }

    public function getIsSoldAttribute()
    {
        return $this->soldItem()->exists();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_items');
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

}


