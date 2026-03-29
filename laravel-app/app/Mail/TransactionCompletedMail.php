<?php

namespace App\Mail;

use App\Models\SoldItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransactionCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $soldItem;
    public function __construct(SoldItem $soldItem)
    {
        $this->soldItem = $soldItem;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('【COACHTECHフリマ】取引完了のお知らせ')
            ->view('transactions.transaction_completed')
            > with([
                'soldItem' => $this->soldItem,
            ]);
    }
}
