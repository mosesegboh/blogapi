<?php

namespace App\Tests;

use App\Helpers\Constants;
use App\Repository\UserRepository;
use App\Helpers\Helper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class UsersTest extends WebTestCase
{
    /**
     * Testing the Post User Api
     *
     * @return void
     */
    public function testCreateUser(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail($this->generateHelper()::AUTH_USER);
        $client->loginUser($testUser);

        $client->jsonRequest('POST', '/api/users',
            [
                'email' => $this->generateHelper()::TEST_USER_REG_EMAIL,
                'password' => $this->generateHelper()::TEST_USER_PASSWORD,
                'firstName' => $this->generateHelper()->generateRandomString(5),
                'lastName' => $this->generateHelper()->generateRandomString(5),
            ]
        );

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertSame(201, $response->getStatusCode());
    }

    /**
     * Testing the index function in Get Api
     *
     * @return void
     */
    public function testGetUser(): void
    {
        $helper = new Helper();
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail($this->generateHelper()::AUTH_USER);
        $client->loginUser($testUser);
        $client->request('GET', '/api/users/6');

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
    }

    /**
     * Testing protected GET route
     *
     * @return void
     */
    public function testUsersProtectedRoutes(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/users/6');

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