<?php

namespace Modulos\RH\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;
use JsonException;
use Modulos\RH\Models\DispositivoAcesso;

class ControlIdApiClient
{
    private Client $http;

    private array $cacheSessoes = [];

    public function __construct(?Client $http = null)
    {
        $this->http = $http ?? new Client([
            'timeout' => 30,
            'connect_timeout' => 10,
        ]);
    }

    public function ping(DispositivoAcesso $dispositivo): array
    {
        return $this->getConfiguration($dispositivo);
    }

    public function getSystemInfo(DispositivoAcesso $dispositivo): array
    {
        return $this->loadObjects($dispositivo, 'system_info');
    }

    public function getConfiguration(DispositivoAcesso $dispositivo): array
    {
        return $this->post($dispositivo, '/get_configuration.fcgi', []);
    }

    public function loadObjects(DispositivoAcesso $dispositivo, string $object, array $filters = [], ?int $loadAfterId = null): array
    {
        $payload = ['object' => $object];

        if (!empty($filters)) {
            $payload = array_merge($payload, $filters);
        }

        if ($loadAfterId !== null) {
            $payload['load_after_id'] = $loadAfterId;
        }

        return $this->post($dispositivo, '/load_objects.fcgi', $payload);
    }

    public function createUser(DispositivoAcesso $dispositivo, array $userData): array
    {
        return $this->post($dispositivo, '/create_objects.fcgi', [
            'object' => 'users',
            'values' => [$userData],
        ]);
    }

    public function createUserGroup(DispositivoAcesso $dispositivo, int $userId, int $groupId): array
    {
        return $this->post($dispositivo, '/create_objects.fcgi', [
            'object' => 'user_groups',
            'values' => [[
                'user_id' => $userId,
                'group_id' => $groupId,
            ]],
        ]);
    }

    public function modifyUser(DispositivoAcesso $dispositivo, int $userId, array $userData): array
    {
        return $this->post($dispositivo, '/modify_objects.fcgi', [
            'object' => 'users',
            'values' => $userData,
            'where' => [
                'users' => [
                    'id' => $userId,
                ],
            ],
        ]);
    }

    public function destroyUser(DispositivoAcesso $dispositivo, int $userId): array
    {
        return $this->post($dispositivo, '/destroy_objects.fcgi', [
            'object' => 'users',
            'where' => [
                'users' => [
                    'id' => $userId,
                ],
            ],
        ]);
    }

    public function setUserImage(
        DispositivoAcesso $dispositivo,
        int $userId,
        string $imageBinary,
        ?int $timestamp = null,
        bool $match = false
    ): array {
        return $this->postBinario($dispositivo, '/user_set_image.fcgi', $imageBinary, [
            'user_id' => $userId,
            'timestamp' => $timestamp ?? time(),
            'match' => $match ? 1 : 0,
        ]);
    }

    public function destroyUserImage(DispositivoAcesso $dispositivo, int $userId): array
    {
        return $this->post($dispositivo, '/user_destroy_image.fcgi', [
            'user_id' => $userId,
        ]);
    }

    public function getUserImage(DispositivoAcesso $dispositivo, int $userId): string
    {
        return $this->get($dispositivo, '/user_get_image.fcgi', [
            'user_id' => $userId,
        ]);
    }

    public function testUserImage(DispositivoAcesso $dispositivo, string $imageBinary): array
    {
        return $this->postBinario($dispositivo, '/user_test_image.fcgi', $imageBinary);
    }

    // ─── Métodos internos ────────────────────────────────────────────

    private function baseUrl(DispositivoAcesso $dispositivo): string
    {
        if (!$dispositivo->dis_ip) {
            throw new InvalidArgumentException('O dispositivo informado nao possui IP configurado.');
        }

        return 'http://' . trim((string) $dispositivo->dis_ip, '/');
    }

    private function obterSessao(DispositivoAcesso $dispositivo): string
    {
        $chave = 'controlid_session_' . $dispositivo->dis_id;

        if (isset($this->cacheSessoes[$chave])) {
            return $this->cacheSessoes[$chave];
        }

        $response = $this->login($dispositivo);

        if (empty($response['session']) || !is_string($response['session'])) {
            throw new InvalidArgumentException('Nao foi possivel obter uma sessao valida no iDFace.');
        }

        $this->cacheSessoes[$chave] = $response['session'];

        return $response['session'];
    }

