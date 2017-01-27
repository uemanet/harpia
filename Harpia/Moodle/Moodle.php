<?php

namespace Harpia\Moodle;

use GuzzleHttp\Client;

class Moodle
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @param array $param - array de parametros para comunicação com o web service do Moodle
     */
    public function send(array $param)
    {
        $url = $param['url'].'/webservice/rest/server.php?wstoken='.$param['token'].'&wsfunction='.$param['functioname'].'&moodlewsrestformat=json';

        $method = 'post';

        if ($param['action'] == 'SELECT') {
            $method = 'get';
        }

        return $this->$method($url, $param['data']);
    }

    private function get($url, $data)
    {
        $response = $this->client->request('GET', $url, ['query' => $data]);

        return json_decode($response->getBody());
    }

    private function post($url, $data)
    {
        $response = $this->client->request('POST', $url, ['body' => $data]);

        return json_decode($response->getBody());
    }
}
