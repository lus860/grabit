<?php


namespace App\Services;


class SendGuzzleRequestService
{
    /**
     * @param $url
     * @param $body
     * @param $headers
     * @return int|mixed
     */
    public static function sendByFormParams($url,$body,$headers){
        $client = new \GuzzleHttp\Client();
        $response = $client->post($url,[
            'headers'=>$headers,
            'form_params'=>$body]);
        $answer = json_decode($response->getBody());
        if ($answer){
            return $answer;
        }
        return 0;
    }

    /**
     * @param $url
     * @param $body
     * @param $headers
     * @return int|mixed
     */
    public static function sendByBody($url,$body,$headers){
        $client = new \GuzzleHttp\Client();
        $response = $client->post($url,[
            'headers'=>$headers,
            'body'=>$body]);
        $answer = json_decode($response->getBody());
        if ($answer){
            return $answer;
        }
        return 0;
    }

    /**
     * @param $url
     * @param $body
     * @param $headers
     * @return int|mixed
     */
    public static function sendForDelivery($url,$body,$headers){
        $client = new \GuzzleHttp\Client();
        $response = $client->post($url,[
            'headers'=>$headers,
            'form_params'=>$body]);
        $answer = json_decode($response->getBody());
        if ($answer){
            return $answer;
        }
        return 0;
    }
}