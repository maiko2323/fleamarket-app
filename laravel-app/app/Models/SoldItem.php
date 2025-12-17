<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoldItem extends Model
{
    use HasFactory;

    public function buyer()
{
    return $this->belongsTo(User::class, 'buyer_id');
}

public function item()
{
    return $this->belongsTo(Item::class);
}
}
