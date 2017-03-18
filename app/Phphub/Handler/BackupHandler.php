<?php

namespace Phphub\Handler;

use Spatie\Backup\Notifications\BaseSender;
use GuzzleHttp\Client;

class BackupHandler extends BaseSender
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function send()
    {
        $data                   = [];
        $data['text']           = $this->subject;
        $data['color']          = '#E65128';
        $data['attachments'][]  = ['text' => $this->message];

        $this->client->request('POST', config('services.bearychat.hook'), [
            'form_params' => ['payload' => json_encode($data)]
        ]);
    }
}
