<?php

namespace App\Models;

use App\Models\SoldItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionChat extends Model
{
    use HasFactory;

    protected $primaryKey = 'transaction_chats_id';

    protected $fillable = [
        'sold_item_id',
        'user_id',
        'message',
        'chat_img',
        'read_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function soldItem()
    {
        return $this->belongsTo(SoldItem::class);
    }
}
