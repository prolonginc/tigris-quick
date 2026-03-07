<?php

namespace App\QuickBooks;

trait HasQuickBooksToken
{
    public function quickBooksToken()
    {
        return $this->hasOne(Token::class);
    }
}
