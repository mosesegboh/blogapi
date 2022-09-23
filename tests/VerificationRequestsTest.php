<?php

namespace App\Tests;

use App\Helpers\Helper;
use App\Repository\UserRepository;
use App\Helpers\Constants;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VerificationRequestsTest extends WebTestCase
{
    /**
     * Testing the PUT Post endpoint
     *
     * @return void
     */
    public function testGetVerificationRequest(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail($this->generateHelper()::AUTH_USER);

        $client->loginUser($testUser);

        $client->request('GET', '/api/verification_requests?page=1');

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
    }

    /**
     * Testing the GET verification request endpoint
     *
     * @return void
     */
    public function testSingleGetVerificationRequest(): void
    {

        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail($this->generateHelper()::AUTH_USER);

        $client->loginUser($testUser);

        $client->request('GET', '/api/verification_requests/1');

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
    }

    public function testGetVerificationDecision(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail($this->generateHelper()::AUTH_USER);

        $client->loginUser($testUser);

        $client->request('GET', '/api/verification_decision/6/1/NotQualified');

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
    }

    /**
     * Testing the protected route for the Verification Request endpoint
     *
     * @return void
     */
    public function testVerificationProtectedRoutes(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/verification_decision/6/1/NotQualified');

        $response = $client->getResponse();
        $this->assertSame(401, $response->getStatusCode());
    }

    /**
     * Generate helper obj for test
     *
     * @return void
     */
    public function generateHelper(): Helper
    {
        return $helper = new Helper();
    }
}