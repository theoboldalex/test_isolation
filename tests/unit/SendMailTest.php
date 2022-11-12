<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Letter;
use App\MailSender;
use ClickSend\Api\PostLetterApi;
use ClickSend\Model\PostRecipient;
use Exception;
use Generator;
use PHPUnit\Framework\TestCase;

use function file_get_contents;
use function json_decode;

class SendMailTest extends TestCase
{
    /**
     * @param  string[]        $recipient
     * @param  PostRecipient[] $result
     *
     * @throws Exception
     *
     * @dataProvider letterDataProvider
     */
    public function testLetterCanHaveARecipient(
        array $recipient,
        array $result,
    ): void {
        $sut = new Letter();

        $sut->setRecipient($recipient);

        $this->assertEquals($result, $sut->getRecipients());
    }

    public function testCanAttachAFileToALetter(): void
    {
        $sut     = new Letter();
        $fileUrl = 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf';

        $sut->attachLetter($fileUrl);

        $this->assertSame($fileUrl, $sut->getLetter()->getFileUrl());
    }

    /** @throws Exception */
    public function testSendingALetter(): void
    {
        $apiMock = $this->createMock(PostLetterApi::class);
        $apiMock->expects($this->once())
            ->method('postLettersSendPost')
            ->willReturn($this->getMockResponse());

        $sut = new MailSender($apiMock);

        $expected = [
            'http_code' => 200,
            'response_code' => 'SUCCESS',
            'response_msg' => 'Letters queued for delivery.',
        ];

        $result = json_decode($sut->sendLetter(new Letter()), true);

        foreach ($expected as $key => $value) {
            $this->assertSame($value, $result[$key]);
        }
    }

    public function letterDataProvider(): Generator
    {
        yield 'send a letter to santa claus' => [
            [
                'address_name' => 'Santa Claus',
                'address_line_1' => '1 Lapland Way',
                'address_line_2' => 'The North Pole',
                'address_city' => 'Smorgasborg',
                'address_state' => 'Phpville',
                'address_postal_code' => '11111',
                'address_country' => 'Greenland',
            ],
            [
                new PostRecipient([
                    'address_name' => 'Santa Claus',
                    'address_line_1' => '1 Lapland Way',
                    'address_line_2' => 'The North Pole',
                    'address_city' => 'Smorgasborg',
                    'address_state' => 'Phpville',
                    'address_postal_code' => '11111',
                    'address_country' => 'Greenland',
                    'return_address_id' => null,
                    'schedule' => 0,
                    0 => 'PostRecipient',
                ]),
            ],
        ];
    }

    private function getMockResponse(): bool|string
    {
        return file_get_contents(__DIR__ . '/fixtures/responses/send_letter_response.json');
    }
}
