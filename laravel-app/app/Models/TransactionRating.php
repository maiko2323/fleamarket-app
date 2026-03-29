<?php

namespace App\Models;

use App\Models\SoldItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionRating extends Model
{
    use HasFactory;

    protected $primaryKey = 'transaction_ratings_id';

    protected $fillable = [
        'sold_item_id',
        'rater_id',
        'rated_user_id',
        'score',
    ];

    public function soldItem()
    {
        return $this->belongsTo(SoldItem::class, 'sold_item_id');
    }

    public function rater()
    {
        return $this->belongsTo(User::class, 'rater_id');
    }

    public function ratedUser()
    {
        return $this->belongsTo(User::class, 'rated_user_id');
    }

}

