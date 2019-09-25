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

    public function setUp(): void
    {
        $this->moodleServiceProvider = new Moodle();
    }

    public function testClient()
    {
        $this->assertInstanceOf(Client::class, $this->moodleServiceProvider->getClient());
    }

    public function testPostRequest()
    {
        // Container para armazenar as requests feitas ao servidor
        $container = [];
        $history = Middleware::history($container);

        // Mock de respostas do servidor
        $mock = new MockHandler([
            new Response(200, ['content-type' => 'application/json'], json_encode([])),
        ]);

        // Response normal do Moodle
        $handler = HandlerStack::create($mock);
        $handler->push($history);
        $client = new Client(['handler' => $handler]);
        $this->moodleServiceProvider->setClient($client);

        $response = $this->moodleServiceProvider->send([
            'url' => 'http://localhost:8080',
            'token' => 'abdefgh123456',
            'functionname' => 'test_post',
            'action' => 'post',
            'data' => [
                'form' => []
            ]
        ]);

        $expectedUrl = "http://localhost:8080/webservice/rest/server.php?wstoken=abdefgh123456&wsfunction=test_post&moodlewsrestformat=json";
        $transaction = array_shift($container);

        $this->assertTrue(is_array($response), "Response must be an array");
        $this->assertSame($expectedUrl, (string)$transaction["request"]->getUri());
        $this->assertEquals('POST', $transaction["request"]->getMethod());
    }

    public function testGetRequest()
    {
        // Container para armazenar as requests feitas ao servidor
        $container = [];
        $history = Middleware::history($container);

        // Mock de respostas do servidor
        $mock = new MockHandler([
            new Response(200, ['content-type' => 'application/text'], json_encode(["response" => true])),
        ]);

        // Response normal do Moodle
        $handler = HandlerStack::create($mock);
        $handler->push($history);
        $client = new Client(['handler' => $handler]);
        $this->moodleServiceProvider->setClient($client);

        $response = $this->moodleServiceProvider->send([
            'url' => 'http://localhost:8080',
            'token' => 'abdefgh123456',
            'functionname' => 'integracao_ping',
            'action' => 'SELECT',
            'data' => []
        ]);

        $expectedUrl = "http://localhost:8080/webservice/rest/server.php?wstoken=abdefgh123456&wsfunction=integracao_ping&moodlewsrestformat=json";

        $transaction = array_shift($container);

        $this->assertTrue(is_array($response), "Response must be an array");
        $this->assertSame($expectedUrl, (string)$transaction["request"]->getUri());
        $this->assertEquals('GET', $transaction["request"]->getMethod());
    }
}
