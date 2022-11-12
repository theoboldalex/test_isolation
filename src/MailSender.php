<?php

declare(strict_types=1);

namespace App;

use ClickSend\Api\PostLetterApi;
use ClickSend\ApiException;

final class MailSender
{
    public function __construct(private readonly PostLetterApi $apiInstance)
    {
    }

    /** @throws ApiException */
    public function sendLetter(Letter $letter): string
    {
        return $this->apiInstance->postLettersSendPost($letter->getLetter());
    }
}
