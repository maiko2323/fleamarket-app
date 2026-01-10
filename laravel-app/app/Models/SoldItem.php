<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Item;

class SoldItem extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'item_id',
        'buyer_id',
        'payment_method',
        'post_code',
        'address',
        'building_name',
        'sold_at',
    ];


    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}

