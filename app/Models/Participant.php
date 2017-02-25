<?php

namespace App\Models;

use Cmgmyr\Messenger\Models\Participant as MessengerParticipant;

class Participant extends MessengerParticipant
{

    public function scopeByWhom($query, $user_id)
    {
        return $query->where('user_id', '=', $user_id);
    }

}
