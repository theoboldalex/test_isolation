<?php


namespace Tests\Unit;

use App\SendMail;
use ClickSend\Api\PostLetterApi;
use ClickSend\Model\PostLetter;
use ClickSend\Model\PostRecipient;
use Exception;
use Generator;
use PHPUnit\Framework\TestCase;

use function file_get_contents;
use function json_encode;

class SendMailTest extends TestCase
{
    /**
     * @param  string[] $recipientData
     * @param  string[] $expected
     *
     * @throws Exception
     *
     * @dataProvider letterDataProvider
     */
    public function testSendingALetter(
        array $recipientData,
        string $fileUrl,
        array $expected,
    ): void {
        // Arrange
        $apiMock       = $this->createMock(PostLetterApi::class);
        $recipientMock = $this->createMock(PostRecipient::class);
        $letterMock    = $this->createMock(PostLetter::class);

        $recipientMock->expects($this->once())
            ->method('setAddressName')
            ->with($recipientData['address_name']);

        $recipientMock->expects($this->once())
            ->method('setAddressLine1')
            ->with($recipientData['address_line_one']);

        $recipientMock->expects($this->once())
            ->method('setAddressLine2')
            ->with($recipientData['address_line_two']);

        $recipientMock->expects($this->once())
            ->method('setAddressCity')
            ->with($recipientData['address_city']);

        $recipientMock->expects($this->once())
            ->method('setAddressState')
            ->with($recipientData['address_state']);

        $recipientMock->expects($this->once())
            ->method('setAddressPostalCode')
            ->with($recipientData['address_postal_code']);

        $recipientMock->expects($this->once())
            ->method('setAddressCountry')
            ->with($recipientData['address_country']);

        $letterMock->expects($this->once())
            ->method('setFileUrl')
            ->with($fileUrl);

        $letterMock->expects($this->once())
            ->method('setRecipients')
            ->with([$recipientMock]);

        $apiMock->expects($this->once())
            ->method('postLettersSendPost')
            ->willReturn($this->getMockResponse());

        // $sut = System Under Test aka the class/module we are isolating as a unit
        $sut = new SendMail(
            $apiMock,
            $recipientMock,
            $letterMock,
        );

        // Act
        $sut->setRecipient($recipientData);
        $sut->attachLetter($fileUrl);
        $result = $sut->sendLetter();

        // Assert
        $this->assertJson($result, json_encode($expected));
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
                'http_code' => '200',
                'response_code' => 'SUCCESS',
                'response_msg' => 'Letters queued for delivery.',
            ],
        ];
    }

    private function getMockResponse(): bool|string
    {
        return file_get_contents(__DIR__ . '/fixtures/responses/send_letter_response.json');
    }
}
