<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\VerificationRequest;
use App\Helpers\Helper;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadTest extends ApiTestCase
{
    /**
     * Testing the File Upload section of the Verification request Api endpoints
     *
     * @return void
     */
    public function testCreateAMediaObject(): void
    {
        $helper = new Helper();
        $file = new UploadedFile('fixtures/files/632ba96c930d7_Screenshot from 2021-09-14 13-59-52.png', '632ba96c930d7_Screenshot from 2021-09-14 13-59-52.png');
        $client = self::createClient();

        $client->request('POST', '/api/verification_requests', [
            'headers' => ['Content-Type' => 'multipart/form-data'],
            'extra' => [
                    'parameters' => [
                        'message' => 'test',
                        'status' => 'Verification Request Sent',
                        'user' => 'api/users/4',
                        'imagePath' => 'files/image.png'
                    ],
                    'files' => [
                        'file' => $file,
                    ],
                ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertMatchesResourceItemJsonSchema(VerificationRequest::class);
        $this->assertJsonContains([
            'message' => 'test',
        ]);
    }
}