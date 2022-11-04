<?php

declare(strict_types=1);

namespace App;

use ClickSend\Api\PostLetterApi;
use ClickSend\Configuration;
use ClickSend\Model\PostLetter;
use ClickSend\Model\PostRecipient;
use Dotenv\Dotenv;
use Exception;
use GuzzleHttp\Client;

class SendMail
{
    private Configuration $config;
    private PostLetterApi $apiInstance;

    public function __construct(
        private PostRecipient $postRecipient,
        private PostLetter $postLetter,
    ) {
        // Some required setup. This wouldn't usually live here.
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        $this->config = Configuration::getDefaultConfiguration()
             ->setUsername($_ENV['CLICKSEND_KEY'])
             ->setPassword($_ENV['CLICKSEND_SECRET']);

        $this->initApiInstance();
    }

    private function initApiInstance(): void
    {
        $this->apiInstance = new PostLetterApi(new Client(), $this->config);
    }

    /** @param string[] $recipient */
    public function setRecipient(array $recipient): void
    {
        throw new Exception(__FUNCTION__ . ' not implemented yet');
    }

    public function attachLetter(string $fileUrl): void
    {
        throw new Exception(__FUNCTION__ . ' not implemented yet');
    }

    public function sendLetter(): void
    {
        throw new Exception(__FUNCTION__ . ' not implemented yet');
    }
}
