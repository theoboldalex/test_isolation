<?php

declare(strict_types=1);

namespace App;

use ClickSend\Model\PostLetter;
use ClickSend\Model\PostRecipient;
use Exception;

final class Letter
{
    public function __construct(
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
        $this->postRecipient->setAddressLine1($recipient['address_line_1']);
        $this->postRecipient->setAddressLine2($recipient['address_line_2']);
        $this->postRecipient->setAddressCity($recipient['address_city']);
        $this->postRecipient->setAddressState($recipient['address_state']);
        $this->postRecipient->setAddressPostalCode($recipient['address_postal_code']);
        $this->postRecipient->setAddressCountry($recipient['address_country']);
        $this->postLetter->setRecipients([$this->postRecipient]);
    }

    public function attachLetter(string $fileUrl): void
    {
        $this->postLetter->setFileUrl($fileUrl);
    }

    public function getLetter(): PostLetter
    {
        return $this->postLetter;
    }

    /** @return string[] */
    public function getRecipients(): array
    {
        return $this->postLetter->getRecipients();
    }

    /** @param string[] $recipient */
    private function validateRecipientFields(array $recipient): bool
    {
        return true;
    }
}
