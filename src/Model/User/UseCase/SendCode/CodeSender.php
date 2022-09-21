<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SendCode;

use GuzzleHttp\ClientInterface;

class CodeSender
{
    public function __construct(
        private ClientInterface $client,
        private string $host,
        private string $apiToken,
    ) {
    }

    public function send(string $code, string $phone): void
    {
        $this->client->request(
            method: 'get',
            uri: "{$this->host}/sms/send",
            options: [
                'query' => [
                    'api_id' => $this->apiToken,
                    'to' => $phone,
                    'msg' => $code,
                    'json' => true,
                ]
            ]
        );
    }
}
