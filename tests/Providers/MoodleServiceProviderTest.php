<?php

use GuzzleHttp\Client;
use Harpia\Moodle\Moodle;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;

class MoodleServiceProviderTest extends TestCase
{
    protected $moodleServiceProvider;

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

        $response = $this->moodleServiceProvider->send([
            'url' => 'http://localhost:8080',
            'token' => 'abdefgh123456',
            'functioname' => 'test_post',
            'action' => 'post',
            'data' => [
                'form' => []
            ]
        ]);

        $this->assertEquals(1, count($container));

        $transaction = array_shift($container);

        $expectedUrl = "http://localhost:8080/webservice/rest/server.php?wstoken=abdefgh123456&wsfunction=test_post&moodlewsrestformat=json";

        $this->assertTrue(is_array($response), "Response must be an array");
        $this->assertSame($expectedUrl, (string) $transaction["request"]->getUri());
        $this->assertEquals('POST', $transaction["request"]->getMethod());
    }

    public function testGetRequest()
    {
        $container = [];
        $history = Middleware::history($container);

        $stack = HandlerStack::create();
        $stack->push($history);

        $client = new Client(['handler' => $stack]);
        $this->moodleServiceProvider->setClient($client);

        $response = $this->moodleServiceProvider->send([
            'url' => 'http://localhost:8080',
            'token' => 'abdefgh123456',
            'functioname' => 'integracao_ping',
            'action' => 'SELECT',
            'data' => []
        ]);

        $this->assertEquals(1, count($container));

        $transaction = array_shift($container);

        $expectedUrl = "http://localhost:8080/webservice/rest/server.php?wstoken=abdefgh123456&wsfunction=integracao_ping&moodlewsrestformat=json";

        $this->assertTrue(is_array($response), "Response must be an array");
        $this->assertSame($expectedUrl, (string) $transaction["request"]->getUri());
        $this->assertEquals('GET', $transaction["request"]->getMethod());
    }
}
