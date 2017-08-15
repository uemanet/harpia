<?php

use GuzzleHttp\Client;
use Harpia\Moodle\Moodle;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;

class MoodleServiceProviderTest extends TestCase
{
    protected $moodleServiceProvider;

    protected $successResponseBody = [
        'id' => 1,
        'status' => 'success',
        'message' => "Recurso criado com sucesso"
    ];

    protected $failureResponseBody = [
        'message' => "Falha"
    ];


    public function setUp()
    {
        $this->moodleServiceProvider = new Moodle();
    }

    public function testClient()
    {
        $this->assertInstanceOf(Client::class, $this->moodleServiceProvider->getClient());
    }

    public function testPostRequest()
    {
        $container = [];
        $history = Middleware::history($container);

        $stack = HandlerStack::create();
        $stack->push($history);

        $client = new Client(['handler' => $stack]);
        $this->moodleServiceProvider->setClient($client);

        $this->moodleServiceProvider->send([
            'url' => 'http://localhost/',
            'token' => 'abdefgh123456',
            'functioname' => 'function_post_test',
            'action' => 'post',
            'data' => [
                'form' => 'data'
            ]
        ]);

        $this->assertEquals(1, count($container));

        $transaction = array_shift($container);

        $expectedUrl = "http://localhost/webservice/rest/server.php?wstoken=abdefgh123456&wsfunction=function_post_test&moodlewsrestformat=json";
        $this->assertSame($expectedUrl, $transaction->getUrl());
        $this->assertEquals('POST', $transaction->getMethod());
    }

    public function testGetRequest()
    {
        $container = [];
        $history = Middleware::history($container);

        $stack = HandlerStack::create();
        $stack->push($history);

        $client = new Client(['handler' => $stack]);
        $this->moodleServiceProvider->setClient($client);

        $this->moodleServiceProvider->send([
            'url' => 'http://localhost/',
            'token' => 'abdefgh123456',
            'functioname' => 'function_get_test',
            'action' => 'SELECT',
            'data' => [
                'form' => 'data'
            ]
        ]);

        $this->assertEquals(1, count($container));

        $transaction = array_shift($container);

        $expectedUrl = "http://localhost/webservice/rest/server.php?wstoken=abdefgh123456&wsfunction=function_get_test&moodlewsrestformat=json";

        $this->assertEquals($expectedUrl, $transaction->getUrl());
        $this->assertEquals('GET', $transaction->getMethod());
    }
}
