<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\SendMail;
use ClickSend\Model\PostLetter;
use ClickSend\Model\PostRecipient;
use Generator;

class SendMailTest extends TestCase
{
    const LETTER_PATH = __DIR__ . '/../fixtures/letters/';

    /** @dataProvider letterDataProvider */
    public function testSendingALetter(
        array $recipientData,
        string $fileUrl,
        array $expected,
    ): void {
        // Arrange
        $recipientMock = $this->createMock(PostRecipient::class);
        $letterMock = $this->createMock(PostLetter::class);

        // $sut = System Under Test aka the class/module we are isolating as a unit
        $sut = new SendMail(
            $recipientMock, 
            $letterMock,
        );

        $sut->setRecipient($recipientData);
        $sut->attachLetter($fileUrl);

        // Act
        $result = $sut->sendLetter();

        // Assert
        $this->assertSame($expected, $result);
    }

    public function letterDataProvider(): Generator
    {
        yield 'send a letter to santa claus' => [
            [
                'address_name' => 'Santa Claus'
            ],
            self::LETTER_PATH . 'santa.pdf',
            []
        ];
    }
}

