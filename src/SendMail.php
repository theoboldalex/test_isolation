<?php

declare(strict_types=1);

namespace App;

use ClickSend\Api\PostLetterApi;
use ClickSend\ApiException;
use ClickSend\Model\PostLetter;
use ClickSend\Model\PostRecipient;
use Exception;

class SendMail
{
    public function __construct(
        private readonly PostLetterApi $apiInstance,
        private readonly PostRecipient $postRecipient = new PostRecipient(),
        private readonly PostLetter $postLetter = new PostLetter(),
    ) {
    }

    /**
     * @param string[] $recipient
     *
     * @throws Exception
     */
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

    /** @throws ApiException */
    public function sendLetter(): string
    {
        return $this->apiInstance->postLettersSendPost($this->postLetter);
    }

    /** @param string[] $recipient */
    private function validateRecipientFields(array $recipient): bool
    {
        return true;
    }
}
