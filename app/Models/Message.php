<?php

namespace App\Models;

use Cmgmyr\Messenger\Models\Message as MessengerMessage;

class Message extends MessengerMessage
{
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
