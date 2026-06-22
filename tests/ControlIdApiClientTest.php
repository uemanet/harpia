<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Modulos\RH\Models\DispositivoAcesso;
use Modulos\RH\Services\ControlIdApiClient;
use PHPUnit\Framework\TestCase;

class ControlIdApiClientTest extends TestCase
{
    public function testModifyUserUsesWhereFilterInsteadOfChangingPrimaryKey(): void
    {
        $history = [];
        $handler = HandlerStack::create(new MockHandler([
            new Response(200, [], json_encode(['session' => 'abc123'], JSON_THROW_ON_ERROR)),
            new Response(200, [], json_encode(['changes' => 1], JSON_THROW_ON_ERROR)),
        ]));
        $handler->push(Middleware::history($history));

        $http = new Client(['handler' => $handler]);
        $client = new ControlIdApiClient($http);

        $dispositivo = new DispositivoAcesso([
            'dis_ip' => '172.16.2.115',
            'dis_login_api' => 'admin',
            'dis_senha_api' => 'admin',
        ]);

        $client->modifyUser($dispositivo, 291, [
            'name' => 'Usuario Teste',
            'registration' => '123',
        ]);

        $this->assertCount(2, $history);

        $request = $history[1]['request'];

        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('/modify_objects.fcgi', $request->getUri()->getPath());
        $this->assertSame('session=abc123', $request->getUri()->getQuery());

        $payload = json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertSame([
            'object' => 'users',
            'values' => [
                'name' => 'Usuario Teste',
                'registration' => '123',
            ],
            'where' => [
                'users' => [
                    'id' => 291,
                ],
            ],
        ], $payload);
        $this->assertArrayNotHasKey('id', $payload['values']);
    }
}
