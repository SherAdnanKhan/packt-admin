<?php

namespace App\Services\Api;

use GuzzleHttp\Client;

abstract class HttpService
{
    protected $uri;
    /**@var Client*/
    protected $client;

    public function __construct(string $uri = "", array $config = [])
    {
        $this->uri = $uri;
        $this->client = new Client();
        $this->parseConfig($config);
    }

    protected function parseConfig(array $config)
    {
    }

    protected function requestGET(string $uri = '', array $options = [])
    {
        return $this->client->request('GET', $this->uri . $uri, array_merge_recursive($this->getOptions(), $options));
    }

    protected function requestPOST(string $uri, array $data = [])
    {
        return $this->client->request(
            'POST',
            $this->uri . $uri,
            array_merge_recursive($this->getOptions(), [
                'json' => $data,
            ])
        );
    }

    protected function requestPUT(string $uri, array $data = [])
    {
        return $this->client->request(
            'PUT',
            $this->uri . $uri,
            array_merge_recursive($this->getOptions(), [
                'json' => $data,
            ])
        );
    }

    protected function requestPATCH(string $uri, array $data = [])
    {
        return $this->client->request(
            'PATCH',
            $this->uri . $uri,
            array_merge_recursive($this->getOptions(), [
                'json' => $data,
            ])
        );
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }
}
