<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;

class ContentModerator
{
    private const API_URL = 'https://www.purgomalum.com/service/containsprofanity';

    public function __construct(
        private HttpClientInterface $httpClient,
        private LoggerInterface $logger
    ) {}

    public function isProfane(string $text): bool
    {
        if (empty($text)) {
            return false;
        }

        try {
            $response = $this->httpClient->request('GET', self::API_URL, [
                'query' => ['text' => $text]
            ]);
            return $response->getContent() === 'true';

        } catch (\Exception $e) {
            $this->logger->error('PurgoMalum API error: ' . $e->getMessage());
            return false;
        }
    }
}
