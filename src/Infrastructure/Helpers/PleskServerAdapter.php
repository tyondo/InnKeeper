<?php

namespace Tyondo\Innkeeper\Infrastructure\Helpers;
use PleskX\Api\Client;
use PleskX\Api\Operator\Database;
use PleskX\Api\Struct\Database as Struct;

class PleskServerAdapter
{
    protected Client $pleskClient;

    public function __construct()
    {
        $username = config('innkeeper.connections.innkeeper.plesk.user');
        $password = config('innkeeper.connections.innkeeper.plesk.password');
        $host = config('innkeeper.connections.innkeeper.plesk.host');

        $client = new Client($host);
        $client->setCredentials($username, $password);

        $this->pleskClient = $client;

       // parent::__construct($this);
    }

    public function getApiClient(): Client
    {
        return $this->pleskClient;
    }

}