    public function invalidarSessao(DispositivoAcesso $dispositivo): void
    {
        $chave = 'controlid_session_' . $dispositivo->dis_id;
        unset($this->cacheSessoes[$chave]);
        Cache::forget($chave);
    }

    private function login(DispositivoAcesso $dispositivo): array
    {
        return $this->postSemSessao($dispositivo, '/login.fcgi', [
            'login' => $this->resolverLogin($dispositivo),
            'password' => $this->resolverSenha($dispositivo),
        ]);
    }

    private function resolverLogin(DispositivoAcesso $dispositivo): string
    {
        $login = $dispositivo->getAttribute('dis_login_api');

        if (is_string($login) && $login !== '') {
            return $login;
        }

        return $this->resolverConfiguracao('services.controlid.login', 'CONTROLID_LOGIN', 'admin');
    }

    private function resolverSenha(DispositivoAcesso $dispositivo): string
    {
        $senha = $dispositivo->getAttribute('dis_senha_api');

        if (is_string($senha) && $senha !== '') {
            return $senha;
        }

        return $this->resolverConfiguracao('services.controlid.password', 'CONTROLID_PASSWORD', 'admin');
    }

    private function resolverConfiguracao(string $configKey, string $envKey, string $default): string
    {
        if (function_exists('app')) {
            try {
                $app = app();
                if ($app && $app->bound('config')) {
                    return (string) config($configKey, $default);
                }
            } catch (\Throwable $e) {
            }
        }

        $value = getenv($envKey);
        return $value !== false && $value !== '' ? (string) $value : $default;
    }

    private function post(DispositivoAcesso $dispositivo, string $endpoint, array $payload): array
    {
        $session = $this->obterSessao($dispositivo);
        return $this->postSemSessao($dispositivo, $endpoint . '?session=' . urlencode($session), $payload);
    }

    private function postBinario(
        DispositivoAcesso $dispositivo,
        string $endpoint,
        string $conteudo,
        array $query = []
    ): array {
        $session = $this->obterSessao($dispositivo);
        $query = array_merge($query, ['session' => $session]);

        try {
            $response = $this->http->request('POST', $this->baseUrl($dispositivo) . $endpoint, [
                'query' => $query,
                'headers' => ['Content-Type' => 'application/octet-stream'],
                'body' => $conteudo,
            ]);

            return $this->decodificarResposta($response->getStatusCode(), (string) $response->getBody());
        } catch (ConnectException $e) {
            throw new InvalidArgumentException(
                sprintf('Dispositivo %s inacessivel: %s', $dispositivo->dis_ip, $e->getMessage()),
                0,
                $e
            );
        }
    }

    private function get(DispositivoAcesso $dispositivo, string $endpoint, array $query = []): string
    {
        $session = $this->obterSessao($dispositivo);
        $query = array_merge($query, ['session' => $session]);

        try {
            $response = $this->http->request('GET', $this->baseUrl($dispositivo) . $endpoint, [
                'query' => $query,
            ]);

            return (string) $response->getBody();
        } catch (ConnectException $e) {
            throw new InvalidArgumentException(
                sprintf('Dispositivo %s inacessivel: %s', $dispositivo->dis_ip, $e->getMessage()),
                0,
                $e
            );
        }
    }

    private function postSemSessao(DispositivoAcesso $dispositivo, string $endpoint, array $payload): array
    {
        try {
            $response = $this->http->request('POST', $this->baseUrl($dispositivo) . $endpoint, [
                'json' => empty($payload) ? (object) [] : $payload,
            ]);

            return $this->decodificarResposta($response->getStatusCode(), (string) $response->getBody());
        } catch (ConnectException $e) {
            throw new InvalidArgumentException(
                sprintf('Dispositivo %s inacessivel: %s', $dispositivo->dis_ip, $e->getMessage()),
                0,
                $e
            );
        }
    }

    private function decodificarResposta(int $statusCode, string $body): array
    {
        try {
            $decoded = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

            if (is_array($decoded)) {
                return $decoded;
            }

            return ['value' => $decoded, 'status_code' => $statusCode];
        } catch (JsonException $e) {
            return ['raw' => $body, 'status_code' => $statusCode];
        }
    }
}
