<?php

namespace Harpia\Moodle;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class Moodle
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param array $param - array de parametros para comunicação com o web service do Moodle
     */
    public function send(array $param)
    {
        try {
            $url = $param['url'] . '/webservice/rest/server.php?wstoken=' . $param['token'] . '&wsfunction=' . $param['functioname'] . '&moodlewsrestformat=json';

            $method = 'post';

            if ($param['action'] == 'SELECT') {
                $method = 'get';
            }

            return $this->$method($url, $param['data']);
        } catch (ClientException $e) {

            if (env('app.debug')) {
                throw $e;
            }

            return [
                'message' => "Falha na requisição"
            ];
        }
    }

    private function get($url, $data)
    {
        $response = $this->client->request('GET', $url, ['query' => $data]);

        return (array) json_decode($response->getBody());
    }

    private function post($url, $data)
    {
        $response = $this->client->request('POST', $url, ['form_params' => $data]);

        return (array) json_decode($response->getBody());
    }
}
