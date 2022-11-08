<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\SendMail;
use ClickSend\Api\PostLetterApi;
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
        $apiMock = $this->createMock(PostLetterApi::class);
        $recipientMock = $this->createMock(PostRecipient::class);
        $letterMock = $this->createMock(PostLetter::class);

        $apiMock->expects($this->once())
            ->method('postLettersSendPost')
            ->willReturn($this->getMockResponse());

        // $sut = System Under Test aka the class/module we are isolating as a unit
        $sut = new SendMail(
            $apiMock,
            $recipientMock, 
            $letterMock,
        );

        $sut->setRecipient($recipientData);
        $sut->attachLetter($fileUrl);

        // Act
        $result = json_decode($sut->sendLetter(), true);

        // Assert
        foreach ($expected as $value) {
            $this->assertContains($value, $result);
        }
    }

    public function letterDataProvider(): Generator
    {
        yield 'send a letter to santa claus' => [
            [
                'address_name' => 'Santa Claus',
                'address_line_one' => '1 Lapland Way',
                'address_line_two' => 'The North Pole',
                'address_city' => 'Smorgasborg',
                'address_state' => 'Phpville',
                'address_postal_code' => '11111',
                'address_country' => 'Greenland',
            ],
            'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
            [
                'http_code' => 200,
                'response_code' => 'SUCCESS',
                'response_msg' => 'Letters queued for delivery.',
            ]
        ];
    }

    private function getMockResponse()
    {
        return file_get_contents(__DIR__ . '/fixtures/responses/send_letter_response.json');
    }
}

