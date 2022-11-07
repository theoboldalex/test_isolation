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
        if (! $this->validateRecipientFields($recipient)) {
            throw new Exception('Invalid fields in recipient.');
        }

        $this->postRecipient->setAddressName($recipient['address_name']);
        $this->postRecipient->setAddressLine1($recipient['address_line_one']);
        $this->postRecipient->setAddressLine2($recipient['address_line_two']);
        $this->postRecipient->setAddressCity($recipient['address_city']);
        $this->postRecipient->setAddressState($recipient['address_state']);
        $this->postRecipient->setAddressPostalCode($recipient['address_postal_code']);
        $this->postRecipient->setAddressCountry($recipient['address_country']);
    }

    public function attachLetter(string $fileUrl): void
    {
        $this->postLetter->setFileUrl($fileUrl);
        $this->postLetter->setRecipients([$this->postRecipient]);
    }

    public function sendLetter(): string
    {
        return $this->apiInstance->postLettersSendPost($this->postLetter);
    }

    private function validateRecipientFields(array $recipient): bool
    {
        return true;
    }
}
