<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class SendMailTest extends TestCase
{
    public function testSendingALetter(): void
    {
        // Arrange
        $config = \ClickSend\Configuration::getDefaultConfiguration()
              ->setUsername($_ENV['CLICKSEND_KEY'])
              ->setPassword($_ENV['CLICKSEND_SECRET']);

        // Act

        // Assert
    }
}

