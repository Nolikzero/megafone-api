<?php

namespace Meshgroup\Megafon;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Arr;
use Meshgroup\Megafon\Exceptions\CouldNotSendNotification;

class MegafonApi
{
    /** @var HttpClient */
    protected $client;

    /** @var string */
    protected $endpoint;

    /** @var string */
    protected $from;

    public function __construct(array $config)
    {
        $login = Arr::get($config, 'login');
        $password = Arr::get($config, 'password');
        $this->from = Arr::get($config, 'from');
        $this->endpoint = Arr::get($config, 'host', 'https://a2p-api.megalabs.ru/').'sms/v1/sms';

        $this->client = new HttpClient([
            'timeout' => 5,
            'connect_timeout' => 5,
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($login . ':' . $password),
            ],
        ]);
    }

    public function send($params)
    {
        $base = [
            'from' => $this->from,
        ];

        $params = \array_merge($base, \array_filter($params));

        try {
            $response = $this->client->request('POST', $this->endpoint, ['json' => $params]);
            $response = \json_decode((string) $response->getBody(), true);

            if (!empty($code = $response['result']['status']['code'])) {
                $description = $response['result']['status']['description'];
                throw new \DomainException($description, $code);
            }

            return $response;
        } catch (\DomainException $exception) {
            throw CouldNotSendNotification::megafonRespondedWithAnError($exception);
        } catch (\Exception $exception) {
            throw CouldNotSendNotification::couldNotCommunicateWithMegafon($exception);
        }
    }
}
