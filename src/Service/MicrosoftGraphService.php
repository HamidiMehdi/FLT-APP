<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class MicrosoftGraphService
{
    /**
     * @param $token
     * @return |null
     */
    public function getLocalisationUser($token)
    {
        $client = new Client();
        try {
            $response = $client->request(
                'GET',
                'https://graph.windows.net/me?api-version=1.0',
                ['headers' =>
                    [
                        'Authorization' => 'Bearer ' . $token
                    ]
                ]
            )->getBody()->getContents();
            $response = json_decode($response,true);

            return $response['physicalDeliveryOfficeName'];
        } catch (GuzzleException $e) {

            return null;
        }
    }
}