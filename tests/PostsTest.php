<?php

namespace App\Tests;

use App\Helpers\Constants;
use App\Repository\UserRepository;
use App\Helpers\Helper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostsTest extends WebTestCase
{
    /**
     * Testing the post Api
     *
     * @return void
     */
    public function testCreatePost(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail($this->generateHelper()::AUTH_USER);

        $client->loginUser($testUser);

        $client->jsonRequest('POST', '/api/posts',
                [
                    'date' => $this->generateHelper()::TEST_DATE,
                    'title' => $this->generateHelper()::TEST_TEXT,
                    'content' => $this->generateHelper()::TEST_TEXT,
                    'user' =>  $this->generateHelper()::TEST_REL_USER
                ]
            );

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertSame(201, $response->getStatusCode());
    }

    /**
     * Testing the GET Post endpoint
     *
     * @return void
     */
    public function testGetPosts(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail($this->generateHelper()::AUTH_USER);

        $client->loginUser($testUser);

        $client->request('GET', '/api/posts/1');

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
    }

    /**
     * Testing the GET All Post endpoint
     *
     * @return void
     */
    public function testAllGetPosts(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/posts?page=1');

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
    }

    /**
     * Testing the PUT Post endpoint
     *
     * @return void
     */
    public function testPutPosts(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail($this->generateHelper()::AUTH_USER);

        $client->loginUser($testUser);

        $client->jsonRequest('PUT', '/api/posts/1',
            [
                'date' => $this->generateHelper()::TEST_DATE,
                'title' => $this->generateHelper()::TEST_TEXT,
                'content' => $this->generateHelper()::TEST_TEXT,
                'user' =>  $this->generateHelper()::TEST_REL_USER
            ]
        );

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
    }

    /**
     * Testing the proteted route Post endpoint
     *
     * @return void
     */
    public function testPostsProtectedRoutes()
    {
        $client = static::createClient();

        $client->jsonRequest('POST', '/api/posts',
            [
                'date' => $this->generateHelper()::TEST_DATE,
                'title' => $this->generateHelper()::TEST_TEXT,
                'content' => $this->generateHelper()::TEST_TEXT,
                'user' =>  $this->generateHelper()::TEST_REL_USER
            ]
        );

        $response = $client->getResponse();
        $this->assertSame(401, $response->getStatusCode());
    }

    /**
     * Testing the validation for the Post endpoint
     *
     * @return void
     */
    public function testPostValidation()
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail($this->generateHelper()::AUTH_USER);

        $client->loginUser($testUser);

        $client->jsonRequest('POST', '/api/posts',
            [
                'date' => $this->generateHelper()::TEST_DATE,
                'title' => '',
                'content' => $this->generateHelper()::TEST_TEXT,
                'user' =>  $this->generateHelper()::TEST_REL_USER
            ]
        );

        $response = $client->getResponse();
        $this->assertSame(422, $response->getStatusCode());
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