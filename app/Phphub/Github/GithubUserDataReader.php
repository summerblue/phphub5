<?php namespace Phphub\Github;

use GuzzleHttp\Client;

class GithubUserDataReader
{
    public function getDataFromUserName($username)
    {
        $client = new Client([
            'base_uri' => 'https://api.github.com/users/',
        ]);

        $query['client_id'] = config('services.github.client_id');
        $query['client_secret'] = config('services.github.client_secret');

        $data = $client->get($username, $query)->getBody()->getContents();
        return json_decode($data, true);
    }
}
